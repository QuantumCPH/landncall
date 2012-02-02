<?php


/**
 * This class adds structure of 'product' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/13/12 05:16:51
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class ProductMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ProductMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(ProductPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(ProductPeer::TABLE_NAME);
		$tMap->setPhpName('Product');
		$tMap->setClassname('Product');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 20);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', false, 50);

		$tMap->addColumn('PRICE', 'Price', 'DOUBLE', true, null);

		$tMap->addColumn('DESCRIPTION', 'Description', 'VARCHAR', false, 200);

		$tMap->addColumn('INITIAL_BALANCE', 'InitialBalance', 'DOUBLE', true, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('IS_EXTRA_FILL_APPLIED', 'IsExtraFillApplied', 'BOOLEAN', true, null);

		$tMap->addColumn('INCLUDE_IN_ZEROCALL', 'IncludeInZerocall', 'BOOLEAN', false, null);

		$tMap->addColumn('IS_IN_STORE', 'IsInStore', 'BOOLEAN', true, null);

		$tMap->addColumn('SMS_CODE', 'SmsCode', 'VARCHAR', false, 2);

		$tMap->addColumn('COUNTRY', 'Country', 'INTEGER', true, 100);

		$tMap->addColumn('REFILL', 'Refill', 'VARCHAR', true, 400);

		$tMap->addForeignKey('COUNTRY_ID', 'CountryId', 'INTEGER', 'enable_country', 'ID', false, 11);

		$tMap->addColumn('REFILL_OPTIONS', 'RefillOptions', 'VARCHAR', true, 400);

		$tMap->addColumn('PRODUCT_ORDER', 'ProductOrder', 'INTEGER', false, 11);

		$tMap->addColumn('PRODUCT_TYPE_PACKAGE', 'ProductTypePackage', 'BOOLEAN', true, null);

		$tMap->addColumn('PRODUCT_COUNTRY_US', 'ProductCountryUs', 'BOOLEAN', true, null);

	} // doBuild()

} // ProductMapBuilder
