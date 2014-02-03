<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Delete_Marks extends Plain_Migration
{

    public function __construct()
    {
        parent::__construct();
        parent::checkForTables('users_to_marks');
    }

    public function up()
    {
        // Add active column
        $this->db->query("ALTER TABLE `users_to_marks` ADD COLUMN `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Set to 1 for active, 0 for inactive.' AFTER `notes`");

    }

    public function down()
    {
        parent::checkForColumns('active', 'users_to_marks');
        $this->db->query("ALTER TABLE `users_to_marks` DROP COLUMN `active`");
    }
}