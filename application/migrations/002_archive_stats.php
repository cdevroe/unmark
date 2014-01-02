<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Archive_stats extends CI_Migration {

	public function up()
	{	
		// Add column to track when a mark was archived
		if ( $this->db->table_exists('users_marks') && !$this->db->field_exists('datearchived','users_marks') ) {
			$this->dbforge->add_column('users_marks',
				array('datearchived' => array('type'=>'timestamp') ) );
		}

	}

	public function down()
	{
		// Drop column to track when a mark was archived
		if ( $this->db->table_exists('users_marks') && $this->db->field_exists('datearchived','users_marks') ) {
			$this->dbforge->drop_column('users_marks','datearchived');
		}
	}
}

/* End of file 002_create_nilai.php */
/* Location: ./application/migrations/002_create_nilai.php */