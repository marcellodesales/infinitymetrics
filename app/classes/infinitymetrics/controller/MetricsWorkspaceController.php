<?php

require_once('propel/Propel.php');

Propel::init("infinitymetrics/orm/conf/infinitymetrics-conf.php");

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
            $user = UserPeer::retrieveByPK($user_id);

            if ($user == NULL) {
                throw new Exception('The user_id does not exist');
            }
            if ($user->getType() != 'INSTRUCTOR') {
                throw new Exception('The user_id does not correspond to an Instructor');
            }

            $ws = new Workspace();
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
        else if ($newState == '' || $newState == NULL) {
            throw new Exception('The new state is empty');
        }
        else if ( array_search($newState, $validStates) === FALSE ) {
            throw new Exception('The new state does not match any allowed states');
        }
        else {
            $ws = WorkspacePeer::retrieveByPK($workspace_id);

            if ($ws == NULL) {
                throw new Exception('The workspace_id does not exist');
            }
            $ws->setState($newState);
            $ws->save();

            return $ws;
        }
    }

    public function retrieveWorkspaceCollection($user_id) {
        if ($user_id == '') {
            throw new Exception('The user_id is empty');
        }
        else {
            $workspaces = array( 'OWN' => array(), 'SHARED' => array() );

            $workspaces['OWN'] = WorkspacePeer::retrieveByPK($user_id);

            $wsShareCriteria = new Criteria();
            $wsShareCriteria->add(WorkspaceSharePeer::user_id, $user_id);

            $workspaceShares = WorkspaceSharePeer::doSelect($wsShareCriteria);

            foreach ($workspaceShares as $wss) {
                $ws = WorkspacePeer::retrieveByPK( $wss->getWorkspaceId() );

                array_push($workspaces['OWN'], $ws);
            }

            return $workspaces;
        }
    }
    
    public function retrieveWorkspace($workspace_id) {
        if ($workspace_id == '') {
            throw new Exception('The workpsace_id is empty');
        }
        else {
            return ( WorkspacePeer::retrieveByPK($workspace_id) );
        }
        
    }
    
    public function shareWorkspace($workspace_id, $user_id) {
        $errors = array();

        if ($workspace_id == '') {
            $errors['workspace_id'] = 'The workspace_id is empty';
        }
        if ($user_id == '') {
            $errors['user_id'] = new Exception('The user_id is empty');
        }

        if (count($errors)) {
            throw new Exception('The parameters are invalid', $errors);
        }
        else {
            $wss = new WorkspaceShare();
            $wss->setWorkspaceId($workspace_id);

            $user = UserPeer::retrieveByPK($user_id);
            $user->addWorkspaceShare($wss);
        }
    }
    
    public function updateWorkspaceProfile($workspace_id, $newTitle, $newDescription) {
        $errors = array();

        if ($workspace_id == '') {
            $errors['workspace_id'] = 'The workspace_id is empty';
        }
        if ($newTitle == '') {
            $errors['newTitle'] = 'The new title is empty';
        }
        if ($newDescription == '') {
            $errors['newDescription'] = 'The new description is empty';
        }

        if (count($errors)) {
            throw new Exception('The parameters are invalid', $errors);
        }
        else {
            $ws = WorkspacePeer::retrieveByPK($workspace_id);

            if ($ws == NULL) {
                throw new Exception('Did not find a Workspace by that ID');
            }
            else {
                $ws->setTitle($newTitle);
                $ws->setDescription($newDescription);
                $ws->save();
            }
        }
    }
}
?>
