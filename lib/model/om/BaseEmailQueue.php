<?php

/**
 * Base class that represents a row from the 'email_queue' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * Tue Dec 20 13:41:53 2011
 *
 * @package    lib.model.om
 */
abstract class BaseEmailQueue extends BaseObject  implements Persistent {


  const PEER = 'EmailQueuePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        EmailQueuePeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        string
	 */
	protected $id;

	/**
	 * The value for the message field.
	 * @var        string
	 */
	protected $message;

	/**
	 * The value for the receipient_email field.
	 * @var        string
	 */
	protected $receipient_email;

	/**
	 * The value for the subject field.
	 * @var        string
	 */
	protected $subject;

	/**
	 * The value for the created_at field.
	 * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
	 * @var        string
	 */
	protected $created_at;

	/**
	 * The value for the receipient_name field.
	 * @var        string
	 */
	protected $receipient_name;

	/**
	 * The value for the ref_id field.
	 * @var        string
	 */
	protected $ref_id;

	/**
	 * The value for the email_status_id field.
	 * Note: this column has a database default value of: 1
	 * @var        int
	 */
	protected $email_status_id;

	/**
	 * The value for the email_type field.
	 * @var        string
	 */
	protected $email_type;

	/**
	 * The value for the cutomer_id field.
	 * @var        int
	 */
	protected $cutomer_id;

	/**
	 * The value for the agent_id field.
	 * @var        int
	 */
	protected $agent_id;

