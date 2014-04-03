<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Plain_Migration extends CI_Migration
{
    public function __construct($config = array())
    {
        // Set config to main config and unset
        $main_config = $config;
        unset($config);

        // If custom config exists, get it and set to custom variable
        if (file_exists(CUSTOMPATH . 'config/migration.php')) {
            include CUSTOMPATH . 'config/migration.php';
            $custom_config = $config;
            unset($config);
        }

        // Return main config to original variable
        $config = $main_config;

        // Set the latest release
        if (! empty($config)) {
            $config['migration_version'] = (isset($custom_config['migration_version']) && $custom_config['migration_version'] > $config['migration_version']) ? $custom_config['migration_version'] : $config['migration_version'];
            if(empty($config['migration_mappings'])){
                $config['migration_mappings'] = array();
            }
            if(!empty($custom_config['migration_mappings'])){
                $config['migration_mappings'] = $config['migration_mappings'] + $custom_config['migration_mappings'];
            }
        }

        // Overriden parent constructor to support YYYYMMDDXX format where XX is sequential number in day
        if (get_parent_class($this) !== FALSE && get_class($this) !== config_item('subclass_prefix').'Migration' )
        {
            return;
        }
        
        foreach ($config as $key => $val)
        {
            $this->{'_'.$key} = $val;
        }
        
        log_message('debug', 'Migrations class initialized');
        
        // Are they trying to use migrations while it is disabled?
        if ($this->_migration_enabled !== TRUE)
        {
            show_error('Migrations has been loaded but is disabled or set up incorrectly.');
        }
        
        // If not set, set it
        $this->_migration_path !== '' OR $this->_migration_path = APPPATH.'migrations/';
        
        // Add trailing slash if not set
        $this->_migration_path = rtrim($this->_migration_path, '/').'/';
        
        // Load migration language
        $this->lang->load('migration');
        
        // They'll probably be using dbforge
        $this->load->dbforge();
        
        // Make sure the migration table name was set.
        if (empty($this->_migration_table))
        {
            show_error('Migrations configuration file (migration.php) must have "migration_table" set.');
        }
        
        // Migration basename regex
        switch($this->_migration_type){
        	case 'timestamp':
        	    $this->_migration_regex = '/^\d{14}_(\w+)$/';
        	    break;
        	case 'unmark':
        	    $this->_migration_regex = '/^\d{10}_(\w+)$/';
        	    break;
        	default:
        	    $this->_migration_regex = '/^\d{3}_(\w+)$/';
        }     
        
        // Make sure a valid migration numbering type was set.
        if ( ! in_array($this->_migration_type, array('sequential', 'timestamp', 'unmark')))
        {
            show_error('An invalid migration numbering type was specified: '.$this->_migration_type);
        }
        
        // If the migrations table is missing, make it
        if ( ! $this->db->table_exists($this->_migration_table))
        {
            $this->dbforge->add_field(array(
                'version' => array('type' => 'BIGINT', 'constraint' => 20),
            ));
        
            $this->dbforge->create_table($this->_migration_table, TRUE);
        
            $this->db->insert($this->_migration_table, array('version' => 0));
        }
        
        // Do we auto migrate to the latest migration?
        if ($this->_migration_auto_latest === TRUE && ! $this->latest())
        {
            show_error($this->error_string());
        }
        // Check that all tables are InnoDB
        self::checkForInnoDB();
    }

    // --------------------------------------------------------------------

    /**
     * Retrieves list of available migration scripts
     *
     * @return  array   list of migration file paths sorted by version
     */
    public function find_migrations()
    {
        $migrations = array();

        $files = glob($this->_migration_path.'*_*.php');
        if (is_dir(CUSTOMPATH . 'migrations/')) {
            $files = array_merge($files, glob(CUSTOMPATH . 'migrations/' . '*_*.php'));
        }

        // Load all *_*.php files in the migrations path
        foreach ($files as $file) {
            $name   = basename($file, '.php');
            $number = $this->_get_migration_number($name);

            // There cannot be duplicate migration numbers
            if (isset($migrations[$number]))
            {
                $this->_error_string = sprintf($this->lang->line('migration_multiple_version'), $number);
                show_error($this->_error_string);
            }
            elseif (! is_numeric($number) || empty($number)) {
                log_message('error', 'Migration file not used because it did not start with a numeric (' . $file . ')');
            }
            else {
                $migrations[$number] = $file;
            }
        }

        ksort($migrations);
        return $migrations;
    }

    /**
     * Extends migration mechanism to create backup before running migrations and remove it on success, but keep on failure
     * (non-PHPdoc)
     * @see CI_Migration::version()
     */
    public function version($target_version)
    {
        // Make DB backup
        $backupFile = $this->_make_backup();
        if($backupFile === FALSE){
            log_message('DEBUG', 'Making backup before migrating failed.');
        }
        // Run migrationgs
        $migrationsResult = parent::version($target_version);
        // If everything went well - remove backup
        if($migrationsResult !== FALSE && $migrationsResult == $target_version){
            if($backupFile !== FALSE && !$this->_delete_backup($backupFile)){
                log_message('debug', 'There was an error when removing backup file.');
            }
        } else{
            if($backupFile !== FALSE){
                log_message('error', 'Migrating to version '.$target_version.' failed. Backup from before migration stored in '.$backupFile);
            } else{
                log_message('error', 'Migrating to version '.$target_version.' failed, but no valid backup saved.');
            }
        }
        return $migrationsResult;
    }
    
    /**
     * Retrieves current schema version
     * If _migration_type == 'unmark' and previous version matches timestamp - return different version
     *
     * @return	int	Current Migration
     */
    protected function _get_version()
    {
        $row = $this->db->select('version')->get($this->_migration_table)->row();
        $finalVersion = $row ? $row->version : 0;
        // Check if we're not switching from timestamp to unmark
        if($finalVersion != 0 && $this->_migration_type === 'unmark' && strlen((string) $finalVersion)  == 14){
            // Switch from timestamp to unmark
            if(!empty($this->_migration_mappings) && !empty($this->_migration_mappings[$finalVersion])){
                log_message('debug', sprintf('Returning mapped %s instead of original %s migration version and updating DB', $this->_migration_mappings[$finalVersion] , $finalVersion));
                $finalVersion = $this->_migration_mappings[$finalVersion];
                $this->_update_version($finalVersion);
            } else{
                show_error(sprintf(_('Migrations switched from timestamp to unmark, but cannot find mapping for version %s. Please add correct version to migrations_mapping config entry for migrations'), $finalVersion));
            }
        }
        return $finalVersion;
    }

    /**
     * Creates database backup and returns a path to a file with this backup
     * @return string Backup file path
     */
    protected function _make_backup(){
        if ($this->db->dbdriver != 'mysql') {
            // FIXME kip9 Look for a way to work with other drivers
            return false;
        }

        $fullBackupFileName = $this->_createBackupFile();
        if($fullBackupFileName !== false){
            // Do backup
            $this->load->dbutil();
            // Backup your entire database and assign it to a variable
            $backup =& $this->dbutil->backup();

            // Load the file helper and write the file to your server
            $this->load->helper('file');
            write_file($fullBackupFileName, $backup);
            @chmod($fullBackupFileName, 0600);
            log_message('info', 'Created backup file '.$fullBackupFileName);
        }
        return $fullBackupFileName;
    }

    /**
     * Creates new database backup file in folder specified by config
     * @return boolean|string File path or false on failure
     */
    private function _createBackupFile(){

        $backupFolder = APPPATH . $this->config->item('plain_db_backup_folder');
        if(!file_exists($backupFolder)){
            if(!mkdir($backupFolder, 0700, true)){
                log_message('DEBUG', 'Cannot create folder for DB backups '.$backupFolder);
                return false;
            }
        }
        if(!is_readable($backupFolder)){
            log_message('DEBUG', 'Cannot create DB backup - backups folder '.$backupFolder.' is not readable');
            return false;
        }
        $absolutePath = realpath($backupFolder);
        $count=0;
        // Skip existing backups
        do{
            $backupFileName = 'db_'.$count.'_'.time().'.bak.gz';
            $fullBackupFileName = $absolutePath . DIRECTORY_SEPARATOR . $backupFileName;
        } while(file_exists($fullBackupFileName));
        return $fullBackupFileName;
    }

    /**
     * Removes database backup file
     * @param string $backupFile Path to stored backup file
     * @return boolean
     */
    protected function _delete_backup($backupFile){
        $configFlag = $this->config->item('plain_db_backup_remove_on_success');
        // If config flag is set to false - do not remove
        if(isset($configFlag) && $configFlag === false){
            return true;
        }
        // Remove backup and return result of deletion
        return unlink($backupFile);
    }

    protected function checkForColumns($columns, $table)
    {
        $columns = (! is_array($columns)) ? array($columns) : $columns;

        if (! empty($columns) && ! empty($table)) {
            $current_columns = self::getColumns($table);
            foreach ($columns as $column) {
                if (! in_array($column, $current_columns)) {
                    $message = 'Column `' . $column . '`  does not exist in `' . $table . '`. Migrations cannot run.';
                    log_message('error', $message);
                    show_error($message, 500);
                    exit;
                }
            }
        }
    }

    protected function checkForInnoDB()
    {
        $res = $this->db->query('SHOW TABLE STATUS');
        if ($res->num_rows() > 0) {
            foreach ($res->result() as $k => $obj) {
                if ((! isset($obj->Engine) || strtolower($obj->Engine) != 'innodb') && ! stristr($obj->Name, 'migrations')) {
                    $message = 'Table `' . $obj->Name . '` is not in InnoDB format. Migrations cannot run.';
                    log_message('error', $message);
                    show_error($message, 500);
                    exit;
                }
            }
        }
    }


    protected function checkForTables($tables)
    {
        $tables = (! is_array($tables)) ? array($tables) : $tables;
        if (! empty($tables)) {
            foreach ($tables as $table) {
                if (self::tableExists($table) === false) {
                    $message = 'Table `' . $table . '` does not exist. Cannot run migration.';
                    log_message('error', $message);
                    show_error($message, 500);
                    exit;
                }
            }
        }
    }

    private function getColumns($table)
    {
        $columns = array();
        $q = $this->db->query("SHOW COLUMNS FROM `" . $table . "`");
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $obj) {
                array_push($columns, $obj->Field);
            }
        }
        return $columns;
    }

    private function tableExists($table)
    {
        $q = $this->db->query("SHOW TABLES LIKE '" . $table . "'");
        return ($q->num_rows() > 0) ? true : false;
    }
}
