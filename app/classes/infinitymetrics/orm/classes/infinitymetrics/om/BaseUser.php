<?php

/**
 * Base class that represents a row from the 'user' table.
 *
 * 
 *
 * @package    infinitymetrics.om
 */
abstract class BaseUser extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UserPeer
	 */
	protected static $peer;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the jn_username field.
	 * @var        string
	 */
	protected $jn_username;

	/**
	 * The value for the jn_password field.
	 * @var        string
	 */
	protected $jn_password;

	/**
	 * The value for the first_name field.
	 * @var        string
	 */
	protected $first_name;

	/**
	 * The value for the last_name field.
	 * @var        string
	 */
	protected $last_name;

	/**
	 * The value for the email field.
	 * @var        string
	 */
	protected $email;

	/**
	 * The value for the type field.
	 * @var        string
	 */
	protected $type;

	/**
	 * @var        array Event[] Collection to store aggregation of Event objects.
	 */
	protected $collEvents;

	/**
	 * @var        Criteria The criteria used to select the current contents of collEvents.
	 */
	private $lastEventCriteria = null;

	/**
	 * @var        Instructors one-to-one related Instructors object
	 */
	protected $singleInstructors;

	/**
	 * @var        array Workspace[] Collection to store aggregation of Workspace objects.
	 */
	protected $collWorkspaces;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkspaces.
	 */
	private $lastWorkspaceCriteria = null;

	/**
	 * @var        array WorkspaceShare[] Collection to store aggregation of WorkspaceShare objects.
	 */
	protected $collWorkspaceShares;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkspaceShares.
	 */
	private $lastWorkspaceShareCriteria = null;

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
	 * Initializes internal state of BaseUser object.
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
	 * Get the [jn_username] column value.
	 * 
	 * @return     string
	 */
	public function getJnUsername()
	{
		return $this->jn_username;
	}

	/**
	 * Get the [jn_password] column value.
	 * 
	 * @return     string
	 */
	public function getJnPassword()
	{
		return $this->jn_password;
	}

	/**
	 * Get the [first_name] column value.
	 * 
	 * @return     string
	 */
	public function getFirstName()
	{
		return $this->first_name;
	}

	/**
	 * Get the [last_name] column value.
	 * 
	 * @return     string
	 */
	public function getLastName()
	{
		return $this->last_name;
	}

	/**
	 * Get the [email] column value.
	 * 
	 * @return     string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Get the [type] column value.
	 * 
	 * @return     string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = UserPeer::USER_ID;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [jn_username] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setJnUsername($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->jn_username !== $v) {
			$this->jn_username = $v;
			$this->modifiedColumns[] = UserPeer::JN_USERNAME;
		}

		return $this;
	} // setJnUsername()

	/**
	 * Set the value of [jn_password] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setJnPassword($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->jn_password !== $v) {
			$this->jn_password = $v;
			$this->modifiedColumns[] = UserPeer::JN_PASSWORD;
		}

		return $this;
	} // setJnPassword()

	/**
	 * Set the value of [first_name] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setFirstName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->first_name !== $v) {
			$this->first_name = $v;
			$this->modifiedColumns[] = UserPeer::FIRST_NAME;
		}

		return $this;
	} // setFirstName()

	/**
	 * Set the value of [last_name] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setLastName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->last_name !== $v) {
			$this->last_name = $v;
			$this->modifiedColumns[] = UserPeer::LAST_NAME;
		}

		return $this;
	} // setLastName()

	/**
	 * Set the value of [email] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setEmail($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->email !== $v) {
			$this->email = $v;
			$this->modifiedColumns[] = UserPeer::EMAIL;
		}

		return $this;
	} // setEmail()

	/**
	 * Set the value of [type] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = UserPeer::TYPE;
		}

		return $this;
	} // setType()

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
			$this->jn_username = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->jn_password = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->first_name = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->last_name = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->email = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->type = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 7; // 7 = UserPeer::NUM_COLUMNS - UserPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating User object", $e);
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
			$con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = UserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collEvents = null;
			$this->lastEventCriteria = null;

			$this->singleInstructors = null;

			$this->collWorkspaces = null;
			$this->lastWorkspaceCriteria = null;

			$this->collWorkspaceShares = null;
			$this->lastWorkspaceShareCriteria = null;

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
			$con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			UserPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			UserPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = UserPeer::USER_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UserPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setUserId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UserPeer::doUpdate($this, $con);
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

			if ($this->singleInstructors !== null) {
				if (!$this->singleInstructors->isDeleted()) {
						$affectedRows += $this->singleInstructors->save($con);
				}
			}

			if ($this->collWorkspaces !== null) {
				foreach ($this->collWorkspaces as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkspaceShares !== null) {
				foreach ($this->collWorkspaceShares as $referrerFK) {
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


			if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collEvents !== null) {
					foreach ($this->collEvents as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->singleInstructors !== null) {
					if (!$this->singleInstructors->validate($columns)) {
						$failureMap = array_merge($failureMap, $this->singleInstructors->getValidationFailures());
					}
				}

				if ($this->collWorkspaces !== null) {
					foreach ($this->collWorkspaces as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkspaceShares !== null) {
					foreach ($this->collWorkspaceShares as $referrerFK) {
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
		$criteria = new Criteria(UserPeer::DATABASE_NAME);

		if ($this->isColumnModified(UserPeer::USER_ID)) $criteria->add(UserPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(UserPeer::JN_USERNAME)) $criteria->add(UserPeer::JN_USERNAME, $this->jn_username);
		if ($this->isColumnModified(UserPeer::JN_PASSWORD)) $criteria->add(UserPeer::JN_PASSWORD, $this->jn_password);
		if ($this->isColumnModified(UserPeer::FIRST_NAME)) $criteria->add(UserPeer::FIRST_NAME, $this->first_name);
		if ($this->isColumnModified(UserPeer::LAST_NAME)) $criteria->add(UserPeer::LAST_NAME, $this->last_name);
		if ($this->isColumnModified(UserPeer::EMAIL)) $criteria->add(UserPeer::EMAIL, $this->email);
		if ($this->isColumnModified(UserPeer::TYPE)) $criteria->add(UserPeer::TYPE, $this->type);

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
		$criteria = new Criteria(UserPeer::DATABASE_NAME);

		$criteria->add(UserPeer::USER_ID, $this->user_id);

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
	 * @param      object $copyObj An object of User (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setJnUsername($this->jn_username);

		$copyObj->setJnPassword($this->jn_password);

		$copyObj->setFirstName($this->first_name);

		$copyObj->setLastName($this->last_name);

		$copyObj->setEmail($this->email);

		$copyObj->setType($this->type);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getEvents() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addEvent($relObj->copy($deepCopy));
				}
			}

			$relObj = $this->getInstructors();
			if ($relObj) {
				$copyObj->setInstructors($relObj->copy($deepCopy));
			}

			foreach ($this->getWorkspaces() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkspace($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkspaceShares() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkspaceShare($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setUserId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     User Clone of current object.
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
	 * @return     UserPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UserPeer();
		}
		return self::$peer;
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
	 * Otherwise if this User has previously been saved, it will retrieve
	 * related Events from storage. If this User is new, it will return
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
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collEvents === null) {
			if ($this->isNew()) {
			   $this->collEvents = array();
			} else {

				$criteria->add(EventPeer::JN_USERNAME, $this->jn_username);

				EventPeer::addSelectColumns($criteria);
				$this->collEvents = EventPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(EventPeer::JN_USERNAME, $this->jn_username);

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
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
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

				$criteria->add(EventPeer::JN_USERNAME, $this->jn_username);

				$count = EventPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(EventPeer::JN_USERNAME, $this->jn_username);

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
			$l->setUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related Events from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getEventsJoinChannel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collEvents === null) {
			if ($this->isNew()) {
				$this->collEvents = array();
			} else {

				$criteria->add(EventPeer::JN_USERNAME, $this->jn_username);

				$this->collEvents = EventPeer::doSelectJoinChannel($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(EventPeer::JN_USERNAME, $this->jn_username);

			if (!isset($this->lastEventCriteria) || !$this->lastEventCriteria->equals($criteria)) {
				$this->collEvents = EventPeer::doSelectJoinChannel($criteria, $con, $join_behavior);
			}
		}
		$this->lastEventCriteria = $criteria;

		return $this->collEvents;
	}

	/**
	 * Gets a single Instructors object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con
	 * @return     Instructors
	 * @throws     PropelException
	 */
	public function getInstructors(PropelPDO $con = null)
	{

		if ($this->singleInstructors === null && !$this->isNew()) {
			$this->singleInstructors = InstructorsPeer::retrieveByPK($this->user_id, $con);
		}

		return $this->singleInstructors;
	}

	/**
	 * Sets a single Instructors object as related to this object by a one-to-one relationship.
	 *
	 * @param      Instructors $l Instructors
	 * @return     User The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setInstructors(Instructors $v)
	{
		$this->singleInstructors = $v;

		// Make sure that that the passed-in Instructors isn't already associated with this object
		if ($v->getUser() === null) {
			$v->setUser($this);
		}

		return $this;
	}

	/**
	 * Clears out the collWorkspaces collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkspaces()
	 */
	public function clearWorkspaces()
	{
		$this->collWorkspaces = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkspaces collection (array).
	 *
	 * By default this just sets the collWorkspaces collection to an empty array (like clearcollWorkspaces());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkspaces()
	{
		$this->collWorkspaces = array();
	}

	/**
	 * Gets an array of Workspace objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this User has previously been saved, it will retrieve
	 * related Workspaces from storage. If this User is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Workspace[]
	 * @throws     PropelException
	 */
	public function getWorkspaces($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkspaces === null) {
			if ($this->isNew()) {
			   $this->collWorkspaces = array();
			} else {

				$criteria->add(WorkspacePeer::USER_ID, $this->user_id);

				WorkspacePeer::addSelectColumns($criteria);
				$this->collWorkspaces = WorkspacePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkspacePeer::USER_ID, $this->user_id);

				WorkspacePeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkspaceCriteria) || !$this->lastWorkspaceCriteria->equals($criteria)) {
					$this->collWorkspaces = WorkspacePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkspaceCriteria = $criteria;
		return $this->collWorkspaces;
	}

	/**
	 * Returns the number of related Workspace objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Workspace objects.
	 * @throws     PropelException
	 */
	public function countWorkspaces(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkspaces === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkspacePeer::USER_ID, $this->user_id);

				$count = WorkspacePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkspacePeer::USER_ID, $this->user_id);

				if (!isset($this->lastWorkspaceCriteria) || !$this->lastWorkspaceCriteria->equals($criteria)) {
					$count = WorkspacePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkspaces);
				}
			} else {
				$count = count($this->collWorkspaces);
			}
		}
		$this->lastWorkspaceCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a Workspace object to this object
	 * through the Workspace foreign key attribute.
	 *
	 * @param      Workspace $l Workspace
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkspace(Workspace $l)
	{
		if ($this->collWorkspaces === null) {
			$this->initWorkspaces();
		}
		if (!in_array($l, $this->collWorkspaces, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkspaces, $l);
			$l->setUser($this);
		}
	}

	/**
	 * Clears out the collWorkspaceShares collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkspaceShares()
	 */
	public function clearWorkspaceShares()
	{
		$this->collWorkspaceShares = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkspaceShares collection (array).
	 *
	 * By default this just sets the collWorkspaceShares collection to an empty array (like clearcollWorkspaceShares());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkspaceShares()
	{
		$this->collWorkspaceShares = array();
	}

	/**
	 * Gets an array of WorkspaceShare objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this User has previously been saved, it will retrieve
	 * related WorkspaceShares from storage. If this User is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkspaceShare[]
	 * @throws     PropelException
	 */
	public function getWorkspaceShares($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkspaceShares === null) {
			if ($this->isNew()) {
			   $this->collWorkspaceShares = array();
			} else {

				$criteria->add(WorkspaceSharePeer::USER_ID, $this->user_id);

				WorkspaceSharePeer::addSelectColumns($criteria);
				$this->collWorkspaceShares = WorkspaceSharePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkspaceSharePeer::USER_ID, $this->user_id);

				WorkspaceSharePeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkspaceShareCriteria) || !$this->lastWorkspaceShareCriteria->equals($criteria)) {
					$this->collWorkspaceShares = WorkspaceSharePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkspaceShareCriteria = $criteria;
		return $this->collWorkspaceShares;
	}

	/**
	 * Returns the number of related WorkspaceShare objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkspaceShare objects.
	 * @throws     PropelException
	 */
	public function countWorkspaceShares(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkspaceShares === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkspaceSharePeer::USER_ID, $this->user_id);

				$count = WorkspaceSharePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkspaceSharePeer::USER_ID, $this->user_id);

				if (!isset($this->lastWorkspaceShareCriteria) || !$this->lastWorkspaceShareCriteria->equals($criteria)) {
					$count = WorkspaceSharePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkspaceShares);
				}
			} else {
				$count = count($this->collWorkspaceShares);
			}
		}
		$this->lastWorkspaceShareCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a WorkspaceShare object to this object
	 * through the WorkspaceShare foreign key attribute.
	 *
	 * @param      WorkspaceShare $l WorkspaceShare
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkspaceShare(WorkspaceShare $l)
	{
		if ($this->collWorkspaceShares === null) {
			$this->initWorkspaceShares();
		}
		if (!in_array($l, $this->collWorkspaceShares, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkspaceShares, $l);
			$l->setUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related WorkspaceShares from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 */
	public function getWorkspaceSharesJoinWorkspace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(UserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkspaceShares === null) {
			if ($this->isNew()) {
				$this->collWorkspaceShares = array();
			} else {

				$criteria->add(WorkspaceSharePeer::USER_ID, $this->user_id);

				$this->collWorkspaceShares = WorkspaceSharePeer::doSelectJoinWorkspace($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkspaceSharePeer::USER_ID, $this->user_id);

			if (!isset($this->lastWorkspaceShareCriteria) || !$this->lastWorkspaceShareCriteria->equals($criteria)) {
				$this->collWorkspaceShares = WorkspaceSharePeer::doSelectJoinWorkspace($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkspaceShareCriteria = $criteria;

		return $this->collWorkspaceShares;
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
			if ($this->singleInstructors) {
				$this->singleInstructors->clearAllReferences($deep);
			}
			if ($this->collWorkspaces) {
				foreach ((array) $this->collWorkspaces as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkspaceShares) {
				foreach ((array) $this->collWorkspaceShares as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collEvents = null;
		$this->singleInstructors = null;
		$this->collWorkspaces = null;
		$this->collWorkspaceShares = null;
	}

} // BaseUser
