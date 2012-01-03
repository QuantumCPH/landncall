<?php


/**
 * This class adds structure of 'customer_order' table to 'propel' DatabaseMap object.
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
class CustomerOrderMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CustomerOrderMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CustomerOrderPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CustomerOrderPeer::TABLE_NAME);
		$tMap->setPhpName('CustomerOrder');
		$tMap->setClassname('CustomerOrder');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'BIGINT', true, 20);

		$tMap->addForeignKey('PRODUCT_ID', 'ProductId', 'INTEGER', 'product', 'ID', true, 11);

		$tMap->addColumn('QUANTITY', 'Quantity', 'INTEGER', true, 11);

		$tMap->addColumn('ORDER_STATUS_ID', 'OrderStatusId', 'INTEGER', true, 11);

		$tMap->addForeignKey('CUSTOMER_ID', 'CustomerId', 'BIGINT', 'customer', 'ID', true, 20);

		$tMap->addColumn('EXTRA_REFILL', 'ExtraRefill', 'DOUBLE', true, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('IS_FIRST_ORDER', 'IsFirstOrder', 'BOOLEAN', true, null);

		$tMap->addForeignKey('AGENT_COMMISSION_PACKAGE_ID', 'AgentCommissionPackageId', 'INTEGER', 'agent_commission_package', 'ID', false, 11);

		$tMap->addColumn('EXE_STATUS', 'ExeStatus', 'INTEGER', false, 11);

	} // doBuild()

} // CustomerOrderMapBuilder
