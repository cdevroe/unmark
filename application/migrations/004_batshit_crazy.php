<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Migration_Batshit_Crazy extends CI_Migration {

    public function up()
    {
      // Update groups table
      $this->db->query("ALTER TABLE `groups` DROP COLUMN `urlname`");
      $this->db->query("ALTER TABLE `groups` DROP COLUMN `uid`");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `id` `group_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented primary key for groups.'");
      $this->db->query("ALTER TABLE `groups` DROP PRIMARY KEY, ADD PRIMARY KEY (`group_id`)");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `createdby` `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The user id that coorelates to an account in users.user_id'");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `name` `name` varchar(50) NOT NULL COMMENT 'The group name.'");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `description` `description` varchar(255) DEFAULT NULL COMMENT 'The optional description for this group.'");
      $this->db->query("ALTER TABLE `groups` ADD COLUMN `active` char(1) NOT NULL DEFAULT '1' COMMENT '1 if group is active, 0 if not. Defaults to 1.' AFTER `description`");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `datecreated` `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The datetime this record was created.' AFTER `active`");
      $this->db->query("ALTER TABLE `groups` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.' AFTER `created_on`");
      $this->db->query("ALTER TABLE `groups` ADD INDEX `user_id`(user_id)");
      $this->db->query("ALTER TABLE `groups` ADD CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE");
    }

    public function down()
    {

      // Revert groups table
      $this->db->query("ALTER TABLE `groups` DROP INDEX `user_id`");
      $this->db->query("ALTER TABLE `groups` DROP COLUMN `last_updated`");
      $this->db->query("ALTER TABLE `groups` DROP COLUMN `active`");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `group_id` `id` int(11) NOT NULL AUTO_INCREMENT");
      $this->db->query("ALTER TABLE `groups` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `user_id` `createdby` int(11) NOT NULL");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `name` `name` varchar(255) NOT NULL");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `description` `description` text DEFAULT NULL ");
      $this->db->query("ALTER TABLE `groups` ADD COLUMN `urlname` varchar(255) DEFAULT NULL AFTER `description`");
      $this->db->query("ALTER TABLE `groups` ADD COLUMN `uid` text NOT NULL AFTER `urlname`");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `created_on` `datecreated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }

}