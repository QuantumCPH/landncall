<?php

/**
 * Base class that represents a row from the 'usage_alert' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 12:45:39
 *
 * @package    lib.model.om
 */
abstract class BaseUsageAlert extends BaseObject  implements Persistent {


  const PEER = 'UsageAlertPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UsageAlertPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the alert_amount field.
	 * @var        int
	 */
	protected $alert_amount;

	/**
	 * The value for the sms_alert_message field.
	 * @var        string
	 */
	protected $sms_alert_message;

	/**
	 * The value for the sms_active field.
	 * @var        boolean
	 */
	protected $sms_active;

	/**
	 * The value for the email_alert_message field.
	 * @var        string
	 */
	protected $email_alert_message;

	/**
	 * The value for the email_active field.
	 * @var        boolean
	 */
	protected $email_active;

	/**
	 * The value for the country field.
	 * @var        int
	 */
	protected $country;

	/**
	 * The value for the sender_name field.
	 * @var        int
	 */
	protected $sender_name;

	/**
	 * The value for the status field.
	 * @var        int
	 */
	protected $status;

	/**
	 * @var        EnableCountry
	 */
	protected $aEnableCountry;

	/**
	 * @var        UsageAlertSender
	 */
	protected $aUsageAlertSender;

	/**
	 * @var        Status
	 */
	protected $aStatusRelatedByStatus;

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
	 * Initializes internal state of BaseUsageAlert object.
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
	 * Get the [alert_amount] column value.
	 * 
	 * @return     int
	 */
	public function getAlertAmount()
	{
		return $this->alert_amount;
	}

	/**
	 * Get the [sms_alert_message] column value.
	 * 
	 * @return     string
	 */
	public function getSmsAlertMessage()
	{
		return $this->sms_alert_message;
	}

	/**
	 * Get the [sms_active] column value.
	 * 
	 * @return     boolean
	 */
	public function getSmsActive()
	{
		return $this->sms_active;
	}

	/**
	 * Get the [email_alert_message] column value.
	 * 
	 * @return     string
	 */
	public function getEmailAlertMessage()
	{
		return $this->email_alert_message;
	}

	/**
	 * Get the [email_active] column value.
	 * 
	 * @return     boolean
	 */
	public function getEmailActive()
	{
		return $this->email_active;
	}

	/**
	 * Get the [country] column value.
	 * 
	 * @return     int
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Get the [sender_name] column value.
	 * 
	 * @return     int
	 */
	public function getSenderName()
	{
		return $this->sender_name;
	}

	/**
	 * Get the [status] column value.
	 * 
	 * @return     int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = UsageAlertPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [alert_amount] column.
	 * 
	 * @param      int $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setAlertAmount($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->alert_amount !== $v) {
			$this->alert_amount = $v;
			$this->modifiedColumns[] = UsageAlertPeer::ALERT_AMOUNT;
		}

		return $this;
	} // setAlertAmount()

	/**
	 * Set the value of [sms_alert_message] column.
	 * 
	 * @param      string $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setSmsAlertMessage($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->sms_alert_message !== $v) {
			$this->sms_alert_message = $v;
			$this->modifiedColumns[] = UsageAlertPeer::SMS_ALERT_MESSAGE;
		}

		return $this;
	} // setSmsAlertMessage()

	/**
	 * Set the value of [sms_active] column.
	 * 
	 * @param      boolean $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setSmsActive($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->sms_active !== $v) {
			$this->sms_active = $v;
			$this->modifiedColumns[] = UsageAlertPeer::SMS_ACTIVE;
		}

		return $this;
	} // setSmsActive()

	/**
	 * Set the value of [email_alert_message] column.
	 * 
	 * @param      string $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setEmailAlertMessage($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->email_alert_message !== $v) {
			$this->email_alert_message = $v;
			$this->modifiedColumns[] = UsageAlertPeer::EMAIL_ALERT_MESSAGE;
		}

		return $this;
	} // setEmailAlertMessage()

	/**
	 * Set the value of [email_active] column.
	 * 
	 * @param      boolean $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setEmailActive($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->email_active !== $v) {
			$this->email_active = $v;
			$this->modifiedColumns[] = UsageAlertPeer::EMAIL_ACTIVE;
		}

		return $this;
	} // setEmailActive()

	/**
	 * Set the value of [country] column.
	 * 
	 * @param      int $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setCountry($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->country !== $v) {
			$this->country = $v;
			$this->modifiedColumns[] = UsageAlertPeer::COUNTRY;
		}

		if ($this->aEnableCountry !== null && $this->aEnableCountry->getId() !== $v) {
			$this->aEnableCountry = null;
		}

		return $this;
	} // setCountry()

	/**
	 * Set the value of [sender_name] column.
	 * 
	 * @param      int $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setSenderName($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->sender_name !== $v) {
			$this->sender_name = $v;
			$this->modifiedColumns[] = UsageAlertPeer::SENDER_NAME;
		}

		if ($this->aUsageAlertSender !== null && $this->aUsageAlertSender->getId() !== $v) {
			$this->aUsageAlertSender = null;
		}

		return $this;
	} // setSenderName()

	/**
	 * Set the value of [status] column.
	 * 
	 * @param      int $v new value
	 * @return     UsageAlert The current object (for fluent API support)
	 */
	public function setStatus($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->status !== $v) {
			$this->status = $v;
			$this->modifiedColumns[] = UsageAlertPeer::STATUS;
		}

