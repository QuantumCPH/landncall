<?php

/**
 * Base static class for performing query and update operations on the 'email_queue' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 09:22:03
 *
 * @package    lib.model.om
 */
abstract class BaseEmailQueuePeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'email_queue';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.EmailQueue';

	/** The total number of columns. */
	const NUM_COLUMNS = 11;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID field */
	const ID = 'email_queue.ID';

	/** the column name for the MESSAGE field */
	const MESSAGE = 'email_queue.MESSAGE';

	/** the column name for the RECEIPIENT_EMAIL field */
	const RECEIPIENT_EMAIL = 'email_queue.RECEIPIENT_EMAIL';

	/** the column name for the SUBJECT field */
	const SUBJECT = 'email_queue.SUBJECT';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'email_queue.CREATED_AT';

	/** the column name for the RECEIPIENT_NAME field */
	const RECEIPIENT_NAME = 'email_queue.RECEIPIENT_NAME';

	/** the column name for the REF_ID field */
	const REF_ID = 'email_queue.REF_ID';

	/** the column name for the EMAIL_STATUS_ID field */
	const EMAIL_STATUS_ID = 'email_queue.EMAIL_STATUS_ID';

	/** the column name for the EMAIL_TYPE field */
	const EMAIL_TYPE = 'email_queue.EMAIL_TYPE';

	/** the column name for the CUTOMER_ID field */
	const CUTOMER_ID = 'email_queue.CUTOMER_ID';

	/** the column name for the AGENT_ID field */
	const AGENT_ID = 'email_queue.AGENT_ID';

	/**
	 * An identiy map to hold any loaded instances of EmailQueue objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array EmailQueue[]
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
		BasePeer::TYPE_PHPNAME => array ('Id', 'Message', 'ReceipientEmail', 'Subject', 'CreatedAt', 'ReceipientName', 'RefId', 'EmailStatusId', 'EmailType', 'CutomerId', 'AgentId', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'message', 'receipientEmail', 'subject', 'createdAt', 'receipientName', 'refId', 'emailStatusId', 'emailType', 'cutomerId', 'agentId', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::MESSAGE, self::RECEIPIENT_EMAIL, self::SUBJECT, self::CREATED_AT, self::RECEIPIENT_NAME, self::REF_ID, self::EMAIL_STATUS_ID, self::EMAIL_TYPE, self::CUTOMER_ID, self::AGENT_ID, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'message', 'receipient_email', 'subject', 'created_at', 'receipient_name', 'ref_id', 'email_status_id', 'email_type', 'cutomer_id', 'agent_id', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Message' => 1, 'ReceipientEmail' => 2, 'Subject' => 3, 'CreatedAt' => 4, 'ReceipientName' => 5, 'RefId' => 6, 'EmailStatusId' => 7, 'EmailType' => 8, 'CutomerId' => 9, 'AgentId' => 10, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'message' => 1, 'receipientEmail' => 2, 'subject' => 3, 'createdAt' => 4, 'receipientName' => 5, 'refId' => 6, 'emailStatusId' => 7, 'emailType' => 8, 'cutomerId' => 9, 'agentId' => 10, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::MESSAGE => 1, self::RECEIPIENT_EMAIL => 2, self::SUBJECT => 3, self::CREATED_AT => 4, self::RECEIPIENT_NAME => 5, self::REF_ID => 6, self::EMAIL_STATUS_ID => 7, self::EMAIL_TYPE => 8, self::CUTOMER_ID => 9, self::AGENT_ID => 10, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'message' => 1, 'receipient_email' => 2, 'subject' => 3, 'created_at' => 4, 'receipient_name' => 5, 'ref_id' => 6, 'email_status_id' => 7, 'email_type' => 8, 'cutomer_id' => 9, 'agent_id' => 10, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
	);

	/**
	 * Get a (singleton) instance of the MapBuilder for this peer class.
	 * @return     MapBuilder The map builder for this peer
	 */
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new EmailQueueMapBuilder();
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
	 * @param      string $column The column name for current table. (i.e. EmailQueuePeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(EmailQueuePeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(EmailQueuePeer::ID);

		$criteria->addSelectColumn(EmailQueuePeer::MESSAGE);

		$criteria->addSelectColumn(EmailQueuePeer::RECEIPIENT_EMAIL);

		$criteria->addSelectColumn(EmailQueuePeer::SUBJECT);

		$criteria->addSelectColumn(EmailQueuePeer::CREATED_AT);

		$criteria->addSelectColumn(EmailQueuePeer::RECEIPIENT_NAME);

		$criteria->addSelectColumn(EmailQueuePeer::REF_ID);

		$criteria->addSelectColumn(EmailQueuePeer::EMAIL_STATUS_ID);

		$criteria->addSelectColumn(EmailQueuePeer::EMAIL_TYPE);

		$criteria->addSelectColumn(EmailQueuePeer::CUTOMER_ID);

		$criteria->addSelectColumn(EmailQueuePeer::AGENT_ID);

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
		$criteria->setPrimaryTableName(EmailQueuePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			EmailQueuePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}


    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $criteria, $con);
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
	 * @return     EmailQueue
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = EmailQueuePeer::doSelect($critcopy, $con);
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
		return EmailQueuePeer::populateObjects(EmailQueuePeer::doSelectStmt($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doSelectStmt:doSelectStmt') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			EmailQueuePeer::addSelectColumns($criteria);
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
	 * @param      EmailQueue $value A EmailQueue object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(EmailQueue $obj, $key = null)
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
	 * @param      mixed $value A EmailQueue object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof EmailQueue) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or EmailQueue object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     EmailQueue Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
		$cls = EmailQueuePeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = EmailQueuePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = EmailQueuePeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				EmailQueuePeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related Status table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinStatus(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(EmailQueuePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			EmailQueuePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(EmailQueuePeer::EMAIL_STATUS_ID,), array(StatusPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $criteria, $con);
    }


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
	 * Selects a collection of EmailQueue objects pre-filled with their Status objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of EmailQueue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinStatus(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doSelectJoin:doSelectJoin') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		EmailQueuePeer::addSelectColumns($c);
		$startcol = (EmailQueuePeer::NUM_COLUMNS - EmailQueuePeer::NUM_LAZY_LOAD_COLUMNS);
		StatusPeer::addSelectColumns($c);

		$c->addJoin(array(EmailQueuePeer::EMAIL_STATUS_ID,), array(StatusPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = EmailQueuePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = EmailQueuePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = EmailQueuePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				EmailQueuePeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = StatusPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = StatusPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					StatusPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (EmailQueue) to $obj2 (Status)
				$obj2->addEmailQueue($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(EmailQueuePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			EmailQueuePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(EmailQueuePeer::EMAIL_STATUS_ID,), array(StatusPeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $criteria, $con);
    }


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
	 * Selects a collection of EmailQueue objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of EmailQueue objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doSelectJoinAll:doSelectJoinAll') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		EmailQueuePeer::addSelectColumns($c);
		$startcol2 = (EmailQueuePeer::NUM_COLUMNS - EmailQueuePeer::NUM_LAZY_LOAD_COLUMNS);

		StatusPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (StatusPeer::NUM_COLUMNS - StatusPeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(EmailQueuePeer::EMAIL_STATUS_ID,), array(StatusPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = EmailQueuePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = EmailQueuePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = EmailQueuePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				EmailQueuePeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined Status rows

			$key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = StatusPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = StatusPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					StatusPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (EmailQueue) to the collection in $obj2 (Status)
				$obj2->addEmailQueue($obj1);
			} // if joined row not null

			$results[] = $obj1;
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
		return EmailQueuePeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a EmailQueue or Criteria object.
	 *
	 * @param      mixed $values Criteria or EmailQueue object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseEmailQueuePeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from EmailQueue object
		}

		if ($criteria->containsKey(EmailQueuePeer::ID) && $criteria->keyContainsValue(EmailQueuePeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.EmailQueuePeer::ID.')');
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

		
    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a EmailQueue or Criteria object.
	 *
	 * @param      mixed $values Criteria or EmailQueue object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseEmailQueuePeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(EmailQueuePeer::ID);
			$selectCriteria->add(EmailQueuePeer::ID, $criteria->remove(EmailQueuePeer::ID), $comparison);

		} else { // $values is EmailQueue object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseEmailQueuePeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseEmailQueuePeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the email_queue table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(EmailQueuePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a EmailQueue or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or EmailQueue object or primary key or array of primary keys
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
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			EmailQueuePeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof EmailQueue) {
			// invalidate the cache for this single object
			EmailQueuePeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(EmailQueuePeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				EmailQueuePeer::removeInstanceFromPool($singleval);
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
	 * Validates all modified columns of given EmailQueue object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      EmailQueue $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(EmailQueue $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(EmailQueuePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(EmailQueuePeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(EmailQueuePeer::DATABASE_NAME, EmailQueuePeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = EmailQueuePeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      string $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     EmailQueue
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = EmailQueuePeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(EmailQueuePeer::DATABASE_NAME);
		$criteria->add(EmailQueuePeer::ID, $pk);

		$v = EmailQueuePeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(EmailQueuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(EmailQueuePeer::DATABASE_NAME);
			$criteria->add(EmailQueuePeer::ID, $pks, Criteria::IN);
			$objs = EmailQueuePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseEmailQueuePeer

// This is the static code needed to register the MapBuilder for this table with the main Propel class.
//
// NOTE: This static code cannot call methods on the EmailQueuePeer class, because it is not defined yet.
// If you need to use overridden methods, you can add this code to the bottom of the EmailQueuePeer class:
//
// Propel::getDatabaseMap(EmailQueuePeer::DATABASE_NAME)->addTableBuilder(EmailQueuePeer::TABLE_NAME, EmailQueuePeer::getMapBuilder());
//
// Doing so will effectively overwrite the registration below.

Propel::getDatabaseMap(BaseEmailQueuePeer::DATABASE_NAME)->addTableBuilder(BaseEmailQueuePeer::TABLE_NAME, BaseEmailQueuePeer::getMapBuilder());

