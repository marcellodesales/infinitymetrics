<?php

require 'infinitymetrics/orm/om/PersistentBaseWorkspace.php';


/**
 * Skeleton subclass for representing a row from the 'workspace' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0 on:
 *
 * 11/18/08 13:07:33
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    infinitymetrics/orm
 */
class PersistentWorkspace extends PersistentBaseWorkspace {

	/**
	 * Initializes internal state of PersistentWorkspace object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

    public function builder($creatorUserId, $description, $title) {
        $this->description = $description;
        $this->title = $title;
        $this->user_id = $creatorUserId;
    }

    public function isShared($workspace_id) {
        if ($workspace_id == '' || $workspace_id == NULL) {
            throw new Exception('workspace_id is empty');
        }
        else {
            $ws = PersistentWorkspacePeer::retrieveByPK($workspace_id);

            if ($ws == NULL) {
                throw new Exception('workspace_id does not exist');
            }
            else {
                $wsShares = $ws->getWorkspaceShares();

                if ($wsShares == NULL) {
                    return false;
                }
                else {
                    return true;
                }
            }
        }
    }

    public function isSharedWithUser($workspace_id, $user_id) {
        if ($workspace_id == '' || $workspace_id == NULL) {
            throw new Exception('workspace_id is empty');
        }
        if ($user_id == '' || $user_id == NULL) {
            throw new Exception('user_id is empty');
        }

        $ws = PersistentWorkspacePeer::retrieveByPK($workspace_id);

        if ($ws == NULL) {
            throw new Exception('workspace_id does not exist');
        }
        if ( !$ws->isShared($workspace_id) ) {
            return false;
        }
        else {
            $wsShares = $ws->getWorkspaceShares();

            foreach ($wsShares as $wss) {
                if ($wss->getUserId() == $user_id) {
                    return true;
                }
            }
            return false;
        }
    }

} // PersistentWorkspace
