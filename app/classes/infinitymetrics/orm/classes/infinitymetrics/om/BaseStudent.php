<?php

/**
 * Base class that represents a row from the 'student' table.
 *
 * 
 *
 * @package    infinitymetrics.om
 */
abstract class BaseStudent extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        StudentPeer
	 */
	protected static $peer;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the institution_id field.
	 * @var        int
	 */
	protected $institution_id;

	/**
	 * @var        Institution
	 */
	protected $aInstitution;

	/**
	 * @var        array StudentXProject[] Collection to store aggregation of StudentXProject objects.
	 */
	protected $collStudentXProjects;

	/**
	 * @var        Criteria The criteria used to select the current contents of collStudentXProjects.
	 */
	private $lastStudentXProjectCriteria = null;

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
	 * Initializes internal state of BaseStudent object.
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
	 * Get the [institution_id] column value.
	 * 
	 * @return     int
	 */
	public function getInstitutionId()
	{
		return $this->institution_id;
	}

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Student The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = StudentPeer::USER_ID;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [institution_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Student The current object (for fluent API support)
	 */
	public function setInstitutionId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->institution_id !== $v) {
			$this->institution_id = $v;
			$this->modifiedColumns[] = StudentPeer::INSTITUTION_ID;
		}

		if ($this->aInstitution !== null && $this->aInstitution->getInstitutionId() !== $v) {
			$this->aInstitution = null;
		}

		return $this;
	} // setInstitutionId()

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
			if (array_diff($this->modifiedColumns, array())) {
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
			$this->institution_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 2; // 2 = StudentPeer::NUM_COLUMNS - StudentPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Student object", $e);
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

		if ($this->aInstitution !== null && $this->institution_id !== $this->aInstitution->getInstitutionId()) {
			$this->aInstitution = null;
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
			$con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = StudentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aInstitution = null;
			$this->collStudentXProjects = null;
			$this->lastStudentXProjectCriteria = null;

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
			$con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			StudentPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(StudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			StudentPeer::addInstanceToPool($this);
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

			if ($this->aInstitution !== null) {
				if ($this->aInstitution->isModified() || $this->aInstitution->isNew()) {
					$affectedRows += $this->aInstitution->save($con);
				}
				$this->setInstitution($this->aInstitution);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = StudentPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += StudentPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collStudentXProjects !== null) {
				foreach ($this->collStudentXProjects as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
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

			if ($this->aInstitution !== null) {
				if (!$this->aInstitution->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aInstitution->getValidationFailures());
				}
			}


			if (($retval = StudentPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collStudentXProjects !== null) {
					foreach ($this->collStudentXProjects as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
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
		$criteria = new Criteria(StudentPeer::DATABASE_NAME);

		if ($this->isColumnModified(StudentPeer::USER_ID)) $criteria->add(StudentPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(StudentPeer::INSTITUTION_ID)) $criteria->add(StudentPeer::INSTITUTION_ID, $this->institution_id);

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
		$criteria = new Criteria(StudentPeer::DATABASE_NAME);

		$criteria->add(StudentPeer::USER_ID, $this->user_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getUserId();
	}

	/**
	 * Generic method to set the primary key (user_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setUserId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Student (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUserId($this->user_id);

		$copyObj->setInstitutionId($this->institution_id);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getStudentXProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addStudentXProject($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


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
	 * @return     Student Clone of current object.
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
	 * @return     StudentPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new StudentPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Institution object.
	 *
	 * @param      Institution $v
	 * @return     Student The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setInstitution(Institution $v = null)
	{
		if ($v === null) {
			$this->setInstitutionId(NULL);
		} else {
			$this->setInstitutionId($v->getInstitutionId());
		}

		$this->aInstitution = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Institution object, it will not be re-added.
		if ($v !== null) {
			$v->addStudent($this);
		}

		return $this;
	}


	/**
	 * Get the associated Institution object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Institution The associated Institution object.
	 * @throws     PropelException
	 */
	public function getInstitution(PropelPDO $con = null)
	{
		if ($this->aInstitution === null && ($this->institution_id !== null)) {
			$this->aInstitution = InstitutionPeer::retrieveByPK($this->institution_id, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aInstitution->addStudents($this);
			 */
		}
		return $this->aInstitution;
	}

	/**
	 * Clears out the collStudentXProjects collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addStudentXProjects()
	 */
	public function clearStudentXProjects()
	{
		$this->collStudentXProjects = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collStudentXProjects collection (array).
	 *
	 * By default this just sets the collStudentXProjects collection to an empty array (like clearcollStudentXProjects());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initStudentXProjects()
	{
		$this->collStudentXProjects = array();
	}

	/**
	 * Gets an array of StudentXProject objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Student has previously been saved, it will retrieve
	 * related StudentXProjects from storage. If this Student is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array StudentXProject[]
	 * @throws     PropelException
	 */
	public function getStudentXProjects($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(StudentPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collStudentXProjects === null) {
			if ($this->isNew()) {
			   $this->collStudentXProjects = array();
			} else {

				$criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);

				StudentXProjectPeer::addSelectColumns($criteria);
				$this->collStudentXProjects = StudentXProjectPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);

				StudentXProjectPeer::addSelectColumns($criteria);
				if (!isset($this->lastStudentXProjectCriteria) || !$this->lastStudentXProjectCriteria->equals($criteria)) {
					$this->collStudentXProjects = StudentXProjectPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastStudentXProjectCriteria = $criteria;
		return $this->collStudentXProjects;
	}

	/**
	 * Returns the number of related StudentXProject objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related StudentXProject objects.
	 * @throws     PropelException
	 */
	public function countStudentXProjects(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(StudentPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collStudentXProjects === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);

				$count = StudentXProjectPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);

				if (!isset($this->lastStudentXProjectCriteria) || !$this->lastStudentXProjectCriteria->equals($criteria)) {
					$count = StudentXProjectPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collStudentXProjects);
				}
			} else {
				$count = count($this->collStudentXProjects);
			}
		}
		$this->lastStudentXProjectCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a StudentXProject object to this object
	 * through the StudentXProject foreign key attribute.
	 *
	 * @param      StudentXProject $l StudentXProject
	 * @return     void
	 * @throws     PropelException
	 */
	public function addStudentXProject(StudentXProject $l)
	{
		if ($this->collStudentXProjects === null) {
			$this->initStudentXProjects();
		}
		if (!in_array($l, $this->collStudentXProjects, true)) { // only add it if the **same** object is not already associated
			array_push($this->collStudentXProjects, $l);
			$l->setStudent($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Student is new, it will return
	 * an empty collection; or if this Student has previously
	 * been saved, it will retrieve related StudentXProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Student.
	 */
	public function getStudentXProjectsJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(StudentPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collStudentXProjects === null) {
			if ($this->isNew()) {
				$this->collStudentXProjects = array();
			} else {

				$criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);

				$this->collStudentXProjects = StudentXProjectPeer::doSelectJoinProject($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(StudentXProjectPeer::USER_ID, $this->user_id);

			if (!isset($this->lastStudentXProjectCriteria) || !$this->lastStudentXProjectCriteria->equals($criteria)) {
				$this->collStudentXProjects = StudentXProjectPeer::doSelectJoinProject($criteria, $con, $join_behavior);
			}
		}
		$this->lastStudentXProjectCriteria = $criteria;

		return $this->collStudentXProjects;
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
			if ($this->collStudentXProjects) {
				foreach ((array) $this->collStudentXProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collStudentXProjects = null;
			$this->aInstitution = null;
	}

} // BaseStudent
