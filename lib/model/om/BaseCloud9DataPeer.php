<?php

/**
 * Base static class for performing query and update operations on the 'cloud9_data' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 10:31:37
 *
 * @package    lib.model.om
 */
abstract class BaseCloud9DataPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'cloud9_data';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.Cloud9Data';

	/** The total number of columns. */
	const NUM_COLUMNS = 20;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID field */
	const ID = 'cloud9_data.ID';

	/** the column name for the REQUEST_TYPE field */
	const REQUEST_TYPE = 'cloud9_data.REQUEST_TYPE';

	/** the column name for the C9_TIMESTAMP field */
	const C9_TIMESTAMP = 'cloud9_data.C9_TIMESTAMP';

	/** the column name for the TRANSACTION_ID field */
	const TRANSACTION_ID = 'cloud9_data.TRANSACTION_ID';

	/** the column name for the CALL_DATE field */
	const CALL_DATE = 'cloud9_data.CALL_DATE';

	/** the column name for the CDR field */
	const CDR = 'cloud9_data.CDR';

	/** the column name for the CID field */
	const CID = 'cloud9_data.CID';

	/** the column name for the MCC field */
	const MCC = 'cloud9_data.MCC';

	/** the column name for the MNC field */
	const MNC = 'cloud9_data.MNC';

	/** the column name for the IMSI field */
	const IMSI = 'cloud9_data.IMSI';

	/** the column name for the MSISDN field */
	const MSISDN = 'cloud9_data.MSISDN';

	/** the column name for the DESTINATION field */
	const DESTINATION = 'cloud9_data.DESTINATION';

	/** the column name for the LEG field */
	const LEG = 'cloud9_data.LEG';

	/** the column name for the LEG_DURATION field */
	const LEG_DURATION = 'cloud9_data.LEG_DURATION';

	/** the column name for the RESELLER_CHARGE field */
	const RESELLER_CHARGE = 'cloud9_data.RESELLER_CHARGE';

	/** the column name for the CLIENT_CHARGE field */
	const CLIENT_CHARGE = 'cloud9_data.CLIENT_CHARGE';

	/** the column name for the USER_CHARGE field */
	const USER_CHARGE = 'cloud9_data.USER_CHARGE';

	/** the column name for the IOT field */
	const IOT = 'cloud9_data.IOT';

	/** the column name for the USER_BALANCE field */
	const USER_BALANCE = 'cloud9_data.USER_BALANCE';

	/** the column name for the ECC field */
	const ECC = 'cloud9_data.ECC';

	/**
	 * An identiy map to hold any loaded instances of Cloud9Data objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array Cloud9Data[]
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
		BasePeer::TYPE_PHPNAME => array ('Id', 'RequestType', 'C9Timestamp', 'TransactionId', 'CallDate', 'Cdr', 'Cid', 'Mcc', 'Mnc', 'Imsi', 'Msisdn', 'Destination', 'Leg', 'LegDuration', 'ResellerCharge', 'ClientCharge', 'UserCharge', 'Iot', 'UserBalance', 'Ecc', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'requestType', 'c9Timestamp', 'transactionId', 'callDate', 'cdr', 'cid', 'mcc', 'mnc', 'imsi', 'msisdn', 'destination', 'leg', 'legDuration', 'resellerCharge', 'clientCharge', 'userCharge', 'iot', 'userBalance', 'ecc', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::REQUEST_TYPE, self::C9_TIMESTAMP, self::TRANSACTION_ID, self::CALL_DATE, self::CDR, self::CID, self::MCC, self::MNC, self::IMSI, self::MSISDN, self::DESTINATION, self::LEG, self::LEG_DURATION, self::RESELLER_CHARGE, self::CLIENT_CHARGE, self::USER_CHARGE, self::IOT, self::USER_BALANCE, self::ECC, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'request_type', 'c9_timestamp', 'transaction_id', 'call_date', 'cdr', 'cid', 'mcc', 'mnc', 'imsi', 'msisdn', 'destination', 'leg', 'leg_duration', 'reseller_charge', 'client_charge', 'user_charge', 'iot', 'user_balance', 'ecc', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'RequestType' => 1, 'C9Timestamp' => 2, 'TransactionId' => 3, 'CallDate' => 4, 'Cdr' => 5, 'Cid' => 6, 'Mcc' => 7, 'Mnc' => 8, 'Imsi' => 9, 'Msisdn' => 10, 'Destination' => 11, 'Leg' => 12, 'LegDuration' => 13, 'ResellerCharge' => 14, 'ClientCharge' => 15, 'UserCharge' => 16, 'Iot' => 17, 'UserBalance' => 18, 'Ecc' => 19, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'requestType' => 1, 'c9Timestamp' => 2, 'transactionId' => 3, 'callDate' => 4, 'cdr' => 5, 'cid' => 6, 'mcc' => 7, 'mnc' => 8, 'imsi' => 9, 'msisdn' => 10, 'destination' => 11, 'leg' => 12, 'legDuration' => 13, 'resellerCharge' => 14, 'clientCharge' => 15, 'userCharge' => 16, 'iot' => 17, 'userBalance' => 18, 'ecc' => 19, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::REQUEST_TYPE => 1, self::C9_TIMESTAMP => 2, self::TRANSACTION_ID => 3, self::CALL_DATE => 4, self::CDR => 5, self::CID => 6, self::MCC => 7, self::MNC => 8, self::IMSI => 9, self::MSISDN => 10, self::DESTINATION => 11, self::LEG => 12, self::LEG_DURATION => 13, self::RESELLER_CHARGE => 14, self::CLIENT_CHARGE => 15, self::USER_CHARGE => 16, self::IOT => 17, self::USER_BALANCE => 18, self::ECC => 19, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'request_type' => 1, 'c9_timestamp' => 2, 'transaction_id' => 3, 'call_date' => 4, 'cdr' => 5, 'cid' => 6, 'mcc' => 7, 'mnc' => 8, 'imsi' => 9, 'msisdn' => 10, 'destination' => 11, 'leg' => 12, 'leg_duration' => 13, 'reseller_charge' => 14, 'client_charge' => 15, 'user_charge' => 16, 'iot' => 17, 'user_balance' => 18, 'ecc' => 19, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, )
	);

	/**
	 * Get a (singleton) instance of the MapBuilder for this peer class.
	 * @return     MapBuilder The map builder for this peer
	 */
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new Cloud9DataMapBuilder();
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
	 * @param      string $column The column name for current table. (i.e. Cloud9DataPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(Cloud9DataPeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(Cloud9DataPeer::ID);

		$criteria->addSelectColumn(Cloud9DataPeer::REQUEST_TYPE);

		$criteria->addSelectColumn(Cloud9DataPeer::C9_TIMESTAMP);

		$criteria->addSelectColumn(Cloud9DataPeer::TRANSACTION_ID);

		$criteria->addSelectColumn(Cloud9DataPeer::CALL_DATE);

		$criteria->addSelectColumn(Cloud9DataPeer::CDR);

		$criteria->addSelectColumn(Cloud9DataPeer::CID);

		$criteria->addSelectColumn(Cloud9DataPeer::MCC);

		$criteria->addSelectColumn(Cloud9DataPeer::MNC);

		$criteria->addSelectColumn(Cloud9DataPeer::IMSI);

		$criteria->addSelectColumn(Cloud9DataPeer::MSISDN);

		$criteria->addSelectColumn(Cloud9DataPeer::DESTINATION);

		$criteria->addSelectColumn(Cloud9DataPeer::LEG);

		$criteria->addSelectColumn(Cloud9DataPeer::LEG_DURATION);

		$criteria->addSelectColumn(Cloud9DataPeer::RESELLER_CHARGE);

		$criteria->addSelectColumn(Cloud9DataPeer::CLIENT_CHARGE);

		$criteria->addSelectColumn(Cloud9DataPeer::USER_CHARGE);

		$criteria->addSelectColumn(Cloud9DataPeer::IOT);

		$criteria->addSelectColumn(Cloud9DataPeer::USER_BALANCE);

		$criteria->addSelectColumn(Cloud9DataPeer::ECC);

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
		$criteria->setPrimaryTableName(Cloud9DataPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			Cloud9DataPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}


    foreach (sfMixer::getCallables('BaseCloud9DataPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseCloud9DataPeer', $criteria, $con);
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
	 * @return     Cloud9Data
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = Cloud9DataPeer::doSelect($critcopy, $con);
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
		return Cloud9DataPeer::populateObjects(Cloud9DataPeer::doSelectStmt($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseCloud9DataPeer:doSelectStmt:doSelectStmt') as $callable)
    {
      call_user_func($callable, 'BaseCloud9DataPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			Cloud9DataPeer::addSelectColumns($criteria);
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
	 * @param      Cloud9Data $value A Cloud9Data object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(Cloud9Data $obj, $key = null)
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
	 * @param      mixed $value A Cloud9Data object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof Cloud9Data) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Cloud9Data object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     Cloud9Data Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
		$cls = Cloud9DataPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = Cloud9DataPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = Cloud9DataPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				Cloud9DataPeer::addInstanceToPool($obj, $key);
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
		return Cloud9DataPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a Cloud9Data or Criteria object.
	 *
	 * @param      mixed $values Criteria or Cloud9Data object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseCloud9DataPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseCloud9DataPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from Cloud9Data object
		}

		if ($criteria->containsKey(Cloud9DataPeer::ID) && $criteria->keyContainsValue(Cloud9DataPeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.Cloud9DataPeer::ID.')');
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

		
    foreach (sfMixer::getCallables('BaseCloud9DataPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseCloud9DataPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a Cloud9Data or Criteria object.
	 *
	 * @param      mixed $values Criteria or Cloud9Data object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseCloud9DataPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseCloud9DataPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(Cloud9DataPeer::ID);
			$selectCriteria->add(Cloud9DataPeer::ID, $criteria->remove(Cloud9DataPeer::ID), $comparison);

		} else { // $values is Cloud9Data object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseCloud9DataPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseCloud9DataPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the cloud9_data table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(Cloud9DataPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a Cloud9Data or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or Cloud9Data object or primary key or array of primary keys
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
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			Cloud9DataPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof Cloud9Data) {
			// invalidate the cache for this single object
			Cloud9DataPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(Cloud9DataPeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				Cloud9DataPeer::removeInstanceFromPool($singleval);
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
	 * Validates all modified columns of given Cloud9Data object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      Cloud9Data $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(Cloud9Data $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(Cloud9DataPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(Cloud9DataPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(Cloud9DataPeer::DATABASE_NAME, Cloud9DataPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = Cloud9DataPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     Cloud9Data
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = Cloud9DataPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(Cloud9DataPeer::DATABASE_NAME);
		$criteria->add(Cloud9DataPeer::ID, $pk);

		$v = Cloud9DataPeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(Cloud9DataPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(Cloud9DataPeer::DATABASE_NAME);
			$criteria->add(Cloud9DataPeer::ID, $pks, Criteria::IN);
			$objs = Cloud9DataPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseCloud9DataPeer

// This is the static code needed to register the MapBuilder for this table with the main Propel class.
//
// NOTE: This static code cannot call methods on the Cloud9DataPeer class, because it is not defined yet.
// If you need to use overridden methods, you can add this code to the bottom of the Cloud9DataPeer class:
//
// Propel::getDatabaseMap(Cloud9DataPeer::DATABASE_NAME)->addTableBuilder(Cloud9DataPeer::TABLE_NAME, Cloud9DataPeer::getMapBuilder());
//
// Doing so will effectively overwrite the registration below.

Propel::getDatabaseMap(BaseCloud9DataPeer::DATABASE_NAME)->addTableBuilder(BaseCloud9DataPeer::TABLE_NAME, BaseCloud9DataPeer::getMapBuilder());

