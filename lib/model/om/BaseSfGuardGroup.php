<?php

/**
 * Base class that represents a row from the 'sf_guard_group' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 05/10/10 11:08:22
 *
 * @package    lib.model.om
 */
abstract class BaseSfGuardGroup extends BaseObject  implements Persistent {


  const PEER = 'SfGuardGroupPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        SfGuardGroupPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the description field.
	 * @var        string
	 */
	protected $description;

	/**
	 * @var        array SfGuardGroupPermission[] Collection to store aggregation of SfGuardGroupPermission objects.
	 */
	protected $collSfGuardGroupPermissions;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSfGuardGroupPermissions.
	 */
	private $lastSfGuardGroupPermissionCriteria = null;

	/**
	 * @var        array SfGuardUserGroup[] Collection to store aggregation of SfGuardUserGroup objects.
	 */
	protected $collSfGuardUserGroups;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSfGuardUserGroups.
	 */
	private $lastSfGuardUserGroupCriteria = null;

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
	 * Initializes internal state of BaseSfGuardGroup object.
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
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{
		return $this->id;
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
	 * Get the [description] column value.
	 * 
	 * @return     string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     SfGuardGroup The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = SfGuardGroupPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     SfGuardGroup The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = SfGuardGroupPeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Set the value of [description] column.
	 * 
	 * @param      string $v new value
	 * @return     SfGuardGroup The current object (for fluent API support)
	 */
	public function setDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = SfGuardGroupPeer::DESCRIPTION;
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

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 3; // 3 = SfGuardGroupPeer::NUM_COLUMNS - SfGuardGroupPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating SfGuardGroup object", $e);
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
			$con = Propel::getConnection(SfGuardGroupPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = SfGuardGroupPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collSfGuardGroupPermissions = null;
			$this->lastSfGuardGroupPermissionCriteria = null;

			$this->collSfGuardUserGroups = null;
			$this->lastSfGuardUserGroupCriteria = null;

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

    foreach (sfMixer::getCallables('BaseSfGuardGroup:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(SfGuardGroupPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			SfGuardGroupPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseSfGuardGroup:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
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

    foreach (sfMixer::getCallables('BaseSfGuardGroup:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(SfGuardGroupPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseSfGuardGroup:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			SfGuardGroupPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = SfGuardGroupPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = SfGuardGroupPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += SfGuardGroupPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collSfGuardGroupPermissions !== null) {
				foreach ($this->collSfGuardGroupPermissions as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSfGuardUserGroups !== null) {
				foreach ($this->collSfGuardUserGroups as $referrerFK) {
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


			if (($retval = SfGuardGroupPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collSfGuardGroupPermissions !== null) {
					foreach ($this->collSfGuardGroupPermissions as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSfGuardUserGroups !== null) {
					foreach ($this->collSfGuardUserGroups as $referrerFK) {
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
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = SfGuardGroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getName();
				break;
			case 2:
				return $this->getDescription();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. Defaults to BasePeer::TYPE_PHPNAME.
	 * @param      boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns.  Defaults to TRUE.
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = SfGuardGroupPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getDescription(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = SfGuardGroupPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setName($value);
				break;
			case 2:
				$this->setDescription($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = SfGuardGroupPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDescription($arr[$keys[2]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);

		if ($this->isColumnModified(SfGuardGroupPeer::ID)) $criteria->add(SfGuardGroupPeer::ID, $this->id);
		if ($this->isColumnModified(SfGuardGroupPeer::NAME)) $criteria->add(SfGuardGroupPeer::NAME, $this->name);
		if ($this->isColumnModified(SfGuardGroupPeer::DESCRIPTION)) $criteria->add(SfGuardGroupPeer::DESCRIPTION, $this->description);

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
		$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);

		$criteria->add(SfGuardGroupPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of SfGuardGroup (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setName($this->name);

		$copyObj->setDescription($this->description);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getSfGuardGroupPermissions() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSfGuardGroupPermission($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getSfGuardUserGroups() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSfGuardUserGroup($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     SfGuardGroup Clone of current object.
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
	 * @return     SfGuardGroupPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new SfGuardGroupPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collSfGuardGroupPermissions collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSfGuardGroupPermissions()
	 */
	public function clearSfGuardGroupPermissions()
	{
		$this->collSfGuardGroupPermissions = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSfGuardGroupPermissions collection (array).
	 *
	 * By default this just sets the collSfGuardGroupPermissions collection to an empty array (like clearcollSfGuardGroupPermissions());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSfGuardGroupPermissions()
	{
		$this->collSfGuardGroupPermissions = array();
	}

	/**
	 * Gets an array of SfGuardGroupPermission objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this SfGuardGroup has previously been saved, it will retrieve
	 * related SfGuardGroupPermissions from storage. If this SfGuardGroup is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array SfGuardGroupPermission[]
	 * @throws     PropelException
	 */
	public function getSfGuardGroupPermissions($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSfGuardGroupPermissions === null) {
			if ($this->isNew()) {
			   $this->collSfGuardGroupPermissions = array();
			} else {

				$criteria->add(SfGuardGroupPermissionPeer::GROUP_ID, $this->id);

				SfGuardGroupPermissionPeer::addSelectColumns($criteria);
				$this->collSfGuardGroupPermissions = SfGuardGroupPermissionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SfGuardGroupPermissionPeer::GROUP_ID, $this->id);

				SfGuardGroupPermissionPeer::addSelectColumns($criteria);
				if (!isset($this->lastSfGuardGroupPermissionCriteria) || !$this->lastSfGuardGroupPermissionCriteria->equals($criteria)) {
					$this->collSfGuardGroupPermissions = SfGuardGroupPermissionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSfGuardGroupPermissionCriteria = $criteria;
		return $this->collSfGuardGroupPermissions;
	}

	/**
	 * Returns the number of related SfGuardGroupPermission objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related SfGuardGroupPermission objects.
	 * @throws     PropelException
	 */
	public function countSfGuardGroupPermissions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSfGuardGroupPermissions === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SfGuardGroupPermissionPeer::GROUP_ID, $this->id);

				$count = SfGuardGroupPermissionPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SfGuardGroupPermissionPeer::GROUP_ID, $this->id);

				if (!isset($this->lastSfGuardGroupPermissionCriteria) || !$this->lastSfGuardGroupPermissionCriteria->equals($criteria)) {
					$count = SfGuardGroupPermissionPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collSfGuardGroupPermissions);
				}
			} else {
				$count = count($this->collSfGuardGroupPermissions);
			}
		}
		$this->lastSfGuardGroupPermissionCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a SfGuardGroupPermission object to this object
	 * through the SfGuardGroupPermission foreign key attribute.
	 *
	 * @param      SfGuardGroupPermission $l SfGuardGroupPermission
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSfGuardGroupPermission(SfGuardGroupPermission $l)
	{
		if ($this->collSfGuardGroupPermissions === null) {
			$this->initSfGuardGroupPermissions();
		}
		if (!in_array($l, $this->collSfGuardGroupPermissions, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSfGuardGroupPermissions, $l);
			$l->setSfGuardGroup($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this SfGuardGroup is new, it will return
	 * an empty collection; or if this SfGuardGroup has previously
	 * been saved, it will retrieve related SfGuardGroupPermissions from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in SfGuardGroup.
	 */
	public function getSfGuardGroupPermissionsJoinSfGuardPermission($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSfGuardGroupPermissions === null) {
			if ($this->isNew()) {
				$this->collSfGuardGroupPermissions = array();
			} else {

				$criteria->add(SfGuardGroupPermissionPeer::GROUP_ID, $this->id);

				$this->collSfGuardGroupPermissions = SfGuardGroupPermissionPeer::doSelectJoinSfGuardPermission($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SfGuardGroupPermissionPeer::GROUP_ID, $this->id);

			if (!isset($this->lastSfGuardGroupPermissionCriteria) || !$this->lastSfGuardGroupPermissionCriteria->equals($criteria)) {
				$this->collSfGuardGroupPermissions = SfGuardGroupPermissionPeer::doSelectJoinSfGuardPermission($criteria, $con, $join_behavior);
			}
		}
		$this->lastSfGuardGroupPermissionCriteria = $criteria;

		return $this->collSfGuardGroupPermissions;
	}

	/**
	 * Clears out the collSfGuardUserGroups collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSfGuardUserGroups()
	 */
	public function clearSfGuardUserGroups()
	{
		$this->collSfGuardUserGroups = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSfGuardUserGroups collection (array).
	 *
	 * By default this just sets the collSfGuardUserGroups collection to an empty array (like clearcollSfGuardUserGroups());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSfGuardUserGroups()
	{
		$this->collSfGuardUserGroups = array();
	}

	/**
	 * Gets an array of SfGuardUserGroup objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this SfGuardGroup has previously been saved, it will retrieve
	 * related SfGuardUserGroups from storage. If this SfGuardGroup is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array SfGuardUserGroup[]
	 * @throws     PropelException
	 */
	public function getSfGuardUserGroups($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSfGuardUserGroups === null) {
			if ($this->isNew()) {
			   $this->collSfGuardUserGroups = array();
			} else {

				$criteria->add(SfGuardUserGroupPeer::GROUP_ID, $this->id);

				SfGuardUserGroupPeer::addSelectColumns($criteria);
				$this->collSfGuardUserGroups = SfGuardUserGroupPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SfGuardUserGroupPeer::GROUP_ID, $this->id);

				SfGuardUserGroupPeer::addSelectColumns($criteria);
				if (!isset($this->lastSfGuardUserGroupCriteria) || !$this->lastSfGuardUserGroupCriteria->equals($criteria)) {
					$this->collSfGuardUserGroups = SfGuardUserGroupPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSfGuardUserGroupCriteria = $criteria;
		return $this->collSfGuardUserGroups;
	}

	/**
	 * Returns the number of related SfGuardUserGroup objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related SfGuardUserGroup objects.
	 * @throws     PropelException
	 */
	public function countSfGuardUserGroups(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSfGuardUserGroups === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SfGuardUserGroupPeer::GROUP_ID, $this->id);

				$count = SfGuardUserGroupPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SfGuardUserGroupPeer::GROUP_ID, $this->id);

				if (!isset($this->lastSfGuardUserGroupCriteria) || !$this->lastSfGuardUserGroupCriteria->equals($criteria)) {
					$count = SfGuardUserGroupPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collSfGuardUserGroups);
				}
			} else {
				$count = count($this->collSfGuardUserGroups);
			}
		}
		$this->lastSfGuardUserGroupCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a SfGuardUserGroup object to this object
	 * through the SfGuardUserGroup foreign key attribute.
	 *
	 * @param      SfGuardUserGroup $l SfGuardUserGroup
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSfGuardUserGroup(SfGuardUserGroup $l)
	{
		if ($this->collSfGuardUserGroups === null) {
			$this->initSfGuardUserGroups();
		}
		if (!in_array($l, $this->collSfGuardUserGroups, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSfGuardUserGroups, $l);
			$l->setSfGuardGroup($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this SfGuardGroup is new, it will return
	 * an empty collection; or if this SfGuardGroup has previously
	 * been saved, it will retrieve related SfGuardUserGroups from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in SfGuardGroup.
	 */
	public function getSfGuardUserGroupsJoinSfGuardUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SfGuardGroupPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSfGuardUserGroups === null) {
			if ($this->isNew()) {
				$this->collSfGuardUserGroups = array();
			} else {

				$criteria->add(SfGuardUserGroupPeer::GROUP_ID, $this->id);

				$this->collSfGuardUserGroups = SfGuardUserGroupPeer::doSelectJoinSfGuardUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SfGuardUserGroupPeer::GROUP_ID, $this->id);

			if (!isset($this->lastSfGuardUserGroupCriteria) || !$this->lastSfGuardUserGroupCriteria->equals($criteria)) {
				$this->collSfGuardUserGroups = SfGuardUserGroupPeer::doSelectJoinSfGuardUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastSfGuardUserGroupCriteria = $criteria;

		return $this->collSfGuardUserGroups;
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
			if ($this->collSfGuardGroupPermissions) {
				foreach ((array) $this->collSfGuardGroupPermissions as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSfGuardUserGroups) {
				foreach ((array) $this->collSfGuardUserGroups as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collSfGuardGroupPermissions = null;
		$this->collSfGuardUserGroups = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseSfGuardGroup:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseSfGuardGroup::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseSfGuardGroup
