<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plain_sessions extends CI_Migration {

    public function up()
    {	// Create tables
        if ( !$this->db->table_exists('plain_sessions') ) {
            $this->db->query("CREATE TABLE IF NOT EXISTS  `plain_sessions` (
	           session_id varchar(40) DEFAULT '0' NOT NULL,
	           session_data text NOT NULL,
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