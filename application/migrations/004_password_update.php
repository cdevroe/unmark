<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Password_update extends Plain_Migration
{

    public function __construct()
    {
      parent::__construct();
      parent::checkForTables('users');
    }

    public function up()
    {
        $this->db->query("ALTER TABLE `users` ADD COLUMN `salt` varchar(50) DEFAULT NULL COMMENT 'The salt used to generate password.' AFTER `password`");
    }

    public function down()
    {
        parent::checkForColumns('salt', 'users');
        $this->db->query("ALTER TABLE `users` DROP COLUMN `salt`");
    }

}