<?php


/**
 * This class adds structure of 'channel' table to 'infinitymetricsm201' DatabaseMap object.
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
class ChannelMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'infinitymetrics.map.ChannelMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(ChannelPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(ChannelPeer::TABLE_NAME);
		$tMap->setPhpName('Channel');
		$tMap->setClassname('Channel');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('CHANNEL_ID', 'ChannelId', 'INTEGER', true, null);

		$tMap->addForeignPrimaryKey('PROJECT_JN_NAME', 'ProjectJnName', 'VARCHAR' , 'project', 'PROJECT_JN_NAME', true, 50);

		$tMap->addPrimaryKey('CHANNEL_NAME', 'ChannelName', 'VARCHAR', true, 30);

		$tMap->addColumn('TITLE', 'Title', 'VARCHAR', false, 255);

		$tMap->addColumn('CATEGORY', 'Category', 'CHAR', true, null);

	} // doBuild()

} // ChannelMapBuilder
