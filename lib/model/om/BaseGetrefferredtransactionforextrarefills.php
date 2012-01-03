<?php

/**
 * Base class that represents a row from the 'getrefferredtransactionforextrarefills' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 10:31:39
 *
 * @package    lib.model.om
 */
abstract class BaseGetrefferredtransactionforextrarefills extends BaseObject  implements Persistent {


  const PEER = 'GetrefferredtransactionforextrarefillsPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        GetrefferredtransactionforextrarefillsPeer
	 */
	protected static $peer;

	/**
	 * The value for the amount field.
	 * @var        string
	 */
	protected $amount;

	/**
	 * The value for the amount_cur_month field.
	 * @var        string
	 */
	protected $amount_cur_month;

	/**
	 * The value for the customer_id field.
	 * @var        string
	 */
	protected $customer_id;

	/**
	 * The value for the referrer_id field.
	 * @var        int
	 */
	protected $referrer_id;

	/**
	 * The value for the ere_todate field.
	 * @var        double
	 */
	protected $ere_todate;

	/**
	 * The value for the ere_cur_month field.
	 * @var        double
	 */
	protected $ere_cur_month;

	/**
	 * The value for the total_transactions field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $total_transactions;

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
	 * Initializes internal state of BaseGetrefferredtransactionforextrarefills object.
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
		$this->total_transactions = '0';
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
	 * Get the [amount_cur_month] column value.
	 * 
	 * @return     string
	 */
	public function getAmountCurMonth()
	{
		return $this->amount_cur_month;
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
	 * Get the [referrer_id] column value.
	 * 
	 * @return     int
	 */
	public function getReferrerId()
	{
		return $this->referrer_id;
	}

	/**
	 * Get the [ere_todate] column value.
	 * 
	 * @return     double
	 */
	public function getEreTodate()
	{
		return $this->ere_todate;
	}

	/**
	 * Get the [ere_cur_month] column value.
	 * 
	 * @return     double
	 */
	public function getEreCurMonth()
	{
		return $this->ere_cur_month;
	}

	/**
	 * Get the [total_transactions] column value.
	 * 
	 * @return     string
	 */
	public function getTotalTransactions()
	{
		return $this->total_transactions;
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
	 * Set the value of [amount] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setAmount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->amount !== $v) {
			$this->amount = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::AMOUNT;
		}

		return $this;
	} // setAmount()

