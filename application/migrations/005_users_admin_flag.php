<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Users_admin_flag extends Plain_Migration
{

    public function __construct()
    {
      parent::__construct();
      parent::checkForTables('users');
    }

    public function up()
    {
        $this->db->query("ALTER TABLE `users` ADD COLUMN `admin` tinyint(1) NOT NULL DEFAULT 0 AFTER `last_updated`");
    }

    public function down()
    {
        parent::checkForColumns('admin', 'users');
        $this->db->query("ALTER TABLE `users` DROP COLUMN `admin`");
    }

}