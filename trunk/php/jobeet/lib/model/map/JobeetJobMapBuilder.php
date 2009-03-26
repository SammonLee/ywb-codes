<?php


/**
 * This class adds structure of 'jobeet_job' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 03/26/09 06:15:01
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class JobeetJobMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.JobeetJobMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(JobeetJobPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(JobeetJobPeer::TABLE_NAME);
		$tMap->setPhpName('JobeetJob');
		$tMap->setClassname('JobeetJob');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CATEGORY_ID', 'CategoryId', 'INTEGER', 'jobeet_category', 'ID', true, null);

		$tMap->addColumn('TYPE', 'Type', 'VARCHAR', false, 255);

		$tMap->addColumn('COMPANY', 'Company', 'VARCHAR', true, 255);

		$tMap->addColumn('LOGO', 'Logo', 'VARCHAR', false, 255);

		$tMap->addColumn('URL', 'Url', 'VARCHAR', false, 255);

		$tMap->addColumn('POSITION', 'Position', 'VARCHAR', true, 255);

		$tMap->addColumn('LOCATION', 'Location', 'VARCHAR', true, 255);

		$tMap->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', true, null);

		$tMap->addColumn('HOW_TO_APPLY', 'HowToApply', 'LONGVARCHAR', true, null);

		$tMap->addColumn('TOKEN', 'Token', 'VARCHAR', true, 255);

		$tMap->addColumn('IS_PUBLIC', 'IsPublic', 'BOOLEAN', true, null);

		$tMap->addColumn('IS_ACTIVATED', 'IsActivated', 'BOOLEAN', true, null);

		$tMap->addColumn('EMAIL', 'Email', 'VARCHAR', true, 255);

		$tMap->addColumn('EXPIRES_AT', 'ExpiresAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // JobeetJobMapBuilder