	/**
	 * Set the value of [amount_cur_month] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setAmountCurMonth($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->amount_cur_month !== $v) {
			$this->amount_cur_month = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::AMOUNT_CUR_MONTH;
		}

		return $this;
	} // setAmountCurMonth()

	/**
	 * Set the value of [customer_id] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setCustomerId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->customer_id !== $v) {
			$this->customer_id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::CUSTOMER_ID;
		}

		return $this;
	} // setCustomerId()

	/**
	 * Set the value of [referrer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setReferrerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->referrer_id !== $v) {
			$this->referrer_id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::REFERRER_ID;
		}

		return $this;
	} // setReferrerId()

	/**
	 * Set the value of [ere_todate] column.
	 * 
	 * @param      double $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setEreTodate($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->ere_todate !== $v) {
			$this->ere_todate = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::ERE_TODATE;
		}

		return $this;
	} // setEreTodate()

	/**
	 * Set the value of [ere_cur_month] column.
	 * 
	 * @param      double $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setEreCurMonth($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->ere_cur_month !== $v) {
			$this->ere_cur_month = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::ERE_CUR_MONTH;
		}

		return $this;
	} // setEreCurMonth()

	/**
	 * Set the value of [total_transactions] column.
	 * 
	 * @param      string $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setTotalTransactions($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->total_transactions !== $v || $v === '0') {
			$this->total_transactions = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::TOTAL_TRANSACTIONS;
		}

		return $this;
	} // setTotalTransactions()

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Getrefferredtransactionforextrarefills The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::ID;
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
			if (array_diff($this->modifiedColumns, array(GetrefferredtransactionforextrarefillsPeer::TOTAL_TRANSACTIONS))) {
				return false;
			}

			if ($this->total_transactions !== '0') {
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

			$this->amount = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
			$this->amount_cur_month = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->customer_id = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->referrer_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->ere_todate = ($row[$startcol + 4] !== null) ? (double) $row[$startcol + 4] : null;
			$this->ere_cur_month = ($row[$startcol + 5] !== null) ? (double) $row[$startcol + 5] : null;
			$this->total_transactions = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = GetrefferredtransactionforextrarefillsPeer::NUM_COLUMNS - GetrefferredtransactionforextrarefillsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Getrefferredtransactionforextrarefills object", $e);
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
			$con = Propel::getConnection(GetrefferredtransactionforextrarefillsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = GetrefferredtransactionforextrarefillsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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

    foreach (sfMixer::getCallables('BaseGetrefferredtransactionforextrarefills:delete:pre') as $callable)
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
			$con = Propel::getConnection(GetrefferredtransactionforextrarefillsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			GetrefferredtransactionforextrarefillsPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseGetrefferredtransactionforextrarefills:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseGetrefferredtransactionforextrarefills:save:pre') as $callable)
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
			$con = Propel::getConnection(GetrefferredtransactionforextrarefillsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseGetrefferredtransactionforextrarefills:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			GetrefferredtransactionforextrarefillsPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = GetrefferredtransactionforextrarefillsPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = GetrefferredtransactionforextrarefillsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += GetrefferredtransactionforextrarefillsPeer::doUpdate($this, $con);
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


			if (($retval = GetrefferredtransactionforextrarefillsPeer::doValidate($this, $columns)) !== true) {
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
		$pos = GetrefferredtransactionforextrarefillsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getAmount();
				break;
			case 1:
				return $this->getAmountCurMonth();
				break;
			case 2:
				return $this->getCustomerId();
				break;
			case 3:
				return $this->getReferrerId();
				break;
			case 4:
				return $this->getEreTodate();
				break;
			case 5:
				return $this->getEreCurMonth();
				break;
			case 6:
				return $this->getTotalTransactions();
				break;
			case 7:
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
		$keys = GetrefferredtransactionforextrarefillsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getAmount(),
			$keys[1] => $this->getAmountCurMonth(),
			$keys[2] => $this->getCustomerId(),
			$keys[3] => $this->getReferrerId(),
			$keys[4] => $this->getEreTodate(),
			$keys[5] => $this->getEreCurMonth(),
			$keys[6] => $this->getTotalTransactions(),
			$keys[7] => $this->getId(),
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
		$pos = GetrefferredtransactionforextrarefillsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setAmount($value);
				break;
			case 1:
				$this->setAmountCurMonth($value);
				break;
			case 2:
				$this->setCustomerId($value);
				break;
			case 3:
				$this->setReferrerId($value);
				break;
			case 4:
				$this->setEreTodate($value);
				break;
			case 5:
				$this->setEreCurMonth($value);
				break;
			case 6:
				$this->setTotalTransactions($value);
				break;
			case 7:
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
		$keys = GetrefferredtransactionforextrarefillsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setAmount($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setAmountCurMonth($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCustomerId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setReferrerId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setEreTodate($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setEreCurMonth($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setTotalTransactions($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setId($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(GetrefferredtransactionforextrarefillsPeer::DATABASE_NAME);

		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::AMOUNT)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::AMOUNT, $this->amount);
		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::AMOUNT_CUR_MONTH)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::AMOUNT_CUR_MONTH, $this->amount_cur_month);
		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::CUSTOMER_ID)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::CUSTOMER_ID, $this->customer_id);
		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::REFERRER_ID)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::REFERRER_ID, $this->referrer_id);
		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::ERE_TODATE)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::ERE_TODATE, $this->ere_todate);
		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::ERE_CUR_MONTH)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::ERE_CUR_MONTH, $this->ere_cur_month);
		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::TOTAL_TRANSACTIONS)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::TOTAL_TRANSACTIONS, $this->total_transactions);
		if ($this->isColumnModified(GetrefferredtransactionforextrarefillsPeer::ID)) $criteria->add(GetrefferredtransactionforextrarefillsPeer::ID, $this->id);

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
		$criteria = new Criteria(GetrefferredtransactionforextrarefillsPeer::DATABASE_NAME);

		$criteria->add(GetrefferredtransactionforextrarefillsPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Getrefferredtransactionforextrarefills (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setAmount($this->amount);

		$copyObj->setAmountCurMonth($this->amount_cur_month);

		$copyObj->setCustomerId($this->customer_id);

		$copyObj->setReferrerId($this->referrer_id);

		$copyObj->setEreTodate($this->ere_todate);

		$copyObj->setEreCurMonth($this->ere_cur_month);

		$copyObj->setTotalTransactions($this->total_transactions);


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
	 * @return     Getrefferredtransactionforextrarefills Clone of current object.
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
	 * @return     GetrefferredtransactionforextrarefillsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new GetrefferredtransactionforextrarefillsPeer();
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
    if (!$callable = sfMixer::getCallable('BaseGetrefferredtransactionforextrarefills:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseGetrefferredtransactionforextrarefills::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseGetrefferredtransactionforextrarefills
