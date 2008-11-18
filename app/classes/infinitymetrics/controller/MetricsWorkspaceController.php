<?php

require_once('propel/Propel.php');

Propel::init("infinitymetrics/orm/config/om-conf.php");

/**
 * Description of MetricsWorkspaceController
 *
 * @author Andres Ardila
 */
class MetricsWorkspaceController
{    
    public function createWorkspace($user_id, $title, $description)
    {
        if ($user_id == '' || $user_id == NULL) {
            throw new Exception('The user_id is empty');
        }
        else if ($title == '' || $title == NULL) {
            throw new Exception('The title is empty');
        }
        else if ($description == '' || $description == NULL) {
            throw new Exception('The description is empty');
        }
        else {
            $user = PersistentUserPeer::retrieveByPK($user_id);

            if ($user == NULL) {
                throw new Exception('The user_id does not exist');
            }
            if ($user->getType() != 'I') {
                throw new Exception('The user_id does not correspond to an Instructor');
            }

            $ws = new PersistentWorkspace();
            $ws->setUserId($user_id);
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

    public function retrieveWorkspaceCollection($user_id) {
        if ($user_id == '' || $user_id == NULL) {
            throw new Exception('The user_id is empty');
        }
        else {
            $user = PersistentUserPeer::retrieveByPK($user_id);

            if ($user == NULL) {
                throw new Exception('The user_id does not exist');
            }

            $workspaces = array( 'OWN' => array(), 'SHARED' => array() );

            $workspaces['OWN'] = PersistentWorkspacePeer::retrieveByPK($user_id);

            $wsShareCriteria = new Criteria();
            $wsShareCriteria->add(WorkspaceSharePeer::user_id, $user_id);

            $workspaceShares = PersistentWorkspaceSharePeer::doSelect($wsShareCriteria);

            foreach ($workspaceShares as $wss) {
                $ws = PersistentWorkspacePeer::retrieveByPK( $wss->getWorkspaceId() );

                array_push($workspaces['OWN'], $ws);
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
    
    public function shareWorkspace($workspace_id, $userIdWithWhomToShareWorkspace) {
        if ($workspace_id == '') {
            throw new Exception('The workspace_id is empty');
        }
        if ($userIdWithWhomToShareWorkspace == '') {
            throw new Exception('The user_id is empty');
        }

        $wss = new WorkspaceShare();

        if ($wss == NULL) {
            throw new Exception('The workspace_id does not exist');
        }

        $wss->setWorkspaceId($workspace_id);

        $user = PersistentUserPeer::retrieveByPK($userIdWithWhomToShareWorkspace);

        if ($user == NULL){
            throw new Exception('The user_id does not exist');
        }

        $user->addWorkspaceShare($wss);
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
