<?php

/**
 * Base static class for performing query and update operations on the 'billing_cdr_details' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 10:31:37
 *
 * @package    lib.model.om
 */
abstract class BaseBillingCdrDetailsPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'billing_cdr_details';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.BillingCdrDetails';

	/** The total number of columns. */
	const NUM_COLUMNS = 16;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID field */
	const ID = 'billing_cdr_details.ID';

	/** the column name for the CALL_TIME field */
	const CALL_TIME = 'billing_cdr_details.CALL_TIME';

	/** the column name for the FROM_COUNTRY field */
	const FROM_COUNTRY = 'billing_cdr_details.FROM_COUNTRY';

	/** the column name for the FROM_NUMBER field */
	const FROM_NUMBER = 'billing_cdr_details.FROM_NUMBER';

	/** the column name for the TO_NUMBER field */
	const TO_NUMBER = 'billing_cdr_details.TO_NUMBER';

	/** the column name for the DURATION field */
	const DURATION = 'billing_cdr_details.DURATION';

	/** the column name for the DURATION_SECOND field */
	const DURATION_SECOND = 'billing_cdr_details.DURATION_SECOND';

	/** the column name for the PURCHASE_PRICE field */
	const PURCHASE_PRICE = 'billing_cdr_details.PURCHASE_PRICE';

	/** the column name for the FONET_DESCRIPTION field */
	const FONET_DESCRIPTION = 'billing_cdr_details.FONET_DESCRIPTION';

	/** the column name for the BILLING_STATUS field */
	const BILLING_STATUS = 'billing_cdr_details.BILLING_STATUS';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'billing_cdr_details.CREATED_AT';

	/** the column name for the DURATION_MINUTES field */
	const DURATION_MINUTES = 'billing_cdr_details.DURATION_MINUTES';

	/** the column name for the EMPLOYEE_ID field */
	const EMPLOYEE_ID = 'billing_cdr_details.EMPLOYEE_ID';

	/** the column name for the CALL_RATE_TABLE_DESCRIPTION field */
	const CALL_RATE_TABLE_DESCRIPTION = 'billing_cdr_details.CALL_RATE_TABLE_DESCRIPTION';

	/** the column name for the CALL_RATE_TABLE_ID field */
	const CALL_RATE_TABLE_ID = 'billing_cdr_details.CALL_RATE_TABLE_ID';

	/** the column name for the PROCESSING_COMMENT field */
	const PROCESSING_COMMENT = 'billing_cdr_details.PROCESSING_COMMENT';

	/**
	 * An identiy map to hold any loaded instances of BillingCdrDetails objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array BillingCdrDetails[]
	 */
	public static $instances = array();

	/**
	 * The MapBuilder instance for this peer.
	 * @var        MapBuilder
	 */
	private static $mapBuilder = null;

	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'CallTime', 'FromCountry', 'FromNumber', 'ToNumber', 'Duration', 'DurationSecond', 'PurchasePrice', 'FonetDescription', 'BillingStatus', 'CreatedAt', 'DurationMinutes', 'EmployeeId', 'CallRateTableDescription', 'CallRateTableId', 'ProcessingComment', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'callTime', 'fromCountry', 'fromNumber', 'toNumber', 'duration', 'durationSecond', 'purchasePrice', 'fonetDescription', 'billingStatus', 'createdAt', 'durationMinutes', 'employeeId', 'callRateTableDescription', 'callRateTableId', 'processingComment', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::CALL_TIME, self::FROM_COUNTRY, self::FROM_NUMBER, self::TO_NUMBER, self::DURATION, self::DURATION_SECOND, self::PURCHASE_PRICE, self::FONET_DESCRIPTION, self::BILLING_STATUS, self::CREATED_AT, self::DURATION_MINUTES, self::EMPLOYEE_ID, self::CALL_RATE_TABLE_DESCRIPTION, self::CALL_RATE_TABLE_ID, self::PROCESSING_COMMENT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'call_time', 'from_country', 'from_number', 'to_number', 'duration', 'duration_second', 'purchase_price', 'fonet_description', 'billing_status', 'created_at', 'duration_minutes', 'employee_id', 'call_rate_table_description', 'call_rate_table_id', 'processing_comment', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'CallTime' => 1, 'FromCountry' => 2, 'FromNumber' => 3, 'ToNumber' => 4, 'Duration' => 5, 'DurationSecond' => 6, 'PurchasePrice' => 7, 'FonetDescription' => 8, 'BillingStatus' => 9, 'CreatedAt' => 10, 'DurationMinutes' => 11, 'EmployeeId' => 12, 'CallRateTableDescription' => 13, 'CallRateTableId' => 14, 'ProcessingComment' => 15, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'callTime' => 1, 'fromCountry' => 2, 'fromNumber' => 3, 'toNumber' => 4, 'duration' => 5, 'durationSecond' => 6, 'purchasePrice' => 7, 'fonetDescription' => 8, 'billingStatus' => 9, 'createdAt' => 10, 'durationMinutes' => 11, 'employeeId' => 12, 'callRateTableDescription' => 13, 'callRateTableId' => 14, 'processingComment' => 15, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::CALL_TIME => 1, self::FROM_COUNTRY => 2, self::FROM_NUMBER => 3, self::TO_NUMBER => 4, self::DURATION => 5, self::DURATION_SECOND => 6, self::PURCHASE_PRICE => 7, self::FONET_DESCRIPTION => 8, self::BILLING_STATUS => 9, self::CREATED_AT => 10, self::DURATION_MINUTES => 11, self::EMPLOYEE_ID => 12, self::CALL_RATE_TABLE_DESCRIPTION => 13, self::CALL_RATE_TABLE_ID => 14, self::PROCESSING_COMMENT => 15, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'call_time' => 1, 'from_country' => 2, 'from_number' => 3, 'to_number' => 4, 'duration' => 5, 'duration_second' => 6, 'purchase_price' => 7, 'fonet_description' => 8, 'billing_status' => 9, 'created_at' => 10, 'duration_minutes' => 11, 'employee_id' => 12, 'call_rate_table_description' => 13, 'call_rate_table_id' => 14, 'processing_comment' => 15, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
	);

	/**
	 * Get a (singleton) instance of the MapBuilder for this peer class.
	 * @return     MapBuilder The map builder for this peer
	 */
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new BillingCdrDetailsMapBuilder();
		}
		return self::$mapBuilder;
	}
	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. BillingCdrDetailsPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(BillingCdrDetailsPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(BillingCdrDetailsPeer::ID);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::CALL_TIME);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::FROM_COUNTRY);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::FROM_NUMBER);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::TO_NUMBER);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::DURATION);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::DURATION_SECOND);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::PURCHASE_PRICE);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::FONET_DESCRIPTION);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::BILLING_STATUS);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::CREATED_AT);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::DURATION_MINUTES);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::EMPLOYEE_ID);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::CALL_RATE_TABLE_DESCRIPTION);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::CALL_RATE_TABLE_ID);

		$criteria->addSelectColumn(BillingCdrDetailsPeer::PROCESSING_COMMENT);

	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(BillingCdrDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			BillingCdrDetailsPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}


    foreach (sfMixer::getCallables('BaseBillingCdrDetailsPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseBillingCdrDetailsPeer', $criteria, $con);
    }


		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     BillingCdrDetails
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = BillingCdrDetailsPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return BillingCdrDetailsPeer::populateObjects(BillingCdrDetailsPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseBillingCdrDetailsPeer:doSelectStmt:doSelectStmt') as $callable)
    {
      call_user_func($callable, 'BaseBillingCdrDetailsPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			BillingCdrDetailsPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      BillingCdrDetails $value A BillingCdrDetails object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(BillingCdrDetails $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getId();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A BillingCdrDetails object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof BillingCdrDetails) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or BillingCdrDetails object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     BillingCdrDetails Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol + 0] === null) {
			return null;
		}
		return (string) $row[$startcol + 0];
	}

	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = BillingCdrDetailsPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = BillingCdrDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = BillingCdrDetailsPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				BillingCdrDetailsPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

  static public function getUniqueColumnNames()
  {
    return array();
  }
	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * This uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass()
	{
		return BillingCdrDetailsPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a BillingCdrDetails or Criteria object.
	 *
	 * @param      mixed $values Criteria or BillingCdrDetails object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseBillingCdrDetailsPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBillingCdrDetailsPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from BillingCdrDetails object
		}

		if ($criteria->containsKey(BillingCdrDetailsPeer::ID) && $criteria->keyContainsValue(BillingCdrDetailsPeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.BillingCdrDetailsPeer::ID.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseBillingCdrDetailsPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBillingCdrDetailsPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a BillingCdrDetails or Criteria object.
	 *
	 * @param      mixed $values Criteria or BillingCdrDetails object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseBillingCdrDetailsPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBillingCdrDetailsPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(BillingCdrDetailsPeer::ID);
			$selectCriteria->add(BillingCdrDetailsPeer::ID, $criteria->remove(BillingCdrDetailsPeer::ID), $comparison);

		} else { // $values is BillingCdrDetails object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBillingCdrDetailsPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBillingCdrDetailsPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the billing_cdr_details table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(BillingCdrDetailsPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a BillingCdrDetails or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or BillingCdrDetails object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			BillingCdrDetailsPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof BillingCdrDetails) {
			// invalidate the cache for this single object
			BillingCdrDetailsPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BillingCdrDetailsPeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				BillingCdrDetailsPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);

			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given BillingCdrDetails object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      BillingCdrDetails $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(BillingCdrDetails $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(BillingCdrDetailsPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(BillingCdrDetailsPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(BillingCdrDetailsPeer::DATABASE_NAME, BillingCdrDetailsPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = BillingCdrDetailsPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     BillingCdrDetails
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = BillingCdrDetailsPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(BillingCdrDetailsPeer::DATABASE_NAME);
		$criteria->add(BillingCdrDetailsPeer::ID, $pk);

		$v = BillingCdrDetailsPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(BillingCdrDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(BillingCdrDetailsPeer::DATABASE_NAME);
			$criteria->add(BillingCdrDetailsPeer::ID, $pks, Criteria::IN);
			$objs = BillingCdrDetailsPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseBillingCdrDetailsPeer

// This is the static code needed to register the MapBuilder for this table with the main Propel class.
//
// NOTE: This static code cannot call methods on the BillingCdrDetailsPeer class, because it is not defined yet.
// If you need to use overridden methods, you can add this code to the bottom of the BillingCdrDetailsPeer class:
//
// Propel::getDatabaseMap(BillingCdrDetailsPeer::DATABASE_NAME)->addTableBuilder(BillingCdrDetailsPeer::TABLE_NAME, BillingCdrDetailsPeer::getMapBuilder());
//
// Doing so will effectively overwrite the registration below.

Propel::getDatabaseMap(BaseBillingCdrDetailsPeer::DATABASE_NAME)->addTableBuilder(BaseBillingCdrDetailsPeer::TABLE_NAME, BaseBillingCdrDetailsPeer::getMapBuilder());

