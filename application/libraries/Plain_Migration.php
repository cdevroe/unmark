<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Plain_Migration extends CI_Migration
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        self::checkForInnoDB();
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