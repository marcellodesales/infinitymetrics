<?php

require 'infinitymetrics/orm/om/PersistentBaseUser.php';
require_once 'infinitymetrics/orm/PersistentUserXProjectPeer.php';

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0 on:
 *
 * 11/27/08 02:02:54
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    infinitymetrics/orm
 */
class PersistentUser extends PersistentBaseUser {

	/**
	 * Initializes internal state of PersistentUser object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

    /**
     * @param PersistentProject $project is the instance of a project
     * @return boolean if the user is the owner of a given project
     */
    public function isOwnerOfProject(PersistentProject $project) {
        $stXProjec = PersistentUserXProjectPeer::retrieveByPK($this->getJnUsername(), $project->getProjectJnName());
        return $stXProjec == null ? null : $stXProjec->getIsOwner() == 1;
    }
    /**
     * @return boolean verifies if the instance is a student
     */
    public function isStudent() {
        return $this->getType() == UserTypeEnum::getInstance()->STUDENT;
    }
    /**
     * @return boolean verifies if the instance is an instructor
     */
    public function isInstructor() {
        return $this->getType() == UserTypeEnum::getInstance()->INSTRUCTOR;
    }
    /**
     * @return boolean verifies if a regular Java.net user
     */
    public function isRegularJnUser() {
        return $this->getType() == UserTypeEnum::getInstance()->JAVANET;
    }
} // PersistentUser
