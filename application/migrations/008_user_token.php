<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_User_Token extends CI_Migration {

    public function up()
    {
        // Update user token length
        $this->db->query("ALTER TABLE `users` CHANGE COLUMN `user_token` `user_token` varchar(62) NOT NULL COMMENT 'Unique user token.'");
    }

    public function down()
    {
        // Back to original
        $this->db->query("ALTER TABLE `users` CHANGE COLUMN `user_token` `user_token` varchar(30) NOT NULL COMMENT 'Unique user token.'");
    }
}