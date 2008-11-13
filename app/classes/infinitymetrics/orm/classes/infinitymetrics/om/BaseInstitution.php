<?php

/**
 * Base class that represents a row from the 'institution' table.
 *
 * 
 *
 * @package    infinitymetrics.om
 */
abstract class BaseInstitution extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        InstitutionPeer
	 */
	protected static $peer;

	/**
	 * The value for the institution_id field.
	 * @var        int
	 */
	protected $institution_id;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the abbreviation field.
	 * @var        string
	 */
	protected $abbreviation;

	/**
	 * The value for the city field.
	 * @var        string
	 */
	protected $city;

	/**
	 * The value for the state_province field.
	 * @var        string
	 */
	protected $state_province;

	/**
	 * The value for the country field.
	 * @var        string
	 */
	protected $country;

	/**
	 * @var        array Instructors[] Collection to store aggregation of Instructors objects.
	 */
	protected $collInstructorss;

	/**
	 * @var        Criteria The criteria used to select the current contents of collInstructorss.
	 */
	private $lastInstructorsCriteria = null;

	/**
	 * @var        array Student[] Collection to store aggregation of Student objects.
	 */
	protected $collStudents;

	/**
	 * @var        Criteria The criteria used to select the current contents of collStudents.
	 */
	private $lastStudentCriteria = null;

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
	 * Initializes internal state of BaseInstitution object.
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
	 * Get the [institution_id] column value.
	 * 
	 * @return     int
	 */
	public function getInstitutionId()
	{
		return $this->institution_id;
	}

	/**
	 * Get the [name] column value.
	 * 
	 * @return     string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the [abbreviation] column value.
	 * 
	 * @return     string
	 */
	public function getAbbreviation()
	{
		return $this->abbreviation;
	}

	/**
	 * Get the [city] column value.
	 * 
	 * @return     string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Get the [state_province] column value.
	 * 
	 * @return     string
	 */
	public function getStateProvince()
	{
		return $this->state_province;
	}

	/**
	 * Get the [country] column value.
	 * 
	 * @return     string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Set the value of [institution_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Institution The current object (for fluent API support)
	 */
	public function setInstitutionId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->institution_id !== $v) {
			$this->institution_id = $v;
			$this->modifiedColumns[] = InstitutionPeer::INSTITUTION_ID;
		}

		return $this;
	} // setInstitutionId()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     Institution The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = InstitutionPeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Set the value of [abbreviation] column.
	 * 
	 * @param      string $v new value
	 * @return     Institution The current object (for fluent API support)
	 */
	public function setAbbreviation($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->abbreviation !== $v) {
			$this->abbreviation = $v;
			$this->modifiedColumns[] = InstitutionPeer::ABBREVIATION;
		}

		return $this;
	} // setAbbreviation()

	/**
	 * Set the value of [city] column.
	 * 
	 * @param      string $v new value
	 * @return     Institution The current object (for fluent API support)
	 */
	public function setCity($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->city !== $v) {
			$this->city = $v;
			$this->modifiedColumns[] = InstitutionPeer::CITY;
		}

		return $this;
	} // setCity()

	/**
	 * Set the value of [state_province] column.
	 * 
	 * @param      string $v new value
	 * @return     Institution The current object (for fluent API support)
	 */
	public function setStateProvince($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->state_province !== $v) {
			$this->state_province = $v;
			$this->modifiedColumns[] = InstitutionPeer::STATE_PROVINCE;
		}

		return $this;
	} // setStateProvince()

	/**
	 * Set the value of [country] column.
	 * 
	 * @param      string $v new value
	 * @return     Institution The current object (for fluent API support)
	 */
	public function setCountry($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->country !== $v) {
			$this->country = $v;
			$this->modifiedColumns[] = InstitutionPeer::COUNTRY;
		}

		return $this;
	} // setCountry()

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

			$this->institution_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->abbreviation = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->city = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->state_province = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->country = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 6; // 6 = InstitutionPeer::NUM_COLUMNS - InstitutionPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Institution object", $e);
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
			$con = Propel::getConnection(InstitutionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = InstitutionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collInstructorss = null;
			$this->lastInstructorsCriteria = null;

			$this->collStudents = null;
			$this->lastStudentCriteria = null;

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
			$con = Propel::getConnection(InstitutionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			InstitutionPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(InstitutionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			InstitutionPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = InstitutionPeer::INSTITUTION_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = InstitutionPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setInstitutionId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += InstitutionPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collInstructorss !== null) {
				foreach ($this->collInstructorss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collStudents !== null) {
				foreach ($this->collStudents as $referrerFK) {
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


			if (($retval = InstitutionPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collInstructorss !== null) {
					foreach ($this->collInstructorss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collStudents !== null) {
					foreach ($this->collStudents as $referrerFK) {
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
		$criteria = new Criteria(InstitutionPeer::DATABASE_NAME);

		if ($this->isColumnModified(InstitutionPeer::INSTITUTION_ID)) $criteria->add(InstitutionPeer::INSTITUTION_ID, $this->institution_id);
		if ($this->isColumnModified(InstitutionPeer::NAME)) $criteria->add(InstitutionPeer::NAME, $this->name);
		if ($this->isColumnModified(InstitutionPeer::ABBREVIATION)) $criteria->add(InstitutionPeer::ABBREVIATION, $this->abbreviation);
		if ($this->isColumnModified(InstitutionPeer::CITY)) $criteria->add(InstitutionPeer::CITY, $this->city);
		if ($this->isColumnModified(InstitutionPeer::STATE_PROVINCE)) $criteria->add(InstitutionPeer::STATE_PROVINCE, $this->state_province);
		if ($this->isColumnModified(InstitutionPeer::COUNTRY)) $criteria->add(InstitutionPeer::COUNTRY, $this->country);

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
		$criteria = new Criteria(InstitutionPeer::DATABASE_NAME);

		$criteria->add(InstitutionPeer::INSTITUTION_ID, $this->institution_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getInstitutionId();
	}

	/**
	 * Generic method to set the primary key (institution_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setInstitutionId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Institution (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setName($this->name);

		$copyObj->setAbbreviation($this->abbreviation);

		$copyObj->setCity($this->city);

		$copyObj->setStateProvince($this->state_province);

		$copyObj->setCountry($this->country);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getInstructorss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addInstructors($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getStudents() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addStudent($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setInstitutionId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     Institution Clone of current object.
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
	 * @return     InstitutionPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new InstitutionPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collInstructorss collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addInstructorss()
	 */
	public function clearInstructorss()
	{
		$this->collInstructorss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collInstructorss collection (array).
	 *
	 * By default this just sets the collInstructorss collection to an empty array (like clearcollInstructorss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initInstructorss()
	{
		$this->collInstructorss = array();
	}

	/**
	 * Gets an array of Instructors objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Institution has previously been saved, it will retrieve
	 * related Instructorss from storage. If this Institution is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Instructors[]
	 * @throws     PropelException
	 */
	public function getInstructorss($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InstitutionPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collInstructorss === null) {
			if ($this->isNew()) {
			   $this->collInstructorss = array();
			} else {

				$criteria->add(InstructorsPeer::INSTITUTION_ID, $this->institution_id);

				InstructorsPeer::addSelectColumns($criteria);
				$this->collInstructorss = InstructorsPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(InstructorsPeer::INSTITUTION_ID, $this->institution_id);

				InstructorsPeer::addSelectColumns($criteria);
				if (!isset($this->lastInstructorsCriteria) || !$this->lastInstructorsCriteria->equals($criteria)) {
					$this->collInstructorss = InstructorsPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastInstructorsCriteria = $criteria;
		return $this->collInstructorss;
	}

	/**
	 * Returns the number of related Instructors objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Instructors objects.
	 * @throws     PropelException
	 */
	public function countInstructorss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InstitutionPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collInstructorss === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(InstructorsPeer::INSTITUTION_ID, $this->institution_id);

				$count = InstructorsPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(InstructorsPeer::INSTITUTION_ID, $this->institution_id);

				if (!isset($this->lastInstructorsCriteria) || !$this->lastInstructorsCriteria->equals($criteria)) {
					$count = InstructorsPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collInstructorss);
				}
			} else {
				$count = count($this->collInstructorss);
			}
		}
		$this->lastInstructorsCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a Instructors object to this object
	 * through the Instructors foreign key attribute.
	 *
	 * @param      Instructors $l Instructors
	 * @return     void
	 * @throws     PropelException
	 */
	public function addInstructors(Instructors $l)
	{
		if ($this->collInstructorss === null) {
			$this->initInstructorss();
		}
		if (!in_array($l, $this->collInstructorss, true)) { // only add it if the **same** object is not already associated
			array_push($this->collInstructorss, $l);
			$l->setInstitution($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Institution is new, it will return
	 * an empty collection; or if this Institution has previously
	 * been saved, it will retrieve related Instructorss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Institution.
	 */
	public function getInstructorssJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InstitutionPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collInstructorss === null) {
			if ($this->isNew()) {
				$this->collInstructorss = array();
			} else {

				$criteria->add(InstructorsPeer::INSTITUTION_ID, $this->institution_id);

				$this->collInstructorss = InstructorsPeer::doSelectJoinUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(InstructorsPeer::INSTITUTION_ID, $this->institution_id);

			if (!isset($this->lastInstructorsCriteria) || !$this->lastInstructorsCriteria->equals($criteria)) {
				$this->collInstructorss = InstructorsPeer::doSelectJoinUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastInstructorsCriteria = $criteria;

		return $this->collInstructorss;
	}

	/**
	 * Clears out the collStudents collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addStudents()
	 */
	public function clearStudents()
	{
		$this->collStudents = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collStudents collection (array).
	 *
	 * By default this just sets the collStudents collection to an empty array (like clearcollStudents());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initStudents()
	{
		$this->collStudents = array();
	}

	/**
	 * Gets an array of Student objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Institution has previously been saved, it will retrieve
	 * related Students from storage. If this Institution is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Student[]
	 * @throws     PropelException
	 */
	public function getStudents($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InstitutionPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collStudents === null) {
			if ($this->isNew()) {
			   $this->collStudents = array();
			} else {

				$criteria->add(StudentPeer::INSTITUTION_ID, $this->institution_id);

				StudentPeer::addSelectColumns($criteria);
				$this->collStudents = StudentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(StudentPeer::INSTITUTION_ID, $this->institution_id);

				StudentPeer::addSelectColumns($criteria);
				if (!isset($this->lastStudentCriteria) || !$this->lastStudentCriteria->equals($criteria)) {
					$this->collStudents = StudentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastStudentCriteria = $criteria;
		return $this->collStudents;
	}

	/**
	 * Returns the number of related Student objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Student objects.
	 * @throws     PropelException
	 */
	public function countStudents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InstitutionPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collStudents === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(StudentPeer::INSTITUTION_ID, $this->institution_id);

				$count = StudentPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(StudentPeer::INSTITUTION_ID, $this->institution_id);

				if (!isset($this->lastStudentCriteria) || !$this->lastStudentCriteria->equals($criteria)) {
					$count = StudentPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collStudents);
				}
			} else {
				$count = count($this->collStudents);
			}
		}
		$this->lastStudentCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a Student object to this object
	 * through the Student foreign key attribute.
	 *
	 * @param      Student $l Student
	 * @return     void
	 * @throws     PropelException
	 */
	public function addStudent(Student $l)
	{
		if ($this->collStudents === null) {
			$this->initStudents();
		}
		if (!in_array($l, $this->collStudents, true)) { // only add it if the **same** object is not already associated
			array_push($this->collStudents, $l);
			$l->setInstitution($this);
		}
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
			if ($this->collInstructorss) {
				foreach ((array) $this->collInstructorss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collStudents) {
				foreach ((array) $this->collStudents as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collInstructorss = null;
		$this->collStudents = null;
	}

} // BaseInstitution
