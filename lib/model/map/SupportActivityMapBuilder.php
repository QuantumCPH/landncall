<?php


/**
 * This class adds structure of 'support_activity' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/13/12 05:16:52
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class SupportActivityMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SupportActivityMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(SupportActivityPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(SupportActivityPeer::TABLE_NAME);
		$tMap->setPhpName('SupportActivity');
		$tMap->setClassname('SupportActivity');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addForeignKey('EMPLOYEE_ID', 'EmployeeId', 'INTEGER', 'employee', 'ID', false, 11);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('TICKET_NUMBER', 'TicketNumber', 'INTEGER', true, 11);

		$tMap->addForeignKey('SUPPORT_ISSUE_ID', 'SupportIssueId', 'INTEGER', 'support_issue', 'ID', false, 11);

		$tMap->addColumn('COMMENT', 'Comment', 'LONGVARCHAR', true, null);

		$tMap->addColumn('SOLUTION', 'Solution', 'LONGVARCHAR', true, null);

		$tMap->addColumn('FILE_PATH', 'FilePath', 'VARCHAR', false, 255);

		$tMap->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'user', 'ID', false, 11);

		$tMap->addForeignKey('SUPPORT_ACTIVITY_STATUS_ID', 'SupportActivityStatusId', 'INTEGER', 'support_activity_status', 'ID', false, 11);

	} // doBuild()

} // SupportActivityMapBuilder
