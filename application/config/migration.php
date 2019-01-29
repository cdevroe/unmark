<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Enable/Disable Migrations
|--------------------------------------------------------------------------
|
| Migrations are _enabled_ by default but should be disabled
| if you'd rather not have Unmark update to the latest schema automatically.
|
*/
$config['migration_enabled'] = 								true; // Set to true to do a migration, then set back to false.


/*
|--------------------------------------------------------------------------
| Migrations version
|--------------------------------------------------------------------------
|
| This is used to set migration version that the file system should be on.
| If you run $this->migration->latest() this is the version that schema will
| be upgraded / downgraded to.
|
| Format for migration (YYYYMMDDXX format where XX is an incremented sequence of changes in selected day)
|
*/
$config['migration_version'] = 								2014112501;


/*
|--------------------------------------------------------------------------
| Miscellaneous Migration options
|--------------------------------------------------------------------------
|
| Unlikely you'll want to update these unless you know what you're doing.
|
| Format for migration (YYYYMMDDXX format where XX is an incremented sequence of changes in selected day)
|
*/
$config['migration_type'] = 									'unmark'; // legacy
$config['migration_auto_latest'] = 						true; // Auto-update to the latest migration
$config['migration_table'] = 									'migrations'; // Table where the migration version number is stored


/*
|--------------------------------------------------------------------------
| Migrations Path
|--------------------------------------------------------------------------
|
| Path to your migrations folder.
| Typically, it will be within your application path.
| Also, writing permission is required within the migrations path.
|
*/
$config['migration_path'] = 									APPPATH . 'migrations/';

/*
 * Due to switch from timestamp to unmark numbering (to support 32-bit systems), we need to add mappings
 * for already created migration files, to port on 64-bit systems properly
 * If you've created custom migrations, please add entries to this config in form of
 * old number (timestamp) => new number (YYYYMMDDXX format where XX is an incremented sequence of changes in selected day)
 * This too is legacy.
 */
$config['migration_mappings'] = array(
	'20140228091723' => '2014022801',
	'20141125010000' => '2014112501'
);


/* End of file migration.php */
/* Location: ./application/config/migration.php */
