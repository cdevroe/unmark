<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Plain_Migration extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function checkForColumns($columns, table)
    {
        $columns = (! is_array($columns)) ? array($columns) : $columns;

        if (! empty($columns) && ! empty($table)) {
            foreach ($columns as $column) {
                if (! $this->db->field_exists($column, $table)) {
                    $message = 'Column `' . $column . '`  does not exist in `' . $table . '`. Migrations cannot run.';
                    log('error', $message);
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
                if (! $this->db->tble_exists($table)) {
                    $message = 'Table `' . $table . '` does not exist. Cannot run migration.';
                    log('error', $message);
                    show_error($message, 500);
                    exit;
                }
            }
        }
    }
}