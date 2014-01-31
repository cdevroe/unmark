<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Delete_Marks extends CI_Migration {

    public function up()
    {
        // Add active column
        $this->db->query("ALTER TABLE `users_to_marks` ADD COLUMN `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Set to 1 for active, 0 for inactive.' AFTER `notes`");

    }

    public function down()
    {
        // Back to original
        $this->db->query("ALTER TABLE `users_to_marks` DROP COLUMN `active`");
    }
}