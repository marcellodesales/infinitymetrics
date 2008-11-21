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

    public function isShared() {
        if ($this->workspace_id == '' || $this->workspace_id == NULL) {
            throw new Exception('workspace is empty');
        }

        $criteria = new Criteria();
        $criteria->add(PersistentWorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);

        $wsShares = PersistentWorkspaceSharePeer::doSelect($criteria);

        if ($wsShares == NULL) {
            return false;
        }
        else {
            return true;
        }
    }

    public function isSharedWithUser($user_id) {
        if ($this->workspace_id == '' || $this->workspace_id == NULL) {
            throw new Exception('workspace is empty');
        }
        if ( PersistentUserPeer::retrieveByPK($user_id) == NULL ) {
            throw new Exception('user does not exist');
        }

        if ( ! $this->isShared() ) {
            return false;
        }
        else {
            $criteria = new Criteria();
            $criteria->add(PersistentWorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);
            $criteria->add(PersistentWorkspaceSharePeer::USER_ID, $user_id);

            $wsShares = PersistentWorkspaceSharePeer::doSelect($criteria);

            foreach ($wsShares as $wss) {
                if ($wss->getUserId() == $user_id) {
                    return true;
                }
            }
            return false;
        }
    }

} // PersistentWorkspace
