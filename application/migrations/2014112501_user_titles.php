<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This migration allows for users to add their own titles for marks.
// This was added in 1.6.0

class Migration_User_Titles extends Plain_Migration
{

    public function __construct()
    {
        parent::__construct();
        parent::checkForTables('users_to_marks');
    }

    public function up()
    {
        // Add mark_title column to users_to_marks
        $this->db->query("ALTER TABLE `users_to_marks` ADD COLUMN `mark_title` text DEFAULT NULL COMMENT 'An optional user-specific mark title.' AFTER `mark_id`");

    }

    public function down()
    {
        parent::checkForColumns('mark_title', 'users_to_marks');
        $this->db->query("ALTER TABLE `users_to_marks` DROP COLUMN `mark_title`");
    }
}