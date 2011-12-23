<?php


/**
 * This class adds structure of 'agent_company' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * Tue Dec 20 13:41:50 2011
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class AgentCompanyMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.AgentCompanyMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(AgentCompanyPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(AgentCompanyPeer::TABLE_NAME);
		$tMap->setPhpName('AgentCompany');
		$tMap->setClassname('AgentCompany');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', true, 255);

		$tMap->addColumn('CVR_NUMBER', 'CvrNumber', 'INTEGER', true, 11);

		$tMap->addColumn('EAN_NUMBER', 'EanNumber', 'INTEGER', false, 11);

		$tMap->addColumn('ADDRESS', 'Address', 'VARCHAR', true, 255);

		$tMap->addColumn('POST_CODE', 'PostCode', 'INTEGER', true, 11);

		$tMap->addForeignKey('COUNTRY_ID', 'CountryId', 'INTEGER', 'country', 'ID', false, 11);

		$tMap->addForeignKey('CITY_ID', 'CityId', 'INTEGER', 'city', 'ID', false, 11);

		$tMap->addColumn('CONTACT_NAME', 'ContactName', 'VARCHAR', true, 150);

		$tMap->addColumn('EMAIL', 'Email', 'VARCHAR', true, 255);

		$tMap->addColumn('HEAD_PHONE_NUMBER', 'HeadPhoneNumber', 'INTEGER', true, 11);

		$tMap->addColumn('FAX_NUMBER', 'FaxNumber', 'INTEGER', true, 11);

		$tMap->addColumn('WEBSITE', 'Website', 'VARCHAR', false, 255);

		$tMap->addForeignKey('STATUS_ID', 'StatusId', 'INTEGER', 'status', 'ID', false, 11);

		$tMap->addForeignKey('COMPANY_TYPE_ID', 'CompanyTypeId', 'INTEGER', 'company_type', 'ID', false, 11);

		$tMap->addColumn('PRODUCT_DETAIL', 'ProductDetail', 'INTEGER', false, 11);

		$tMap->addForeignKey('COMMISSION_PERIOD_ID', 'CommissionPeriodId', 'INTEGER', 'commission_period', 'ID', false, 11);

		$tMap->addForeignKey('ACCOUNT_MANAGER_ID', 'AccountManagerId', 'INTEGER', 'user', 'ID', false, 11);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addForeignKey('AGENT_COMMISSION_PACKAGE_ID', 'AgentCommissionPackageId', 'INTEGER', 'agent_commission_package', 'ID', true, 11);

		$tMap->addColumn('SMS_CODE', 'SmsCode', 'VARCHAR', false, 4);

		$tMap->addColumn('IS_PREPAID', 'IsPrepaid', 'BOOLEAN', false, null);

		$tMap->addColumn('BALANCE', 'Balance', 'FLOAT', false, null);

	} // doBuild()

} // AgentCompanyMapBuilder