	/**
	 * @var        Status
	 */
	protected $aStatus;

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
	 * Initializes internal state of BaseEmailQueue object.
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
		$this->email_status_id = 1;
	}

	/**
	 * Get the [id] column value.
	 * 
	 * @return     string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [message] column value.
	 * 
	 * @return     string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Get the [receipient_email] column value.
	 * 
	 * @return     string
	 */
	public function getReceipientEmail()
	{
		return $this->receipient_email;
	}

	/**
	 * Get the [subject] column value.
	 * 
	 * @return     string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Get the [optionally formatted] temporal [created_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->created_at === null) {
			return null;
		}


		if ($this->created_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->created_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [receipient_name] column value.
	 * 
	 * @return     string
	 */
	public function getReceipientName()
	{
		return $this->receipient_name;
	}

	/**
	 * Get the [ref_id] column value.
	 * 
	 * @return     string
	 */
	public function getRefId()
	{
		return $this->ref_id;
	}

	/**
	 * Get the [email_status_id] column value.
	 * 
	 * @return     int
	 */
	public function getEmailStatusId()
	{
		return $this->email_status_id;
	}

	/**
	 * Get the [email_type] column value.
	 * 
	 * @return     string
	 */
	public function getEmailType()
	{
		return $this->email_type;
	}

	/**
	 * Get the [cutomer_id] column value.
	 * 
	 * @return     int
	 */
	public function getCutomerId()
	{
		return $this->cutomer_id;
	}

	/**
	 * Get the [agent_id] column value.
	 * 
	 * @return     int
	 */
	public function getAgentId()
	{
		return $this->agent_id;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      string $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = EmailQueuePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [message] column.
	 * 
	 * @param      string $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setMessage($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->message !== $v) {
			$this->message = $v;
			$this->modifiedColumns[] = EmailQueuePeer::MESSAGE;
		}

		return $this;
	} // setMessage()

	/**
	 * Set the value of [receipient_email] column.
	 * 
	 * @param      string $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setReceipientEmail($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->receipient_email !== $v) {
			$this->receipient_email = $v;
			$this->modifiedColumns[] = EmailQueuePeer::RECEIPIENT_EMAIL;
		}

		return $this;
	} // setReceipientEmail()

	/**
	 * Set the value of [subject] column.
	 * 
	 * @param      string $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setSubject($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->subject !== $v) {
			$this->subject = $v;
			$this->modifiedColumns[] = EmailQueuePeer::SUBJECT;
		}

		return $this;
	} // setSubject()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setCreatedAt($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->created_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->created_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = EmailQueuePeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

	/**
	 * Set the value of [receipient_name] column.
	 * 
	 * @param      string $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setReceipientName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->receipient_name !== $v) {
			$this->receipient_name = $v;
			$this->modifiedColumns[] = EmailQueuePeer::RECEIPIENT_NAME;
		}

		return $this;
	} // setReceipientName()

	/**
	 * Set the value of [ref_id] column.
	 * 
	 * @param      string $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setRefId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->ref_id !== $v) {
			$this->ref_id = $v;
			$this->modifiedColumns[] = EmailQueuePeer::REF_ID;
		}

		return $this;
	} // setRefId()

	/**
	 * Set the value of [email_status_id] column.
	 * 
	 * @param      int $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setEmailStatusId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->email_status_id !== $v || $v === 1) {
			$this->email_status_id = $v;
			$this->modifiedColumns[] = EmailQueuePeer::EMAIL_STATUS_ID;
		}

		if ($this->aStatus !== null && $this->aStatus->getId() !== $v) {
			$this->aStatus = null;
		}

		return $this;
	} // setEmailStatusId()

	/**
	 * Set the value of [email_type] column.
	 * 
	 * @param      string $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setEmailType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->email_type !== $v) {
			$this->email_type = $v;
			$this->modifiedColumns[] = EmailQueuePeer::EMAIL_TYPE;
		}

		return $this;
	} // setEmailType()

	/**
	 * Set the value of [cutomer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setCutomerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->cutomer_id !== $v) {
			$this->cutomer_id = $v;
			$this->modifiedColumns[] = EmailQueuePeer::CUTOMER_ID;
		}

		return $this;
	} // setCutomerId()

	/**
	 * Set the value of [agent_id] column.
	 * 
	 * @param      int $v new value
	 * @return     EmailQueue The current object (for fluent API support)
	 */
	public function setAgentId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->agent_id !== $v) {
			$this->agent_id = $v;
			$this->modifiedColumns[] = EmailQueuePeer::AGENT_ID;
		}

		return $this;
	} // setAgentId()

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
			if (array_diff($this->modifiedColumns, array(EmailQueuePeer::EMAIL_STATUS_ID))) {
				return false;
			}

			if ($this->email_status_id !== 1) {
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

			$this->id = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
			$this->message = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->receipient_email = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->subject = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->created_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->receipient_name = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->ref_id = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->email_status_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->email_type = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->cutomer_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
			$this->agent_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 11; // 11 = EmailQueuePeer::NUM_COLUMNS - EmailQueuePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating EmailQueue object", $e);
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

		if ($this->aStatus !== null && $this->email_status_id !== $this->aStatus->getId()) {
			$this->aStatus = null;
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
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = EmailQueuePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aStatus = null;
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

    foreach (sfMixer::getCallables('BaseEmailQueue:delete:pre') as $callable)
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
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			EmailQueuePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseEmailQueue:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseEmailQueue:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(EmailQueuePeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseEmailQueue:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			EmailQueuePeer::addInstanceToPool($this);
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

			if ($this->aStatus !== null) {
				if ($this->aStatus->isModified() || $this->aStatus->isNew()) {
					$affectedRows += $this->aStatus->save($con);
				}
				$this->setStatus($this->aStatus);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = EmailQueuePeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = EmailQueuePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += EmailQueuePeer::doUpdate($this, $con);
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

			if ($this->aStatus !== null) {
				if (!$this->aStatus->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aStatus->getValidationFailures());
				}
			}


			if (($retval = EmailQueuePeer::doValidate($this, $columns)) !== true) {
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
		$pos = EmailQueuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getMessage();
				break;
			case 2:
				return $this->getReceipientEmail();
				break;
			case 3:
				return $this->getSubject();
				break;
			case 4:
				return $this->getCreatedAt();
				break;
			case 5:
				return $this->getReceipientName();
				break;
			case 6:
				return $this->getRefId();
				break;
			case 7:
				return $this->getEmailStatusId();
				break;
			case 8:
				return $this->getEmailType();
				break;
			case 9:
				return $this->getCutomerId();
				break;
			case 10:
				return $this->getAgentId();
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
		$keys = EmailQueuePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getMessage(),
			$keys[2] => $this->getReceipientEmail(),
			$keys[3] => $this->getSubject(),
			$keys[4] => $this->getCreatedAt(),
			$keys[5] => $this->getReceipientName(),
			$keys[6] => $this->getRefId(),
			$keys[7] => $this->getEmailStatusId(),
			$keys[8] => $this->getEmailType(),
			$keys[9] => $this->getCutomerId(),
			$keys[10] => $this->getAgentId(),
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
		$pos = EmailQueuePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setMessage($value);
				break;
			case 2:
				$this->setReceipientEmail($value);
				break;
			case 3:
				$this->setSubject($value);
				break;
			case 4:
				$this->setCreatedAt($value);
				break;
			case 5:
				$this->setReceipientName($value);
				break;
			case 6:
				$this->setRefId($value);
				break;
			case 7:
				$this->setEmailStatusId($value);
				break;
			case 8:
				$this->setEmailType($value);
				break;
			case 9:
				$this->setCutomerId($value);
				break;
			case 10:
				$this->setAgentId($value);
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
		$keys = EmailQueuePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMessage($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setReceipientEmail($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setSubject($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setReceipientName($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setRefId($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setEmailStatusId($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setEmailType($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setCutomerId($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setAgentId($arr[$keys[10]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(EmailQueuePeer::DATABASE_NAME);

		if ($this->isColumnModified(EmailQueuePeer::ID)) $criteria->add(EmailQueuePeer::ID, $this->id);
		if ($this->isColumnModified(EmailQueuePeer::MESSAGE)) $criteria->add(EmailQueuePeer::MESSAGE, $this->message);
		if ($this->isColumnModified(EmailQueuePeer::RECEIPIENT_EMAIL)) $criteria->add(EmailQueuePeer::RECEIPIENT_EMAIL, $this->receipient_email);
		if ($this->isColumnModified(EmailQueuePeer::SUBJECT)) $criteria->add(EmailQueuePeer::SUBJECT, $this->subject);
		if ($this->isColumnModified(EmailQueuePeer::CREATED_AT)) $criteria->add(EmailQueuePeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(EmailQueuePeer::RECEIPIENT_NAME)) $criteria->add(EmailQueuePeer::RECEIPIENT_NAME, $this->receipient_name);
		if ($this->isColumnModified(EmailQueuePeer::REF_ID)) $criteria->add(EmailQueuePeer::REF_ID, $this->ref_id);
		if ($this->isColumnModified(EmailQueuePeer::EMAIL_STATUS_ID)) $criteria->add(EmailQueuePeer::EMAIL_STATUS_ID, $this->email_status_id);
		if ($this->isColumnModified(EmailQueuePeer::EMAIL_TYPE)) $criteria->add(EmailQueuePeer::EMAIL_TYPE, $this->email_type);
		if ($this->isColumnModified(EmailQueuePeer::CUTOMER_ID)) $criteria->add(EmailQueuePeer::CUTOMER_ID, $this->cutomer_id);
		if ($this->isColumnModified(EmailQueuePeer::AGENT_ID)) $criteria->add(EmailQueuePeer::AGENT_ID, $this->agent_id);

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
		$criteria = new Criteria(EmailQueuePeer::DATABASE_NAME);

		$criteria->add(EmailQueuePeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     string
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      string $key Primary key.
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
	 * @param      object $copyObj An object of EmailQueue (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setMessage($this->message);

		$copyObj->setReceipientEmail($this->receipient_email);

		$copyObj->setSubject($this->subject);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setReceipientName($this->receipient_name);

		$copyObj->setRefId($this->ref_id);

		$copyObj->setEmailStatusId($this->email_status_id);

		$copyObj->setEmailType($this->email_type);

		$copyObj->setCutomerId($this->cutomer_id);

		$copyObj->setAgentId($this->agent_id);


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
	 * @return     EmailQueue Clone of current object.
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
	 * @return     EmailQueuePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new EmailQueuePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Status object.
	 *
	 * @param      Status $v
	 * @return     EmailQueue The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setStatus(Status $v = null)
	{
		if ($v === null) {
			$this->setEmailStatusId(1);
		} else {
			$this->setEmailStatusId($v->getId());
		}

		$this->aStatus = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Status object, it will not be re-added.
		if ($v !== null) {
			$v->addEmailQueue($this);
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
	public function getStatus(PropelPDO $con = null)
	{
		if ($this->aStatus === null && ($this->email_status_id !== null)) {
			$c = new Criteria(StatusPeer::DATABASE_NAME);
			$c->add(StatusPeer::ID, $this->email_status_id);
			$this->aStatus = StatusPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aStatus->addEmailQueues($this);
			 */
		}
		return $this->aStatus;
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

			$this->aStatus = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseEmailQueue:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseEmailQueue::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseEmailQueue
