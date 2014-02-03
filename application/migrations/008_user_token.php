<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_User_Token extends Plain_Migration
{

    public function __construct()
    {
        parent::__construct();
        parent::checkForTables('users');
        parent::checkForColumns('user_token', 'users');
    }

    public function up()
    {
        // Update user token length
        $this->db->query("ALTER TABLE `users` CHANGE COLUMN `user_token` `user_token` varchar(62) NOT NULL COMMENT 'Unique user token.'");

        // Update all users
        $users = $this->db->query("SELECT user_id, user_token, created_on FROM `users`");
        if ($users->num_rows() > 0) {
            foreach ($users->result() as $user) {
                $res = $this->db->query("UPDATE `users` SET user_token = '" . $user->user_token . md5($user->created_on) . "' WHERE user_id = '" . $user->user_id . "'");
            }
        }
    }

    public function down()
    {
        // Back to original
        $this->db->query("ALTER TABLE `users` CHANGE COLUMN `user_token` `user_token` varchar(30) NOT NULL COMMENT 'Unique user token.'");
    }
}