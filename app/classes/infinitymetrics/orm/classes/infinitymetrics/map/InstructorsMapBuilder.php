<?php


/**
 * This class adds structure of 'instructors' table to 'infinitymetricsm201' DatabaseMap object.
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
class InstructorsMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'infinitymetrics.map.InstructorsMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(InstructorsPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(InstructorsPeer::TABLE_NAME);
		$tMap->setPhpName('Instructors');
		$tMap->setClassname('Instructors');

		$tMap->setUseIdGenerator(false);

		$tMap->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'user', 'USER_ID', true, null);

		$tMap->addForeignKey('INSTITUTION_ID', 'InstitutionId', 'SMALLINT', 'institution', 'INSTITUTION_ID', true, null);

	} // doBuild()

} // InstructorsMapBuilder
