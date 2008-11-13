<?php

/**
 * Base class that represents a row from the 'channel' table.
 *
 * 
 *
 * @package    infinitymetrics.om
 */
abstract class BaseChannel extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ChannelPeer
	 */
	protected static $peer;

	/**
	 * The value for the channel_id field.
	 * @var        int
	 */
	protected $channel_id;

	/**
	 * The value for the project_jn_name field.
	 * @var        string
	 */
	protected $project_jn_name;

	/**
	 * The value for the channel_name field.
	 * @var        string
	 */
	protected $channel_name;

	/**
	 * The value for the title field.
	 * @var        string
	 */
	protected $title;

	/**
	 * The value for the category field.
	 * @var        string
	 */
	protected $category;

	/**
	 * @var        Project
	 */
	protected $aProject;

	/**
	 * @var        array Event[] Collection to store aggregation of Event objects.
	 */
	protected $collEvents;

	/**
	 * @var        Criteria The criteria used to select the current contents of collEvents.
	 */
	private $lastEventCriteria = null;

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
	 * Initializes internal state of BaseChannel object.
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
	 * Get the [channel_id] column value.
	 * 
	 * @return     int
	 */
	public function getChannelId()
	{
		return $this->channel_id;
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
	 * Get the [channel_name] column value.
	 * 
	 * @return     string
	 */
	public function getChannelName()
	{
		return $this->channel_name;
	}

	/**
	 * Get the [title] column value.
	 * 
	 * @return     string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Get the [category] column value.
	 * 
	 * @return     string
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * Set the value of [channel_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Channel The current object (for fluent API support)
	 */
	public function setChannelId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->channel_id !== $v) {
			$this->channel_id = $v;
			$this->modifiedColumns[] = ChannelPeer::CHANNEL_ID;
		}

		return $this;
	} // setChannelId()

	/**
	 * Set the value of [project_jn_name] column.
	 * 
	 * @param      string $v new value
	 * @return     Channel The current object (for fluent API support)
	 */
	public function setProjectJnName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->project_jn_name !== $v) {
			$this->project_jn_name = $v;
			$this->modifiedColumns[] = ChannelPeer::PROJECT_JN_NAME;
		}

		if ($this->aProject !== null && $this->aProject->getProjectJnName() !== $v) {
			$this->aProject = null;
		}

		return $this;
	} // setProjectJnName()

	/**
	 * Set the value of [channel_name] column.
	 * 
	 * @param      string $v new value
	 * @return     Channel The current object (for fluent API support)
	 */
	public function setChannelName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->channel_name !== $v) {
			$this->channel_name = $v;
			$this->modifiedColumns[] = ChannelPeer::CHANNEL_NAME;
		}

		return $this;
	} // setChannelName()

	/**
	 * Set the value of [title] column.
	 * 
	 * @param      string $v new value
	 * @return     Channel The current object (for fluent API support)
	 */
	public function setTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->title !== $v) {
			$this->title = $v;
			$this->modifiedColumns[] = ChannelPeer::TITLE;
		}

		return $this;
	} // setTitle()

	/**
	 * Set the value of [category] column.
	 * 
	 * @param      string $v new value
	 * @return     Channel The current object (for fluent API support)
	 */
	public function setCategory($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->category !== $v) {
			$this->category = $v;
			$this->modifiedColumns[] = ChannelPeer::CATEGORY;
		}

		return $this;
	} // setCategory()

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

			$this->channel_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->project_jn_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->channel_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->category = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = ChannelPeer::NUM_COLUMNS - ChannelPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Channel object", $e);
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
			$con = Propel::getConnection(ChannelPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = ChannelPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aProject = null;
			$this->collEvents = null;
			$this->lastEventCriteria = null;

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
			$con = Propel::getConnection(ChannelPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			ChannelPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(ChannelPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			ChannelPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = ChannelPeer::CHANNEL_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ChannelPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setChannelId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += ChannelPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collEvents !== null) {
				foreach ($this->collEvents as $referrerFK) {
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

			if ($this->aProject !== null) {
				if (!$this->aProject->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aProject->getValidationFailures());
				}
			}


			if (($retval = ChannelPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collEvents !== null) {
					foreach ($this->collEvents as $referrerFK) {
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
		$criteria = new Criteria(ChannelPeer::DATABASE_NAME);

		if ($this->isColumnModified(ChannelPeer::CHANNEL_ID)) $criteria->add(ChannelPeer::CHANNEL_ID, $this->channel_id);
		if ($this->isColumnModified(ChannelPeer::PROJECT_JN_NAME)) $criteria->add(ChannelPeer::PROJECT_JN_NAME, $this->project_jn_name);
		if ($this->isColumnModified(ChannelPeer::CHANNEL_NAME)) $criteria->add(ChannelPeer::CHANNEL_NAME, $this->channel_name);
		if ($this->isColumnModified(ChannelPeer::TITLE)) $criteria->add(ChannelPeer::TITLE, $this->title);
		if ($this->isColumnModified(ChannelPeer::CATEGORY)) $criteria->add(ChannelPeer::CATEGORY, $this->category);

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
		$criteria = new Criteria(ChannelPeer::DATABASE_NAME);

		$criteria->add(ChannelPeer::CHANNEL_ID, $this->channel_id);
		$criteria->add(ChannelPeer::PROJECT_JN_NAME, $this->project_jn_name);
		$criteria->add(ChannelPeer::CHANNEL_NAME, $this->channel_name);

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

		$pks[0] = $this->getChannelId();

		$pks[1] = $this->getProjectJnName();

		$pks[2] = $this->getChannelName();

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

		$this->setChannelId($keys[0]);

		$this->setProjectJnName($keys[1]);

		$this->setChannelName($keys[2]);

	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Channel (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setProjectJnName($this->project_jn_name);

		$copyObj->setChannelName($this->channel_name);

		$copyObj->setTitle($this->title);

		$copyObj->setCategory($this->category);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getEvents() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addEvent($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setChannelId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     Channel Clone of current object.
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
	 * @return     ChannelPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ChannelPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Project object.
	 *
	 * @param      Project $v
	 * @return     Channel The current object (for fluent API support)
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
			$v->addChannel($this);
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
			   $this->aProject->addChannels($this);
			 */
		}
		return $this->aProject;
	}

	/**
	 * Clears out the collEvents collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addEvents()
	 */
	public function clearEvents()
	{
		$this->collEvents = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collEvents collection (array).
	 *
	 * By default this just sets the collEvents collection to an empty array (like clearcollEvents());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initEvents()
	{
		$this->collEvents = array();
	}

	/**
	 * Gets an array of Event objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Channel has previously been saved, it will retrieve
	 * related Events from storage. If this Channel is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Event[]
	 * @throws     PropelException
	 */
	public function getEvents($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ChannelPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collEvents === null) {
			if ($this->isNew()) {
			   $this->collEvents = array();
			} else {

				$criteria->add(EventPeer::CHANNEL_ID, $this->channel_id);

				EventPeer::addSelectColumns($criteria);
				$this->collEvents = EventPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(EventPeer::CHANNEL_ID, $this->channel_id);

				EventPeer::addSelectColumns($criteria);
				if (!isset($this->lastEventCriteria) || !$this->lastEventCriteria->equals($criteria)) {
					$this->collEvents = EventPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastEventCriteria = $criteria;
		return $this->collEvents;
	}

	/**
	 * Returns the number of related Event objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Event objects.
	 * @throws     PropelException
	 */
	public function countEvents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ChannelPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collEvents === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(EventPeer::CHANNEL_ID, $this->channel_id);

				$count = EventPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(EventPeer::CHANNEL_ID, $this->channel_id);

				if (!isset($this->lastEventCriteria) || !$this->lastEventCriteria->equals($criteria)) {
					$count = EventPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collEvents);
				}
			} else {
				$count = count($this->collEvents);
			}
		}
		$this->lastEventCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a Event object to this object
	 * through the Event foreign key attribute.
	 *
	 * @param      Event $l Event
	 * @return     void
	 * @throws     PropelException
	 */
	public function addEvent(Event $l)
	{
		if ($this->collEvents === null) {
			$this->initEvents();
		}
		if (!in_array($l, $this->collEvents, true)) { // only add it if the **same** object is not already associated
			array_push($this->collEvents, $l);
			$l->setChannel($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Channel is new, it will return
	 * an empty collection; or if this Channel has previously
	 * been saved, it will retrieve related Events from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Channel.
	 */
	public function getEventsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ChannelPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collEvents === null) {
			if ($this->isNew()) {
				$this->collEvents = array();
			} else {

				$criteria->add(EventPeer::CHANNEL_ID, $this->channel_id);

				$this->collEvents = EventPeer::doSelectJoinUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(EventPeer::CHANNEL_ID, $this->channel_id);

			if (!isset($this->lastEventCriteria) || !$this->lastEventCriteria->equals($criteria)) {
				$this->collEvents = EventPeer::doSelectJoinUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastEventCriteria = $criteria;

		return $this->collEvents;
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
			if ($this->collEvents) {
				foreach ((array) $this->collEvents as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collEvents = null;
			$this->aProject = null;
	}

} // BaseChannel
