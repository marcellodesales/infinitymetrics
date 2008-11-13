<?php

/**
 * Base class that represents a row from the 'workspace' table.
 *
 * 
 *
 * @package    infinitymetrics.om
 */
abstract class BaseWorkspace extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WorkspacePeer
	 */
	protected static $peer;

	/**
	 * The value for the workspace_id field.
	 * @var        int
	 */
	protected $workspace_id;

	/**
	 * The value for the state field.
	 * Note: this column has a database default value of: 'NEW'
	 * @var        string
	 */
	protected $state;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the title field.
	 * @var        string
	 */
	protected $title;

	/**
	 * The value for the description field.
	 * @var        string
	 */
	protected $description;

	/**
	 * @var        User
	 */
	protected $aUser;

	/**
	 * @var        array WorkpaceXProject[] Collection to store aggregation of WorkpaceXProject objects.
	 */
	protected $collWorkpaceXProjects;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkpaceXProjects.
	 */
	private $lastWorkpaceXProjectCriteria = null;

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
	 * Initializes internal state of BaseWorkspace object.
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
		$this->state = 'NEW';
	}

	/**
	 * Get the [workspace_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkspaceId()
	{
		return $this->workspace_id;
	}

	/**
	 * Get the [state] column value.
	 * 
	 * @return     string
	 */
	public function getState()
	{
		return $this->state;
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
	 * Get the [title] column value.
	 * 
	 * @return     string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Get the [description] column value.
	 * 
	 * @return     string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set the value of [workspace_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Workspace The current object (for fluent API support)
	 */
	public function setWorkspaceId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workspace_id !== $v) {
			$this->workspace_id = $v;
			$this->modifiedColumns[] = WorkspacePeer::WORKSPACE_ID;
		}

		return $this;
	} // setWorkspaceId()

	/**
	 * Set the value of [state] column.
	 * 
	 * @param      string $v new value
	 * @return     Workspace The current object (for fluent API support)
	 */
	public function setState($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->state !== $v || $v === 'NEW') {
			$this->state = $v;
			$this->modifiedColumns[] = WorkspacePeer::STATE;
		}

		return $this;
	} // setState()

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Workspace The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = WorkspacePeer::USER_ID;
		}

		if ($this->aUser !== null && $this->aUser->getUserId() !== $v) {
			$this->aUser = null;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [title] column.
	 * 
	 * @param      string $v new value
	 * @return     Workspace The current object (for fluent API support)
	 */
	public function setTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->title !== $v) {
			$this->title = $v;
			$this->modifiedColumns[] = WorkspacePeer::TITLE;
		}

		return $this;
	} // setTitle()

	/**
	 * Set the value of [description] column.
	 * 
	 * @param      string $v new value
	 * @return     Workspace The current object (for fluent API support)
	 */
	public function setDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = WorkspacePeer::DESCRIPTION;
		}

		return $this;
	} // setDescription()

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
			if (array_diff($this->modifiedColumns, array(WorkspacePeer::STATE))) {
				return false;
			}

			if ($this->state !== 'NEW') {
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

			$this->workspace_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->state = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->user_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = WorkspacePeer::NUM_COLUMNS - WorkspacePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Workspace object", $e);
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

		if ($this->aUser !== null && $this->user_id !== $this->aUser->getUserId()) {
			$this->aUser = null;
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
			$con = Propel::getConnection(WorkspacePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WorkspacePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aUser = null;
			$this->collWorkpaceXProjects = null;
			$this->lastWorkpaceXProjectCriteria = null;

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
			$con = Propel::getConnection(WorkspacePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			WorkspacePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(WorkspacePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			WorkspacePeer::addInstanceToPool($this);
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

			if ($this->aUser !== null) {
				if ($this->aUser->isModified() || $this->aUser->isNew()) {
					$affectedRows += $this->aUser->save($con);
				}
				$this->setUser($this->aUser);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = WorkspacePeer::WORKSPACE_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = WorkspacePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setWorkspaceId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += WorkspacePeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collWorkpaceXProjects !== null) {
				foreach ($this->collWorkpaceXProjects as $referrerFK) {
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aUser !== null) {
				if (!$this->aUser->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
				}
			}


			if (($retval = WorkspacePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collWorkpaceXProjects !== null) {
					foreach ($this->collWorkpaceXProjects as $referrerFK) {
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
		$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);

		if ($this->isColumnModified(WorkspacePeer::WORKSPACE_ID)) $criteria->add(WorkspacePeer::WORKSPACE_ID, $this->workspace_id);
		if ($this->isColumnModified(WorkspacePeer::STATE)) $criteria->add(WorkspacePeer::STATE, $this->state);
		if ($this->isColumnModified(WorkspacePeer::USER_ID)) $criteria->add(WorkspacePeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(WorkspacePeer::TITLE)) $criteria->add(WorkspacePeer::TITLE, $this->title);
		if ($this->isColumnModified(WorkspacePeer::DESCRIPTION)) $criteria->add(WorkspacePeer::DESCRIPTION, $this->description);

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
		$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);

		$criteria->add(WorkspacePeer::WORKSPACE_ID, $this->workspace_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getWorkspaceId();
	}

	/**
	 * Generic method to set the primary key (workspace_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setWorkspaceId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Workspace (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setState($this->state);

		$copyObj->setUserId($this->user_id);

		$copyObj->setTitle($this->title);

		$copyObj->setDescription($this->description);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getWorkpaceXProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkpaceXProject($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkspaceShares() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkspaceShare($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setWorkspaceId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     Workspace Clone of current object.
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
	 * @return     WorkspacePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WorkspacePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a User object.
	 *
	 * @param      User $v
	 * @return     Workspace The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setUser(User $v = null)
	{
		if ($v === null) {
			$this->setUserId(NULL);
		} else {
			$this->setUserId($v->getUserId());
		}

		$this->aUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the User object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkspace($this);
		}

		return $this;
	}


	/**
	 * Get the associated User object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     User The associated User object.
	 * @throws     PropelException
	 */
	public function getUser(PropelPDO $con = null)
	{
		if ($this->aUser === null && ($this->user_id !== null)) {
			$this->aUser = UserPeer::retrieveByPK($this->user_id, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aUser->addWorkspaces($this);
			 */
		}
		return $this->aUser;
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
	 * Otherwise if this Workspace has previously been saved, it will retrieve
	 * related WorkpaceXProjects from storage. If this Workspace is new, it will return
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
			$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkpaceXProjects === null) {
			if ($this->isNew()) {
			   $this->collWorkpaceXProjects = array();
			} else {

				$criteria->add(WorkpaceXProjectPeer::WORKSPACE_ID, $this->workspace_id);

				WorkpaceXProjectPeer::addSelectColumns($criteria);
				$this->collWorkpaceXProjects = WorkpaceXProjectPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkpaceXProjectPeer::WORKSPACE_ID, $this->workspace_id);

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
			$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);
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

				$criteria->add(WorkpaceXProjectPeer::WORKSPACE_ID, $this->workspace_id);

				$count = WorkpaceXProjectPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkpaceXProjectPeer::WORKSPACE_ID, $this->workspace_id);

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
			$l->setWorkspace($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Workspace is new, it will return
	 * an empty collection; or if this Workspace has previously
	 * been saved, it will retrieve related WorkpaceXProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Workspace.
	 */
	public function getWorkpaceXProjectsJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkpaceXProjects === null) {
			if ($this->isNew()) {
				$this->collWorkpaceXProjects = array();
			} else {

				$criteria->add(WorkpaceXProjectPeer::WORKSPACE_ID, $this->workspace_id);

				$this->collWorkpaceXProjects = WorkpaceXProjectPeer::doSelectJoinProject($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkpaceXProjectPeer::WORKSPACE_ID, $this->workspace_id);

			if (!isset($this->lastWorkpaceXProjectCriteria) || !$this->lastWorkpaceXProjectCriteria->equals($criteria)) {
				$this->collWorkpaceXProjects = WorkpaceXProjectPeer::doSelectJoinProject($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkpaceXProjectCriteria = $criteria;

		return $this->collWorkpaceXProjects;
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
	 * Otherwise if this Workspace has previously been saved, it will retrieve
	 * related WorkspaceShares from storage. If this Workspace is new, it will return
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
			$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkspaceShares === null) {
			if ($this->isNew()) {
			   $this->collWorkspaceShares = array();
			} else {

				$criteria->add(WorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);

				WorkspaceSharePeer::addSelectColumns($criteria);
				$this->collWorkspaceShares = WorkspaceSharePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);

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
			$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);
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

				$criteria->add(WorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);

				$count = WorkspaceSharePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);

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
			$l->setWorkspace($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Workspace is new, it will return
	 * an empty collection; or if this Workspace has previously
	 * been saved, it will retrieve related WorkspaceShares from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Workspace.
	 */
	public function getWorkspaceSharesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkspacePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkspaceShares === null) {
			if ($this->isNew()) {
				$this->collWorkspaceShares = array();
			} else {

				$criteria->add(WorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);

				$this->collWorkspaceShares = WorkspaceSharePeer::doSelectJoinUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkspaceSharePeer::WORKSPACE_ID, $this->workspace_id);

			if (!isset($this->lastWorkspaceShareCriteria) || !$this->lastWorkspaceShareCriteria->equals($criteria)) {
				$this->collWorkspaceShares = WorkspaceSharePeer::doSelectJoinUser($criteria, $con, $join_behavior);
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
			if ($this->collWorkpaceXProjects) {
				foreach ((array) $this->collWorkpaceXProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkspaceShares) {
				foreach ((array) $this->collWorkspaceShares as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collWorkpaceXProjects = null;
		$this->collWorkspaceShares = null;
			$this->aUser = null;
	}

} // BaseWorkspace
