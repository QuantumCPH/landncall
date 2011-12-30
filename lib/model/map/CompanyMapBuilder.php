<?php


/**
 * This class adds structure of 'company' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/30/11 11:22:05
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CompanyMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CompanyMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CompanyPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CompanyPeer::TABLE_NAME);
		$tMap->setPhpName('Company');
		$tMap->setClassname('Company');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', true, 255);

		$tMap->addColumn('VAT_NO', 'VatNo', 'INTEGER', true, 11);

		$tMap->addColumn('EAN_NUMBER', 'EanNumber', 'INTEGER', false, 11);

		$tMap->addColumn('ADDRESS', 'Address', 'VARCHAR', true, 255);

		$tMap->addColumn('POST_CODE', 'PostCode', 'VARCHAR', true, 255);

		$tMap->addForeignKey('COUNTRY_ID', 'CountryId', 'INTEGER', 'country', 'ID', false, 11);

		$tMap->addForeignKey('CITY_ID', 'CityId', 'INTEGER', 'city', 'ID', false, 11);

		$tMap->addColumn('CONTACT_NAME', 'ContactName', 'VARCHAR', true, 150);

		$tMap->addColumn('EMAIL', 'Email', 'VARCHAR', true, 255);

		$tMap->addColumn('HEAD_PHONE_NUMBER', 'HeadPhoneNumber', 'INTEGER', true, 11);

		$tMap->addColumn('FAX_NUMBER', 'FaxNumber', 'INTEGER', false, 11);

		$tMap->addColumn('WEBSITE', 'Website', 'VARCHAR', false, 255);

		$tMap->addForeignKey('STATUS_ID', 'StatusId', 'INTEGER', 'status', 'ID', false, 11);

		$tMap->addForeignKey('COMPANY_SIZE_ID', 'CompanySizeId', 'INTEGER', 'company_size', 'ID', false, 11);

		$tMap->addForeignKey('COMPANY_TYPE_ID', 'CompanyTypeId', 'INTEGER', 'company_type', 'ID', false, 11);

		$tMap->addForeignKey('CUSTOMER_TYPE_ID', 'CustomerTypeId', 'INTEGER', 'customer_type', 'ID', false, 11);

		$tMap->addColumn('CPR_NUMBER', 'CprNumber', 'INTEGER', false, 11);

		$tMap->addForeignKey('APARTMENT_FORM_ID', 'ApartmentFormId', 'INTEGER', 'apartment_form', 'ID', false, 11);

		$tMap->addForeignKey('INVOICE_METHOD_ID', 'InvoiceMethodId', 'INTEGER', 'invoice_method', 'ID', true, 11);

		$tMap->addForeignKey('ACCOUNT_MANAGER_ID', 'AccountManagerId', 'INTEGER', 'user', 'ID', false, 11);

		$tMap->addForeignKey('AGENT_COMPANY_ID', 'AgentCompanyId', 'INTEGER', 'agent_company', 'ID', false, 11);

		$tMap->addColumn('CONFIRMED_AT', 'ConfirmedAt', 'DATE', false, null);

		$tMap->addColumn('CVR_NUMBER', 'CvrNumber', 'INTEGER', false, 11);

		$tMap->addColumn('SIM_CARD_DISPATCH_DATE', 'SimCardDispatchDate', 'DATE', false, null);

		$tMap->addForeignKey('PACKAGE_ID', 'PackageId', 'INTEGER', 'package', 'ID', true, 11);

		$tMap->addColumn('USAGE_DISCOUNT_PC', 'UsageDiscountPc', 'FLOAT', false, null);

		$tMap->addColumn('REGISTRATION_DATE', 'RegistrationDate', 'TIMESTAMP', false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('FILE_PATH', 'FilePath', 'VARCHAR', false, 255);

		$tMap->addColumn('RATE_TABLE_ID', 'RateTableId', 'INTEGER', true, 11);

	} // doBuild()

} // CompanyMapBuilder
