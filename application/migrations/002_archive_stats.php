<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Archive_stats extends Plain_Migration
{
	public function __construct()
    {
      parent::__construct();
      parent::checkForTables('users_marks');
    }

	public function up()
	{
		// Add column to track when a mark was archived
		$this->dbforge->add_column('users_marks', array('datearchived timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
	}

	public function down()
	{
		parent::checkForColumns('datearchived', 'users_marks');
		$this->dbforge->drop_column('users_marks','datearchived');
	}
}
