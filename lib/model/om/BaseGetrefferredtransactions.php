<?php

/**
 * Base class that represents a row from the 'getrefferredtransactions' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 12:45:36
 *
 * @package    lib.model.om
 */
abstract class BaseGetrefferredtransactions extends BaseObject  implements Persistent {


  const PEER = 'GetrefferredtransactionsPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        GetrefferredtransactionsPeer
	 */
	protected static $peer;

	/**
	 * The value for the transaction_id field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $transaction_id;

	/**
	 * The value for the customer_order_id field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $customer_order_id;

	/**
	 * The value for the amount field.
	 * @var        string
	 */
	protected $amount;

	/**
	 * The value for the customer_id field.
	 * @var        string
	 */
	protected $customer_id;

	/**
	 * The value for the is_first_order field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $is_first_order;

	/**
	 * The value for the registration_earning field.
	 * @var        double
	 */
	protected $registration_earning;

	/**
	 * The value for the extra_refills_earning field.
	 * @var        double
	 */
	protected $extra_refills_earning;

	/**
	 * The value for the created_at field.
	 * Note: this column has a database default value of: NULL
	 * @var        string
	 */
	protected $created_at;

	/**
	 * The value for the referrer_id field.
	 * @var        int
	 */
	protected $referrer_id;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the first_name field.
	 * @var        string
	 */
	protected $first_name;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

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
	 * Initializes internal state of BaseGetrefferredtransactions object.
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
		$this->transaction_id = '0';
		$this->customer_order_id = '0';
		$this->is_first_order = false;
		$this->created_at = NULL;
	}

	/**
	 * Get the [transaction_id] column value.
	 * 
	 * @return     string
	 */
	public function getTransactionId()
	{
		return $this->transaction_id;
	}

	/**
	 * Get the [customer_order_id] column value.
	 * 
	 * @return     string
	 */
	public function getCustomerOrderId()
	{
		return $this->customer_order_id;
	}

	/**
	 * Get the [amount] column value.
	 * 
	 * @return     string
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Get the [customer_id] column value.
	 * 
	 * @return     string
	 */
	public function getCustomerId()
	{
		return $this->customer_id;
	}

	/**
	 * Get the [is_first_order] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsFirstOrder()
	{
		return $this->is_first_order;
	}

	/**
	 * Get the [registration_earning] column value.
	 * 
	 * @return     double
	 */
	public function getRegistrationEarning()
	{
		return $this->registration_earning;
	}

	/**
	 * Get the [extra_refills_earning] column value.
	 * 
	 * @return     double
	 */
	public function getExtraRefillsEarning()
	{
		return $this->extra_refills_earning;
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
	 * Get the [referrer_id] column value.
	 * 
	 * @return     int
	 */
	public function getReferrerId()
	{
		return $this->referrer_id;
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
	 * Get the [first_name] column value.
	 * 
	 * @return     string
	 */
	public function getFirstName()
	{
		return $this->first_name;
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
	 * Set the value of [transaction_id] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setTransactionId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->transaction_id !== $v || $v === '0') {
			$this->transaction_id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::TRANSACTION_ID;
		}

		return $this;
	} // setTransactionId()

	/**
	 * Set the value of [customer_order_id] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setCustomerOrderId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->customer_order_id !== $v || $v === '0') {
			$this->customer_order_id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::CUSTOMER_ORDER_ID;
		}

		return $this;
	} // setCustomerOrderId()

	/**
	 * Set the value of [amount] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setAmount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->amount !== $v) {
			$this->amount = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::AMOUNT;
		}

		return $this;
	} // setAmount()

	/**
	 * Set the value of [customer_id] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setCustomerId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->customer_id !== $v) {
			$this->customer_id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::CUSTOMER_ID;
		}

		return $this;
	} // setCustomerId()

	/**
	 * Set the value of [is_first_order] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setIsFirstOrder($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_first_order !== $v || $v === false) {
			$this->is_first_order = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::IS_FIRST_ORDER;
		}

		return $this;
	} // setIsFirstOrder()

	/**
	 * Set the value of [registration_earning] column.
	 * 
	 * @param      double $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setRegistrationEarning($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->registration_earning !== $v) {
			$this->registration_earning = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::REGISTRATION_EARNING;
		}

		return $this;
	} // setRegistrationEarning()

	/**
	 * Set the value of [extra_refills_earning] column.
	 * 
	 * @param      double $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setExtraRefillsEarning($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->extra_refills_earning !== $v) {
			$this->extra_refills_earning = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::EXTRA_REFILLS_EARNING;
		}

		return $this;
	} // setExtraRefillsEarning()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Getrefferredtransactions The current object (for fluent API support)
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
					|| ($dt->format('Y-m-d H:i:s') === NULL) // or the entered value matches the default
					)
			{
				$this->created_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = GetrefferredtransactionsPeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

	/**
	 * Set the value of [referrer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setReferrerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->referrer_id !== $v) {
			$this->referrer_id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::REFERRER_ID;
		}

		return $this;
	} // setReferrerId()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Set the value of [first_name] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setFirstName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->first_name !== $v) {
			$this->first_name = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::FIRST_NAME;
		}

		return $this;
	} // setFirstName()

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Getrefferredtransactions The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionsPeer::ID;
		}

		return $this;
	} // setId()

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
			if (array_diff($this->modifiedColumns, array(GetrefferredtransactionsPeer::TRANSACTION_ID,GetrefferredtransactionsPeer::CUSTOMER_ORDER_ID,GetrefferredtransactionsPeer::IS_FIRST_ORDER,GetrefferredtransactionsPeer::CREATED_AT))) {
				return false;
			}

			if ($this->transaction_id !== '0') {
				return false;
			}

			if ($this->customer_order_id !== '0') {
				return false;
			}

			if ($this->is_first_order !== false) {
				return false;
			}

			if ($this->created_at !== NULL) {
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

			$this->transaction_id = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
			$this->customer_order_id = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->amount = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->customer_id = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->is_first_order = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->registration_earning = ($row[$startcol + 5] !== null) ? (double) $row[$startcol + 5] : null;
			$this->extra_refills_earning = ($row[$startcol + 6] !== null) ? (double) $row[$startcol + 6] : null;
			$this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->referrer_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->name = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->first_name = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 12; // 12 = GetrefferredtransactionsPeer::NUM_COLUMNS - GetrefferredtransactionsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Getrefferredtransactions object", $e);
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
			$con = Propel::getConnection(GetrefferredtransactionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = GetrefferredtransactionsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

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

    foreach (sfMixer::getCallables('BaseGetrefferredtransactions:delete:pre') as $callable)
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
			$con = Propel::getConnection(GetrefferredtransactionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			GetrefferredtransactionsPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseGetrefferredtransactions:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseGetrefferredtransactions:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(GetrefferredtransactionsPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(GetrefferredtransactionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseGetrefferredtransactions:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			GetrefferredtransactionsPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = GetrefferredtransactionsPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = GetrefferredtransactionsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += GetrefferredtransactionsPeer::doUpdate($this, $con);
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


			if (($retval = GetrefferredtransactionsPeer::doValidate($this, $columns)) !== true) {
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
		$pos = GetrefferredtransactionsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getTransactionId();
				break;
			case 1:
				return $this->getCustomerOrderId();
				break;
			case 2:
				return $this->getAmount();
				break;
			case 3:
				return $this->getCustomerId();
				break;
			case 4:
				return $this->getIsFirstOrder();
				break;
			case 5:
				return $this->getRegistrationEarning();
				break;
			case 6:
				return $this->getExtraRefillsEarning();
				break;
			case 7:
				return $this->getCreatedAt();
				break;
			case 8:
				return $this->getReferrerId();
				break;
			case 9:
				return $this->getName();
				break;
			case 10:
				return $this->getFirstName();
				break;
			case 11:
				return $this->getId();
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
		$keys = GetrefferredtransactionsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getTransactionId(),
			$keys[1] => $this->getCustomerOrderId(),
			$keys[2] => $this->getAmount(),
			$keys[3] => $this->getCustomerId(),
			$keys[4] => $this->getIsFirstOrder(),
			$keys[5] => $this->getRegistrationEarning(),
			$keys[6] => $this->getExtraRefillsEarning(),
			$keys[7] => $this->getCreatedAt(),
			$keys[8] => $this->getReferrerId(),
			$keys[9] => $this->getName(),
			$keys[10] => $this->getFirstName(),
			$keys[11] => $this->getId(),
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
		$pos = GetrefferredtransactionsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setTransactionId($value);
				break;
			case 1:
				$this->setCustomerOrderId($value);
				break;
			case 2:
				$this->setAmount($value);
				break;
			case 3:
				$this->setCustomerId($value);
				break;
			case 4:
				$this->setIsFirstOrder($value);
				break;
			case 5:
				$this->setRegistrationEarning($value);
				break;
			case 6:
				$this->setExtraRefillsEarning($value);
				break;
			case 7:
				$this->setCreatedAt($value);
				break;
			case 8:
				$this->setReferrerId($value);
				break;
			case 9:
				$this->setName($value);
				break;
			case 10:
				$this->setFirstName($value);
				break;
			case 11:
				$this->setId($value);
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
		$keys = GetrefferredtransactionsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setTransactionId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCustomerOrderId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setAmount($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCustomerId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setIsFirstOrder($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setRegistrationEarning($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setExtraRefillsEarning($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setReferrerId($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setName($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setFirstName($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setId($arr[$keys[11]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(GetrefferredtransactionsPeer::DATABASE_NAME);

		if ($this->isColumnModified(GetrefferredtransactionsPeer::TRANSACTION_ID)) $criteria->add(GetrefferredtransactionsPeer::TRANSACTION_ID, $this->transaction_id);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::CUSTOMER_ORDER_ID)) $criteria->add(GetrefferredtransactionsPeer::CUSTOMER_ORDER_ID, $this->customer_order_id);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::AMOUNT)) $criteria->add(GetrefferredtransactionsPeer::AMOUNT, $this->amount);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::CUSTOMER_ID)) $criteria->add(GetrefferredtransactionsPeer::CUSTOMER_ID, $this->customer_id);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::IS_FIRST_ORDER)) $criteria->add(GetrefferredtransactionsPeer::IS_FIRST_ORDER, $this->is_first_order);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::REGISTRATION_EARNING)) $criteria->add(GetrefferredtransactionsPeer::REGISTRATION_EARNING, $this->registration_earning);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::EXTRA_REFILLS_EARNING)) $criteria->add(GetrefferredtransactionsPeer::EXTRA_REFILLS_EARNING, $this->extra_refills_earning);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::CREATED_AT)) $criteria->add(GetrefferredtransactionsPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::REFERRER_ID)) $criteria->add(GetrefferredtransactionsPeer::REFERRER_ID, $this->referrer_id);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::NAME)) $criteria->add(GetrefferredtransactionsPeer::NAME, $this->name);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::FIRST_NAME)) $criteria->add(GetrefferredtransactionsPeer::FIRST_NAME, $this->first_name);
		if ($this->isColumnModified(GetrefferredtransactionsPeer::ID)) $criteria->add(GetrefferredtransactionsPeer::ID, $this->id);

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
		$criteria = new Criteria(GetrefferredtransactionsPeer::DATABASE_NAME);

		$criteria->add(GetrefferredtransactionsPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Getrefferredtransactions (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setTransactionId($this->transaction_id);

		$copyObj->setCustomerOrderId($this->customer_order_id);

		$copyObj->setAmount($this->amount);

		$copyObj->setCustomerId($this->customer_id);

		$copyObj->setIsFirstOrder($this->is_first_order);

		$copyObj->setRegistrationEarning($this->registration_earning);

		$copyObj->setExtraRefillsEarning($this->extra_refills_earning);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setReferrerId($this->referrer_id);

		$copyObj->setName($this->name);

		$copyObj->setFirstName($this->first_name);


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
	 * @return     Getrefferredtransactions Clone of current object.
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
	 * @return     GetrefferredtransactionsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new GetrefferredtransactionsPeer();
		}
		return self::$peer;
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

	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseGetrefferredtransactions:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseGetrefferredtransactions::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseGetrefferredtransactions
