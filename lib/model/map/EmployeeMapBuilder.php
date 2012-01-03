<?php


/**
 * This class adds structure of 'employee' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 09:22:03
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class EmployeeMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.EmployeeMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(EmployeePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(EmployeePeer::TABLE_NAME);
		$tMap->setPhpName('Employee');
		$tMap->setClassname('Employee');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addForeignKey('COMPANY_ID', 'CompanyId', 'INTEGER', 'company', 'ID', false, 11);

		$tMap->addColumn('FIRST_NAME', 'FirstName', 'VARCHAR', true, 50);

		$tMap->addColumn('LAST_NAME', 'LastName', 'VARCHAR', true, 50);

		$tMap->addColumn('EMAIL', 'Email', 'VARCHAR', true, 150);

		$tMap->addColumn('MOBILE_MODEL', 'MobileModel', 'VARCHAR', true, 50);

		$tMap->addColumn('MOBILE_NUMBER', 'MobileNumber', 'VARCHAR', true, 50);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('APP_CODE', 'AppCode', 'VARCHAR', false, 7);

		$tMap->addColumn('IS_APP_REGISTERED', 'IsAppRegistered', 'BOOLEAN', false, null);

		$tMap->addColumn('PASSWORD', 'Password', 'VARCHAR', false, 255);

		$tMap->addColumn('REGISTRATION_TYPE', 'RegistrationType', 'VARCHAR', false, 255);

		$tMap->addColumn('PRODUCT_PRICE', 'ProductPrice', 'INTEGER', false, 11);

		$tMap->addColumn('PRODUCT_ID', 'ProductId', 'INTEGER', false, 11);

		$tMap->addColumn('COUNTRY_CODE', 'CountryCode', 'VARCHAR', false, 50);

		$tMap->addColumn('COUNTRY_MOBILE_NUMBER', 'CountryMobileNumber', 'VARCHAR', false, 250);

	} // doBuild()

} // EmployeeMapBuilder
