<?php


/**
 * This class adds structure of 'invite_bonus' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/11/12 05:25:24
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class InviteBonusMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.InviteBonusMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(InviteBonusPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(InviteBonusPeer::TABLE_NAME);
		$tMap->setPhpName('InviteBonus');
		$tMap->setClassname('InviteBonus');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('INVITE_ID', 'InviteId', 'INTEGER', false, 11);

		$tMap->addColumn('CUSTOMER_ID', 'CustomerId', 'INTEGER', false, 11);

		$tMap->addColumn('BONUS_PAID', 'BonusPaid', 'INTEGER', false, 11);

	} // doBuild()

} // InviteBonusMapBuilder
