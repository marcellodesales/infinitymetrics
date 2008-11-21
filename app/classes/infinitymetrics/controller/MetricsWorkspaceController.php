<?php

require_once 'infinitymetrics/model/workspace/MetricsWorkspace.class.php';

/**
 * Description of MetricsWorkspaceController
 *
 * @author Andres Ardila
 */
class MetricsWorkspaceController
{    
    public function createWorkspace($jn_username, $title, $description)
    {
        if ($jn_username == '' || $jn_username == NULL) {
            throw new Exception('The java.net username is empty');
        }
        else if ($title == '' || $title == NULL) {
            throw new Exception('The title is empty');
        }
        else if ($description == '' || $description == NULL) {
            throw new Exception('The description is empty');
        }
        else {
            $user = PersistentUserPeer::retrieveByJNUsername($jn_username);

            if ($user == NULL) {
                throw new Exception('The java.net username does not exist');
            }
            if ($user->getType() != 'I') {
                throw new Exception('The java.net username does not correspond to an Instructor');
            }

            $ws = new PersistentWorkspace();
            $ws->setUserId( $user->getUserId() );
            $ws->setTitle($title);
            $ws->setDescription($description);
            $ws->save();

            return $ws;
        }
    }

    public function changeWorkspaceState($workspace_id, $newState) {
        $validStates = array('NEW', 'ACTIVE', 'INACTIVE', 'PAUSED');
        
        if ($workspace_id == '' || $workspace_id == NULL) {
            throw new Exception('The workspace_id is empty');
        }
        if ($newState == '' || $newState == NULL) {
            throw new Exception('The new state is empty');
        }
        if ( array_search($newState, $validStates) === FALSE ) {
            throw new Exception('The new state does not match any allowed states');
        }

        $ws = PersistentWorkspacePeer::retrieveByPK($workspace_id);

        if ($ws == NULL) {
            throw new Exception('The workspace_id does not exist');
        }

        $ws->setState($newState);
        $ws->save();

        return $ws;
    }

    public function retrieveWorkspaceCollection($jn_username) {
        if ($jn_username == '' || $jn_username == NULL) {
            throw new Exception('The java.net username is empty');
        }
        else {
            $user = PersistentUserPeer::retrieveByJNUsername($jn_username);

            if ($user == NULL) {
                throw new Exception('The java.net username does not exist');
            }

            $workspaces = array( 'OWN' => array(), 'SHARED' => array() );
            
            $wsCriteria = new Criteria();
            $wsCriteria->add(PersistentWorkspacePeer::USER_ID, $user->getPrimaryKey());
            
            $workspaces['OWN'] = PersistentWorkspacePeer::doSelect($wsCriteria);

            $wsShareCriteria = new Criteria();
            $wsShareCriteria->add(PersistentWorkspaceSharePeer::USER_ID, $user->getPrimaryKey() );

            $workspaceShares = PersistentWorkspaceSharePeer::doSelect($wsShareCriteria);

            foreach ($workspaceShares as $wss) {
                $ws = PersistentWorkspacePeer::retrieveByPK( $wss->getWorkspaceId() );

                array_push($workspaces['SHARED'], $ws);
            }

            return $workspaces;
        }
    }
    
    public function retrieveWorkspace($workspace_id) {
        if ($workspace_id == '' || $workspace_id == NULL) {
            throw new Exception('The workpsace_id is empty');
        }
        else {
            return ( PersistentWorkspacePeer::retrieveByPK($workspace_id) );
        }
        
    }
    
    public function shareWorkspace($workspace_id, $jnUsernameWithWhomToShareWorkspace) {
        if ($workspace_id == '') {
            throw new Exception('The workspace_id is empty');
        }
        if ($jnUsernameWithWhomToShareWorkspace == '') {
            throw new Exception('The java.net username is empty');
        }
        if (PersistentWorkspacePeer::retrieveByPK($workspace_id) == NULL) {
            throw new Exception('The workspace_id does not exist');
        }

        $user = PersistentUserPeer::retrieveByJNUsername($jnUsernameWithWhomToShareWorkspace);

        if ($user == NULL){
            throw new Exception('The java.net username does not exist');
        }

        if ( PersistentWorkspace::isSharedWithUser($workspace_id, $jnUsernameWithWhomToShareWorkspace) )
        {
            throw new Exception('The workspace is already being shared');
        }
        $wss = new PersistentWorkspaceShare();
        $wss->setWorkspaceId($workspace_id);
        $wss->setUserId($user->getUserId());
        $wss->save();
        return;
    }
    
    public function updateWorkspaceProfile($workspace_id, $newTitle, $newDescription) {
        if ($workspace_id == '' || $workspace_id == NULL) {
            throw new Exception('The workspace_id is empty');
        }
        else if ($newTitle == '' || $newTitle == NULL) {
            throw new Exception('The new title is empty');
        }
        else if ($newDescription == '' || $newDescription == NULL) {
            throw new Exception('The new description is empty');
        }
        else {
            $ws = PersistentWorkspacePeer::retrieveByPK($workspace_id);

            if ($ws == NULL) {
                throw new Exception('Did not find a Workspace by that ID');
            }
            else {
                $ws->setTitle($newTitle);
                $ws->setDescription($newDescription);
                $ws->save();

                return $ws;
            }
        }
    }
}
?>
