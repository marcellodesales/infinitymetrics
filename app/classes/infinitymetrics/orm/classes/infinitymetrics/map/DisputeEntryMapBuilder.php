<?php


/**
 * This class adds structure of 'dispute_entry' table to 'infinitymetricsm201' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    infinitymetrics.map
 */
class DisputeEntryMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'infinitymetrics.map.DisputeEntryMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(DisputeEntryPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(DisputeEntryPeer::TABLE_NAME);
		$tMap->setPhpName('DisputeEntry');
		$tMap->setClassname('DisputeEntry');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ENTRY_ID', 'EntryId', 'INTEGER', true, null);

		$tMap->addColumn('NOTES', 'Notes', 'VARCHAR', true, 255);

		$tMap->addForeignKey('DISPUTE_ID', 'DisputeId', 'SMALLINT', 'dispute', 'DISPUTE_ID', true, null);

		$tMap->addColumn('DATE', 'Date', 'TIMESTAMP', true, null);

	} // doBuild()

} // DisputeEntryMapBuilder