		if ($this->aStatusRelatedByStatus !== null && $this->aStatusRelatedByStatus->getId() !== $v) {
			$this->aStatusRelatedByStatus = null;
		}

		return $this;
	} // setStatus()

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
			$this->alert_amount = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->sms_alert_message = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->sms_active = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
			$this->email_alert_message = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->email_active = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->country = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->sender_name = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->status = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 9; // 9 = UsageAlertPeer::NUM_COLUMNS - UsageAlertPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating UsageAlert object", $e);
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

		if ($this->aEnableCountry !== null && $this->country !== $this->aEnableCountry->getId()) {
			$this->aEnableCountry = null;
		}
		if ($this->aUsageAlertSender !== null && $this->sender_name !== $this->aUsageAlertSender->getId()) {
			$this->aUsageAlertSender = null;
		}
		if ($this->aStatusRelatedByStatus !== null && $this->status !== $this->aStatusRelatedByStatus->getId()) {
			$this->aStatusRelatedByStatus = null;
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
			$con = Propel::getConnection(UsageAlertPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = UsageAlertPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aEnableCountry = null;
			$this->aUsageAlertSender = null;
			$this->aStatusRelatedByStatus = null;
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

    foreach (sfMixer::getCallables('BaseUsageAlert:delete:pre') as $callable)
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
			$con = Propel::getConnection(UsageAlertPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			UsageAlertPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseUsageAlert:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseUsageAlert:save:pre') as $callable)
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
			$con = Propel::getConnection(UsageAlertPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseUsageAlert:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			UsageAlertPeer::addInstanceToPool($this);
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

			if ($this->aEnableCountry !== null) {
				if ($this->aEnableCountry->isModified() || $this->aEnableCountry->isNew()) {
					$affectedRows += $this->aEnableCountry->save($con);
				}
				$this->setEnableCountry($this->aEnableCountry);
			}

			if ($this->aUsageAlertSender !== null) {
				if ($this->aUsageAlertSender->isModified() || $this->aUsageAlertSender->isNew()) {
					$affectedRows += $this->aUsageAlertSender->save($con);
				}
				$this->setUsageAlertSender($this->aUsageAlertSender);
			}

			if ($this->aStatusRelatedByStatus !== null) {
				if ($this->aStatusRelatedByStatus->isModified() || $this->aStatusRelatedByStatus->isNew()) {
					$affectedRows += $this->aStatusRelatedByStatus->save($con);
				}
				$this->setStatusRelatedByStatus($this->aStatusRelatedByStatus);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = UsageAlertPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = UsageAlertPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += UsageAlertPeer::doUpdate($this, $con);
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

			if ($this->aEnableCountry !== null) {
				if (!$this->aEnableCountry->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEnableCountry->getValidationFailures());
				}
			}

			if ($this->aUsageAlertSender !== null) {
				if (!$this->aUsageAlertSender->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUsageAlertSender->getValidationFailures());
				}
			}

			if ($this->aStatusRelatedByStatus !== null) {
				if (!$this->aStatusRelatedByStatus->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aStatusRelatedByStatus->getValidationFailures());
				}
			}


			if (($retval = UsageAlertPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = UsageAlertPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getAlertAmount();
				break;
			case 2:
				return $this->getSmsAlertMessage();
				break;
			case 3:
				return $this->getSmsActive();
				break;
			case 4:
				return $this->getEmailAlertMessage();
				break;
			case 5:
				return $this->getEmailActive();
				break;
			case 6:
				return $this->getCountry();
				break;
			case 7:
				return $this->getSenderName();
				break;
			case 8:
				return $this->getStatus();
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
		$keys = UsageAlertPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getAlertAmount(),
			$keys[2] => $this->getSmsAlertMessage(),
			$keys[3] => $this->getSmsActive(),
			$keys[4] => $this->getEmailAlertMessage(),
			$keys[5] => $this->getEmailActive(),
			$keys[6] => $this->getCountry(),
			$keys[7] => $this->getSenderName(),
			$keys[8] => $this->getStatus(),
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
		$pos = UsageAlertPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setAlertAmount($value);
				break;
			case 2:
				$this->setSmsAlertMessage($value);
				break;
			case 3:
				$this->setSmsActive($value);
				break;
			case 4:
				$this->setEmailAlertMessage($value);
				break;
			case 5:
				$this->setEmailActive($value);
				break;
			case 6:
				$this->setCountry($value);
				break;
			case 7:
				$this->setSenderName($value);
				break;
			case 8:
				$this->setStatus($value);
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
		$keys = UsageAlertPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setAlertAmount($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setSmsAlertMessage($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setSmsActive($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setEmailAlertMessage($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setEmailActive($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCountry($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setSenderName($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setStatus($arr[$keys[8]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UsageAlertPeer::DATABASE_NAME);

		if ($this->isColumnModified(UsageAlertPeer::ID)) $criteria->add(UsageAlertPeer::ID, $this->id);
		if ($this->isColumnModified(UsageAlertPeer::ALERT_AMOUNT)) $criteria->add(UsageAlertPeer::ALERT_AMOUNT, $this->alert_amount);
		if ($this->isColumnModified(UsageAlertPeer::SMS_ALERT_MESSAGE)) $criteria->add(UsageAlertPeer::SMS_ALERT_MESSAGE, $this->sms_alert_message);
		if ($this->isColumnModified(UsageAlertPeer::SMS_ACTIVE)) $criteria->add(UsageAlertPeer::SMS_ACTIVE, $this->sms_active);
		if ($this->isColumnModified(UsageAlertPeer::EMAIL_ALERT_MESSAGE)) $criteria->add(UsageAlertPeer::EMAIL_ALERT_MESSAGE, $this->email_alert_message);
		if ($this->isColumnModified(UsageAlertPeer::EMAIL_ACTIVE)) $criteria->add(UsageAlertPeer::EMAIL_ACTIVE, $this->email_active);
		if ($this->isColumnModified(UsageAlertPeer::COUNTRY)) $criteria->add(UsageAlertPeer::COUNTRY, $this->country);
		if ($this->isColumnModified(UsageAlertPeer::SENDER_NAME)) $criteria->add(UsageAlertPeer::SENDER_NAME, $this->sender_name);
		if ($this->isColumnModified(UsageAlertPeer::STATUS)) $criteria->add(UsageAlertPeer::STATUS, $this->status);

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
		$criteria = new Criteria(UsageAlertPeer::DATABASE_NAME);

		$criteria->add(UsageAlertPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of UsageAlert (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setAlertAmount($this->alert_amount);

		$copyObj->setSmsAlertMessage($this->sms_alert_message);

		$copyObj->setSmsActive($this->sms_active);

		$copyObj->setEmailAlertMessage($this->email_alert_message);

		$copyObj->setEmailActive($this->email_active);

		$copyObj->setCountry($this->country);

		$copyObj->setSenderName($this->sender_name);

		$copyObj->setStatus($this->status);


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
	 * @return     UsageAlert Clone of current object.
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
	 * @return     UsageAlertPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UsageAlertPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a EnableCountry object.
	 *
	 * @param      EnableCountry $v
	 * @return     UsageAlert The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setEnableCountry(EnableCountry $v = null)
	{
		if ($v === null) {
			$this->setCountry(NULL);
		} else {
			$this->setCountry($v->getId());
		}

		$this->aEnableCountry = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the EnableCountry object, it will not be re-added.
		if ($v !== null) {
			$v->addUsageAlert($this);
		}

		return $this;
	}


	/**
	 * Get the associated EnableCountry object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     EnableCountry The associated EnableCountry object.
	 * @throws     PropelException
	 */
	public function getEnableCountry(PropelPDO $con = null)
	{
		if ($this->aEnableCountry === null && ($this->country !== null)) {
			$c = new Criteria(EnableCountryPeer::DATABASE_NAME);
			$c->add(EnableCountryPeer::ID, $this->country);
			$this->aEnableCountry = EnableCountryPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aEnableCountry->addUsageAlerts($this);
			 */
		}
		return $this->aEnableCountry;
	}

	/**
	 * Declares an association between this object and a UsageAlertSender object.
	 *
	 * @param      UsageAlertSender $v
	 * @return     UsageAlert The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setUsageAlertSender(UsageAlertSender $v = null)
	{
		if ($v === null) {
			$this->setSenderName(NULL);
		} else {
			$this->setSenderName($v->getId());
		}

		$this->aUsageAlertSender = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the UsageAlertSender object, it will not be re-added.
		if ($v !== null) {
			$v->addUsageAlert($this);
		}

		return $this;
	}


	/**
	 * Get the associated UsageAlertSender object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     UsageAlertSender The associated UsageAlertSender object.
	 * @throws     PropelException
	 */
	public function getUsageAlertSender(PropelPDO $con = null)
	{
		if ($this->aUsageAlertSender === null && ($this->sender_name !== null)) {
			$c = new Criteria(UsageAlertSenderPeer::DATABASE_NAME);
			$c->add(UsageAlertSenderPeer::ID, $this->sender_name);
			$this->aUsageAlertSender = UsageAlertSenderPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aUsageAlertSender->addUsageAlerts($this);
			 */
		}
		return $this->aUsageAlertSender;
	}

	/**
	 * Declares an association between this object and a Status object.
	 *
	 * @param      Status $v
	 * @return     UsageAlert The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setStatusRelatedByStatus(Status $v = null)
	{
		if ($v === null) {
			$this->setStatus(NULL);
		} else {
			$this->setStatus($v->getId());
		}

		$this->aStatusRelatedByStatus = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Status object, it will not be re-added.
		if ($v !== null) {
			$v->addUsageAlert($this);
		}

		return $this;
	}


	/**
	 * Get the associated Status object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Status The associated Status object.
	 * @throws     PropelException
	 */
	public function getStatusRelatedByStatus(PropelPDO $con = null)
	{
		if ($this->aStatusRelatedByStatus === null && ($this->status !== null)) {
			$c = new Criteria(StatusPeer::DATABASE_NAME);
			$c->add(StatusPeer::ID, $this->status);
			$this->aStatusRelatedByStatus = StatusPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aStatusRelatedByStatus->addUsageAlerts($this);
			 */
		}
		return $this->aStatusRelatedByStatus;
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

			$this->aEnableCountry = null;
			$this->aUsageAlertSender = null;
			$this->aStatusRelatedByStatus = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseUsageAlert:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseUsageAlert::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseUsageAlert
