<?php

/**
 * Base class that represents a row from the 'project' table.
 *
 * 
 *
 * @package    infinitymetrics.om
 */
abstract class BaseProject extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ProjectPeer
	 */
	protected static $peer;

	/**
	 * The value for the project_jn_name field.
	 * @var        string
	 */
	protected $project_jn_name;

	/**
	 * The value for the summary field.
	 * @var        string
	 */
	protected $summary;

	/**
	 * @var        array Channel[] Collection to store aggregation of Channel objects.
	 */
	protected $collChannels;

	/**
	 * @var        Criteria The criteria used to select the current contents of collChannels.
	 */
	private $lastChannelCriteria = null;

	/**
	 * @var        array Dispute[] Collection to store aggregation of Dispute objects.
	 */
	protected $collDisputes;

	/**
	 * @var        Criteria The criteria used to select the current contents of collDisputes.
	 */
	private $lastDisputeCriteria = null;

	/**
	 * @var        array StudentXProject[] Collection to store aggregation of StudentXProject objects.
	 */
	protected $collStudentXProjects;

	/**
	 * @var        Criteria The criteria used to select the current contents of collStudentXProjects.
	 */
	private $lastStudentXProjectCriteria = null;

	/**
	 * @var        array WorkpaceXProject[] Collection to store aggregation of WorkpaceXProject objects.
	 */
	protected $collWorkpaceXProjects;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkpaceXProjects.
	 */
	private $lastWorkpaceXProjectCriteria = null;

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
	 * Initializes internal state of BaseProject object.
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
	 * Get the [project_jn_name] column value.
	 * 
	 * @return     string
	 */
	public function getProjectJnName()
	{
		return $this->project_jn_name;
	}

	/**
	 * Get the [summary] column value.
	 * 
	 * @return     string
	 */
	public function getSummary()
	{
		return $this->summary;
	}

	/**
	 * Set the value of [project_jn_name] column.
	 * 
	 * @param      string $v new value
	 * @return     Project The current object (for fluent API support)
	 */
	public function setProjectJnName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->project_jn_name !== $v) {
			$this->project_jn_name = $v;
			$this->modifiedColumns[] = ProjectPeer::PROJECT_JN_NAME;
		}

		return $this;
	} // setProjectJnName()

	/**
	 * Set the value of [summary] column.
	 * 
	 * @param      string $v new value
	 * @return     Project The current object (for fluent API support)
	 */
	public function setSummary($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->summary !== $v) {
			$this->summary = $v;
			$this->modifiedColumns[] = ProjectPeer::SUMMARY;
		}

		return $this;
	} // setSummary()

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

			$this->project_jn_name = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
			$this->summary = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 2; // 2 = ProjectPeer::NUM_COLUMNS - ProjectPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Project object", $e);
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
			$con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = ProjectPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collChannels = null;
			$this->lastChannelCriteria = null;

			$this->collDisputes = null;
			$this->lastDisputeCriteria = null;

			$this->collStudentXProjects = null;
			$this->lastStudentXProjectCriteria = null;

			$this->collWorkpaceXProjects = null;
			$this->lastWorkpaceXProjectCriteria = null;

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
			$con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			ProjectPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			ProjectPeer::addInstanceToPool($this);
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


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ProjectPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += ProjectPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collChannels !== null) {
				foreach ($this->collChannels as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collDisputes !== null) {
				foreach ($this->collDisputes as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collStudentXProjects !== null) {
				foreach ($this->collStudentXProjects as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkpaceXProjects !== null) {
				foreach ($this->collWorkpaceXProjects as $referrerFK) {
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


			if (($retval = ProjectPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collChannels !== null) {
					foreach ($this->collChannels as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collDisputes !== null) {
					foreach ($this->collDisputes as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collStudentXProjects !== null) {
					foreach ($this->collStudentXProjects as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkpaceXProjects !== null) {
					foreach ($this->collWorkpaceXProjects as $referrerFK) {
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
		$criteria = new Criteria(ProjectPeer::DATABASE_NAME);

		if ($this->isColumnModified(ProjectPeer::PROJECT_JN_NAME)) $criteria->add(ProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);
		if ($this->isColumnModified(ProjectPeer::SUMMARY)) $criteria->add(ProjectPeer::SUMMARY, $this->summary);

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
		$criteria = new Criteria(ProjectPeer::DATABASE_NAME);

		$criteria->add(ProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     string
	 */
	public function getPrimaryKey()
	{
		return $this->getProjectJnName();
	}

	/**
	 * Generic method to set the primary key (project_jn_name column).
	 *
	 * @param      string $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setProjectJnName($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Project (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setProjectJnName($this->project_jn_name);

		$copyObj->setSummary($this->summary);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getChannels() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addChannel($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getDisputes() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addDispute($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getStudentXProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addStudentXProject($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkpaceXProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkpaceXProject($relObj->copy($deepCopy));
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
	 * @return     Project Clone of current object.
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
	 * @return     ProjectPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ProjectPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collChannels collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addChannels()
	 */
	public function clearChannels()
	{
		$this->collChannels = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collChannels collection (array).
	 *
	 * By default this just sets the collChannels collection to an empty array (like clearcollChannels());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initChannels()
	{
		$this->collChannels = array();
	}

	/**
	 * Gets an array of Channel objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Project has previously been saved, it will retrieve
	 * related Channels from storage. If this Project is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Channel[]
	 * @throws     PropelException
	 */
	public function getChannels($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collChannels === null) {
			if ($this->isNew()) {
			   $this->collChannels = array();
			} else {

				$criteria->add(ChannelPeer::PROJECT_JN_NAME, $this->project_jn_name);

				ChannelPeer::addSelectColumns($criteria);
				$this->collChannels = ChannelPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(ChannelPeer::PROJECT_JN_NAME, $this->project_jn_name);

				ChannelPeer::addSelectColumns($criteria);
				if (!isset($this->lastChannelCriteria) || !$this->lastChannelCriteria->equals($criteria)) {
					$this->collChannels = ChannelPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastChannelCriteria = $criteria;
		return $this->collChannels;
	}

	/**
	 * Returns the number of related Channel objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Channel objects.
	 * @throws     PropelException
	 */
	public function countChannels(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collChannels === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(ChannelPeer::PROJECT_JN_NAME, $this->project_jn_name);

				$count = ChannelPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(ChannelPeer::PROJECT_JN_NAME, $this->project_jn_name);

				if (!isset($this->lastChannelCriteria) || !$this->lastChannelCriteria->equals($criteria)) {
					$count = ChannelPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collChannels);
				}
			} else {
				$count = count($this->collChannels);
			}
		}
		$this->lastChannelCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a Channel object to this object
	 * through the Channel foreign key attribute.
	 *
	 * @param      Channel $l Channel
	 * @return     void
	 * @throws     PropelException
	 */
	public function addChannel(Channel $l)
	{
		if ($this->collChannels === null) {
			$this->initChannels();
		}
		if (!in_array($l, $this->collChannels, true)) { // only add it if the **same** object is not already associated
			array_push($this->collChannels, $l);
			$l->setProject($this);
		}
	}

	/**
	 * Clears out the collDisputes collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addDisputes()
	 */
	public function clearDisputes()
	{
		$this->collDisputes = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collDisputes collection (array).
	 *
	 * By default this just sets the collDisputes collection to an empty array (like clearcollDisputes());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initDisputes()
	{
		$this->collDisputes = array();
	}

	/**
	 * Gets an array of Dispute objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Project has previously been saved, it will retrieve
	 * related Disputes from storage. If this Project is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Dispute[]
	 * @throws     PropelException
	 */
	public function getDisputes($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collDisputes === null) {
			if ($this->isNew()) {
			   $this->collDisputes = array();
			} else {

				$criteria->add(DisputePeer::PROJECT_JN_NAME, $this->project_jn_name);

				DisputePeer::addSelectColumns($criteria);
				$this->collDisputes = DisputePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(DisputePeer::PROJECT_JN_NAME, $this->project_jn_name);

				DisputePeer::addSelectColumns($criteria);
				if (!isset($this->lastDisputeCriteria) || !$this->lastDisputeCriteria->equals($criteria)) {
					$this->collDisputes = DisputePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastDisputeCriteria = $criteria;
		return $this->collDisputes;
	}

	/**
	 * Returns the number of related Dispute objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Dispute objects.
	 * @throws     PropelException
	 */
	public function countDisputes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collDisputes === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(DisputePeer::PROJECT_JN_NAME, $this->project_jn_name);

				$count = DisputePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(DisputePeer::PROJECT_JN_NAME, $this->project_jn_name);

				if (!isset($this->lastDisputeCriteria) || !$this->lastDisputeCriteria->equals($criteria)) {
					$count = DisputePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collDisputes);
				}
			} else {
				$count = count($this->collDisputes);
			}
		}
		$this->lastDisputeCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a Dispute object to this object
	 * through the Dispute foreign key attribute.
	 *
	 * @param      Dispute $l Dispute
	 * @return     void
	 * @throws     PropelException
	 */
	public function addDispute(Dispute $l)
	{
		if ($this->collDisputes === null) {
			$this->initDisputes();
		}
		if (!in_array($l, $this->collDisputes, true)) { // only add it if the **same** object is not already associated
			array_push($this->collDisputes, $l);
			$l->setProject($this);
		}
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
	 * Otherwise if this Project has previously been saved, it will retrieve
	 * related StudentXProjects from storage. If this Project is new, it will return
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
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collStudentXProjects === null) {
			if ($this->isNew()) {
			   $this->collStudentXProjects = array();
			} else {

				$criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				StudentXProjectPeer::addSelectColumns($criteria);
				$this->collStudentXProjects = StudentXProjectPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

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
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
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

				$criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				$count = StudentXProjectPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

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
			$l->setProject($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related StudentXProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 */
	public function getStudentXProjectsJoinStudent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collStudentXProjects === null) {
			if ($this->isNew()) {
				$this->collStudentXProjects = array();
			} else {

				$criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				$this->collStudentXProjects = StudentXProjectPeer::doSelectJoinStudent($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(StudentXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

			if (!isset($this->lastStudentXProjectCriteria) || !$this->lastStudentXProjectCriteria->equals($criteria)) {
				$this->collStudentXProjects = StudentXProjectPeer::doSelectJoinStudent($criteria, $con, $join_behavior);
			}
		}
		$this->lastStudentXProjectCriteria = $criteria;

		return $this->collStudentXProjects;
	}

	/**
	 * Clears out the collWorkpaceXProjects collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkpaceXProjects()
	 */
	public function clearWorkpaceXProjects()
	{
		$this->collWorkpaceXProjects = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkpaceXProjects collection (array).
	 *
	 * By default this just sets the collWorkpaceXProjects collection to an empty array (like clearcollWorkpaceXProjects());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkpaceXProjects()
	{
		$this->collWorkpaceXProjects = array();
	}

	/**
	 * Gets an array of WorkpaceXProject objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Project has previously been saved, it will retrieve
	 * related WorkpaceXProjects from storage. If this Project is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkpaceXProject[]
	 * @throws     PropelException
	 */
	public function getWorkpaceXProjects($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkpaceXProjects === null) {
			if ($this->isNew()) {
			   $this->collWorkpaceXProjects = array();
			} else {

				$criteria->add(WorkpaceXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				WorkpaceXProjectPeer::addSelectColumns($criteria);
				$this->collWorkpaceXProjects = WorkpaceXProjectPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkpaceXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				WorkpaceXProjectPeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkpaceXProjectCriteria) || !$this->lastWorkpaceXProjectCriteria->equals($criteria)) {
					$this->collWorkpaceXProjects = WorkpaceXProjectPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkpaceXProjectCriteria = $criteria;
		return $this->collWorkpaceXProjects;
	}

	/**
	 * Returns the number of related WorkpaceXProject objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkpaceXProject objects.
	 * @throws     PropelException
	 */
	public function countWorkpaceXProjects(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkpaceXProjects === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkpaceXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				$count = WorkpaceXProjectPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkpaceXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				if (!isset($this->lastWorkpaceXProjectCriteria) || !$this->lastWorkpaceXProjectCriteria->equals($criteria)) {
					$count = WorkpaceXProjectPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkpaceXProjects);
				}
			} else {
				$count = count($this->collWorkpaceXProjects);
			}
		}
		$this->lastWorkpaceXProjectCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a WorkpaceXProject object to this object
	 * through the WorkpaceXProject foreign key attribute.
	 *
	 * @param      WorkpaceXProject $l WorkpaceXProject
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkpaceXProject(WorkpaceXProject $l)
	{
		if ($this->collWorkpaceXProjects === null) {
			$this->initWorkpaceXProjects();
		}
		if (!in_array($l, $this->collWorkpaceXProjects, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkpaceXProjects, $l);
			$l->setProject($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related WorkpaceXProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 */
	public function getWorkpaceXProjectsJoinWorkspace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkpaceXProjects === null) {
			if ($this->isNew()) {
				$this->collWorkpaceXProjects = array();
			} else {

				$criteria->add(WorkpaceXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

				$this->collWorkpaceXProjects = WorkpaceXProjectPeer::doSelectJoinWorkspace($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkpaceXProjectPeer::PROJECT_JN_NAME, $this->project_jn_name);

			if (!isset($this->lastWorkpaceXProjectCriteria) || !$this->lastWorkpaceXProjectCriteria->equals($criteria)) {
				$this->collWorkpaceXProjects = WorkpaceXProjectPeer::doSelectJoinWorkspace($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkpaceXProjectCriteria = $criteria;

		return $this->collWorkpaceXProjects;
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
			if ($this->collChannels) {
				foreach ((array) $this->collChannels as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collDisputes) {
				foreach ((array) $this->collDisputes as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collStudentXProjects) {
				foreach ((array) $this->collStudentXProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkpaceXProjects) {
				foreach ((array) $this->collWorkpaceXProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collChannels = null;
		$this->collDisputes = null;
		$this->collStudentXProjects = null;
		$this->collWorkpaceXProjects = null;
	}

} // BaseProject
