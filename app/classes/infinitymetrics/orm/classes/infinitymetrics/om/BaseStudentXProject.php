<?php

/**
 * Base class that represents a row from the 'student_x_project' table.
 *
 * 
 *
 * @package    infinitymetrics.om
 */
abstract class BaseStudentXProject extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        StudentXProjectPeer
	 */
	protected static $peer;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the project_jn_name field.
	 * @var        string
	 */
	protected $project_jn_name;

	/**
	 * The value for the is_leader field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $is_leader;

	/**
	 * @var        Project
	 */
	protected $aProject;

	/**
	 * @var        Student
	 */
	protected $aStudent;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Initializes internal state of BaseStudentXProject object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->is_leader = 0;
	}

	/**
	 * Get the [user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * Get the [project_jn_name] column value.
	 * 
	 * @return     string
	 */
	public function getProjectJnName()
	{
		return $this->project_jn_name;
	}

	/**
	 * Get the [is_leader] column value.
	 * 
	 * @return     int
	 */
	public function getIsLeader()
	{
		return $this->is_leader;
	}

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     StudentXProject The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = StudentXProjectPeer::USER_ID;
		}

		if ($this->aStudent !== null && $this->aStudent->getUserId() !== $v) {
			$this->aStudent = null;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [project_jn_name] column.
	 * 
	 * @param      string $v new value
	 * @return     StudentXProject The current object (for fluent API support)
	 */
	public function setProjectJnName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->project_jn_name !== $v) {
			$this->project_jn_name = $v;
			$this->modifiedColumns[] = StudentXProjectPeer::PROJECT_JN_NAME;
		}

		if ($this->aProject !== null && $this->aProject->getProjectJnName() !== $v) {
			$this->aProject = null;
		}

		return $this;
	} // setProjectJnName()

	/**
	 * Set the value of [is_leader] column.
	 * 
	 * @param      int $v new value
	 * @return     StudentXProject The current object (for fluent API support)
	 */
	public function setIsLeader($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->is_leader !== $v || $v === 0) {
			$this->is_leader = $v;
			$this->modifiedColumns[] = StudentXProjectPeer::IS_LEADER;
		}

		return $this;
	} // setIsLeader()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
			// First, ensure that we don't have any columns that have been modified which aren't default columns.
			if (array_diff($this->modifiedColumns, array(StudentXProjectPeer::IS_LEADER))) {
				return false;
			}

			if ($this->is_leader !== 0) {
				return false;
			}

		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->user_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->project_jn_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->is_leader = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 3; // 3 = StudentXProjectPeer::NUM_COLUMNS - StudentXProjectPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating StudentXProject object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

		if ($this->aStudent !== null && $this->user_id !== $this->aStudent->getUserId()) {
			$this->aStudent = null;
		}
		if ($this->aProject !== null && $this->project_jn_name !== $this->aProject->getProjectJnName()) {
			$this->aProject = null;
		}
	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(StudentXProjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = StudentXProjectPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aProject = null;
			$this->aStudent = null;
		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(StudentXProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			StudentXProjectPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(StudentXProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			StudentXProjectPeer::addInstanceToPool($this);
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aProject !== null) {
				if ($this->aProject->isModified() || $this->aProject->isNew()) {
					$affectedRows += $this->aProject->save($con);
				}
				$this->setProject($this->aProject);
			}

			if ($this->aStudent !== null) {
				if ($this->aStudent->isModified() || $this->aStudent->isNew()) {
					$affectedRows += $this->aStudent->save($con);
				}
				$this->setStudent($this->aStudent);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = StudentXProjectPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += StudentXProjectPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aProject !== null) {
				if (!$this->aProject->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aProject->getValidationFailures());
				}
			}

			if ($this->aStudent !== null) {
				if (!$this->aStudent->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aStudent->getValidationFailures());
				}
			}


			if (($retval = StudentXProjectPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(StudentXProjectPeer::DATABASE_NAME);

		if ($this->isColumnModified(StudentXProjectPeer::USER_ID)) $criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(StudentXProjectPeer::PROJECT_JN_NAME)) $criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);
		if ($this->isColumnModified(StudentXProjectPeer::IS_LEADER)) $criteria->add(StudentXProjectPeer::IS_LEADER, $this->is_leader);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(StudentXProjectPeer::DATABASE_NAME);

		$criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);
		$criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

		return $criteria;
	}

	/**
	 * Returns the composite primary key for this object.
	 * The array elements will be in same order as specified in XML.
	 * @return     array
	 */
	public function getPrimaryKey()
	{
		$pks = array();

		$pks[0] = $this->getUserId();

		$pks[1] = $this->getProjectJnName();

		return $pks;
	}

	/**
	 * Set the [composite] primary key.
	 *
	 * @param      array $keys The elements of the composite key (order must match the order in XML file).
	 * @return     void
	 */
	public function setPrimaryKey($keys)
	{

		$this->setUserId($keys[0]);

		$this->setProjectJnName($keys[1]);

	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of StudentXProject (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUserId($this->user_id);

		$copyObj->setProjectJnName($this->project_jn_name);

		$copyObj->setIsLeader($this->is_leader);


		$copyObj->setNew(true);

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     StudentXProject Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     StudentXProjectPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new StudentXProjectPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Project object.
	 *
	 * @param      Project $v
	 * @return     StudentXProject The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setProject(Project $v = null)
	{
		if ($v === null) {
			$this->setProjectJnName(NULL);
		} else {
			$this->setProjectJnName($v->getProjectJnName());
		}

		$this->aProject = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Project object, it will not be re-added.
		if ($v !== null) {
			$v->addStudentXProject($this);
		}

		return $this;
	}


	/**
	 * Get the associated Project object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Project The associated Project object.
	 * @throws     PropelException
	 */
	public function getProject(PropelPDO $con = null)
	{
		if ($this->aProject === null && (($this->project_jn_name !== "" && $this->project_jn_name !== null))) {
			$this->aProject = ProjectPeer::retrieveByPK($this->project_jn_name, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aProject->addStudentXProjects($this);
			 */
		}
		return $this->aProject;
	}

	/**
	 * Declares an association between this object and a Student object.
	 *
	 * @param      Student $v
	 * @return     StudentXProject The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setStudent(Student $v = null)
	{
		if ($v === null) {
			$this->setUserId(NULL);
		} else {
			$this->setUserId($v->getUserId());
		}

		$this->aStudent = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Student object, it will not be re-added.
		if ($v !== null) {
			$v->addStudentXProject($this);
		}

		return $this;
	}


	/**
	 * Get the associated Student object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Student The associated Student object.
	 * @throws     PropelException
	 */
	public function getStudent(PropelPDO $con = null)
	{
		if ($this->aStudent === null && ($this->user_id !== null)) {
			$this->aStudent = StudentPeer::retrieveByPK($this->user_id, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aStudent->addStudentXProjects($this);
			 */
		}
		return $this->aStudent;
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} // if ($deep)

			$this->aProject = null;
			$this->aStudent = null;
	}

} // BaseStudentXProject
