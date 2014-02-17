<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Plain_Migration extends CI_Migration
{
    public function __construct($config = array())
    {
        parent::__construct($config);
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
                if (! isset($obj->Engine) || strtolower($obj->Engine) != 'innodb') {
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