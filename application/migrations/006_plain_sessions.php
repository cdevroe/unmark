<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plain_sessions extends CI_Migration {

    public function up()
    {	// Create tables
        if ( !$this->db->table_exists('plain_sessions') ) {
            $this->db->query("CREATE TABLE IF NOT EXISTS  `plain_sessions` (
	           session_id varchar(40) NOT NULL COMMENT 'Unique session identifier',
	           session_data text NOT NULL COMMENT 'Serialized session data',
               last_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last updated at',
	           PRIMARY KEY (session_id)
               )  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
        }
    }

    public function down()
    {
        // Drop tables
        $this->dbforge->drop_table('plain_sessions');
    }
}

/* End of file 001_create_nilai.php */
/* Location: ./application/migrations/001_create_nilai.php */