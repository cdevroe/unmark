<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Users_update extends CI_Migration {

    public function up()
    {
      $this->db->query("ALTER TABLE `users` ADD COLUMN `salt` varchar(50) NOT NULL COMMENT 'The salt used to generate password.' AFTER `password`");
    }

    public function down()
    {
      $this->db->query("ALTER TABLE `users` DROP COLUMN `salt`");
    }

}