<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Users_update extends Plain_Migration {

    public function __construct()
    {
      parent::__construct();
      parent::checkForTables('users');
    }

    public function up()
    {
      parent::checkForColumns(array('id', 'emailaddress', 'password', 'datejoined', 'status'), 'users');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `id` `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT');
      $this->db->query('ALTER TABLE `users` DROP PRIMARY KEY, ADD PRIMARY KEY (`user_id`)');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `emailaddress` `email` varchar(255) NOT NULL');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `password` `password` varchar(150) NOT NULL');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `datejoined` `date_joined` datetime NOT NULL');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `status` `status` varchar(25) NOT NULL DEFAULT \'inactive\'');
      $this->db->query('ALTER TABLE `users` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `status`');
      $this->db->query('ALTER TABLE `users` DROP INDEX `emailaddress`');
      $this->db->query('ALTER TABLE `users` ADD UNIQUE `email`(email)');
    }

    public function down()
    {

      parent::checkForColumns(array('user_id', 'email', 'password', 'date_joined', 'status', 'last_updated'), 'users');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `user_id` `id` bigint(11) NOT NULL AUTO_INCREMENT');
      $this->db->query('ALTER TABLE `users` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)');
      $this->db->query('ALTER TABLE `users` DROP COLUMN `last_updated`');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `email` `emailaddress` varchar(255) NOT NULL');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `password` `password` varchar(255) NOT NULL');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `date_joined` `datejoined` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
      $this->db->query('ALTER TABLE `users` CHANGE COLUMN `status` `status` varchar(255) NOT NULL');
      $this->db->query('ALTER TABLE `users` DROP INDEX `email`');
      $this->db->query('ALTER TABLE `users` ADD UNIQUE `emailaddress`(emailaddress)');
    }

}
