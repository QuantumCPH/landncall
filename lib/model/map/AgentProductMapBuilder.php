<?php


/**
 * This class adds structure of 'agent_product' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 09:22:01
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class AgentProductMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.AgentProductMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(AgentProductPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(AgentProductPeer::TABLE_NAME);
		$tMap->setPhpName('AgentProduct');
		$tMap->setClassname('AgentProduct');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('AGENT_PRODUCT_ID', 'AgentProductId', 'INTEGER', true, 11);

		$tMap->addForeignKey('AGENT_ID', 'AgentId', 'INTEGER', 'agent_company', 'ID', true, 11);

		$tMap->addForeignKey('PRODUCT_ID', 'ProductId', 'INTEGER', 'product', 'ID', true, 11);

		$tMap->addColumn('REG_SHARE_VALUE', 'RegShareValue', 'FLOAT', true, null);

		$tMap->addColumn('IS_REG_SHARE_VALUE_PC', 'IsRegShareValuePc', 'BOOLEAN', true, null);

		$tMap->addColumn('REG_SHARE_ENABLE', 'RegShareEnable', 'BOOLEAN', true, null);

		$tMap->addColumn('EXTRA_PAYMENTS_SHARE_VALUE', 'ExtraPaymentsShareValue', 'FLOAT', true, null);

		$tMap->addColumn('IS_EXTRA_PAYMENTS_SHARE_VALUE_PC', 'IsExtraPaymentsShareValuePc', 'BOOLEAN', true, null);

		$tMap->addColumn('EXTRA_PAYMENTS_SHARE_ENABLE', 'ExtraPaymentsShareEnable', 'BOOLEAN', true, null);

	} // doBuild()

} // AgentProductMapBuilder
