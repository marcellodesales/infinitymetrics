<?php


/**
 * This class adds structure of 'event' table to 'infinitymetricsm201' DatabaseMap object.
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
class EventMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'infinitymetrics.map.EventMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(EventPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(EventPeer::TABLE_NAME);
		$tMap->setPhpName('Event');
		$tMap->setClassname('Event');

		$tMap->setUseIdGenerator(false);

		$tMap->addForeignPrimaryKey('CHANNEL_ID', 'ChannelId', 'INTEGER' , 'channel', 'CHANNEL_ID', true, null);

		$tMap->addPrimaryKey('EVENT_ID', 'EventId', 'VARCHAR', true, 30);

		$tMap->addForeignKey('JN_USERNAME', 'JnUsername', 'VARCHAR', 'user', 'JN_USERNAME', true, 32);

		$tMap->addColumn('DATE', 'Date', 'TIMESTAMP', true, null);

	} // doBuild()

} // EventMapBuilder
