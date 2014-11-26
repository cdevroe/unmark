<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Enable/Disable Migrations
|--------------------------------------------------------------------------
|
| Migrations are disabled by default but should be enabled
| whenever you intend to do a schema migration.
|
*/
$config['migration_enabled'] = true;


/*
|--------------------------------------------------------------------------
| Migrations version
|--------------------------------------------------------------------------
|
| This is used to set migration version that the file system should be on.
| If you run $this->migration->latest() this is the version that schema will
| be upgraded / downgraded to.
|
*/
// Format for migration_type = unmark: (YYYYMMDDXX format where XX is an incremented sequence of changes in selected day)
// Last time changed (version 1.6.0)
$config['migration_version'] = 2014112501;

// Set migration type to timestamp to avoid conflicts
$config['migration_type']    = 'unmark';


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
$config['migration_path'] = APPPATH . 'migrations/';

/*
 * Due to switch from timestamp to unmark numbering (to support 32-bit systems), we need to add mappings
 * for already created migration files, to port on 64-bit systems properly
 * If you've created custom migrations, please add entries to this config in form of
 * old number (timestamp) => new number (YYYYMMDDXX format where XX is an incremented sequence of changes in selected day)
 */
$config['migration_mappings'] = array(
	'20140228091723' => '2014022801',
);


/* End of file migration.php */
/* Location: ./application/config/migration.php */