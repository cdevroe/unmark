<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Migration_Batshit_Crazy extends CI_Migration {

    public function up()
    {

      // Make sure all tables are INNODB, UTF-8
      // Original migration they were not
      // If anyone download that version and ran successfully, some keys may not be created correctly
      $this->db->query("ALTER TABLE `groups` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `groups_invites` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `marks` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `migrations` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `users_groups` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `users_marks` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `users_smartlabels` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

      // Create new labels table
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `labels` (
          `label_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key.',
          `smart_label_id` bigint(20) UNSIGNED COMMENT 'If a smart label, the label_id to use if a match is found.',
          `name` varchar(50) NOT NULL COMMENT 'The name of the label.',
          `domain` varchar(255) COMMENT 'The hostname of the domain to match. Keep in all lowercase.',
          `active` tinyint NOT NULL DEFAULT '1' COMMENT '1 is active, 0 if not. Defaults to 1.',
          `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.',
          PRIMARY KEY (`label_id`),
          CONSTRAINT `FK_smart_label_id` FOREIGN KEY (`smart_label_id`) REFERENCES `labels` (`label_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          INDEX `smart_label_id`(smart_label_id)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");

      // Update groups table
      $this->db->query("ALTER TABLE `groups` DROP COLUMN `urlname`");
      $this->db->query("ALTER TABLE `groups` DROP COLUMN `uid`");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `id` `group_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented primary key for groups.'");
      $this->db->query("ALTER TABLE `groups` DROP PRIMARY KEY, ADD PRIMARY KEY (`group_id`)");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `createdby` `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The user id that coorelates to an account in users.user_id'");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `name` `name` varchar(50) NOT NULL COMMENT 'The group name.'");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `description` `description` varchar(255) DEFAULT NULL COMMENT 'The optional description for this group.'");
      $this->db->query("ALTER TABLE `groups` ADD COLUMN `active` tinyint NOT NULL DEFAULT '1' COMMENT '1 if group is active, 0 if not. Defaults to 1.' AFTER `description`");
      $this->db->query("ALTER TABLE `groups` CHANGE COLUMN `datecreated` `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The datetime this record was created.' AFTER `active`");
      $this->db->query("ALTER TABLE `groups` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.' AFTER `created_on`");
      $this->db->query("ALTER TABLE `groups` ADD INDEX `user_id`(user_id)");
      $this->db->query("ALTER TABLE `groups` ADD CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE");

      // Update group_invties table
      $this->db->query("RENAME TABLE `groups_invites` TO `group_invites`");
      $this->db->query("ALTER TABLE `group_invites` DROP COLUMN `invitedby`");
      $this->db->query("ALTER TABLE `group_invites` DROP COLUMN `status`");
      $this->db->query("ALTER TABLE `group_invites` CHANGE COLUMN `id` `group_invite_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key.'");
      $this->db->query("ALTER TABLE `group_invites` DROP PRIMARY KEY, ADD PRIMARY KEY (`group_invite_id`)");
      $this->db->query("ALTER TABLE `group_invites` CHANGE COLUMN `groupid` `group_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The group_id it from groups.group_id'");
      $this->db->query("ALTER TABLE `group_invites` CHANGE COLUMN `emailaddress` `email` varchar(150) NOT NULL COMMENT 'The email address of the invited user.'");
      $this->db->query("ALTER TABLE `group_invites` ADD COLUMN `accepted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = yes, 0 =no' AFTER `email`");
      $this->db->query("ALTER TABLE `group_invites` CHANGE COLUMN `dateinvited` `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The datetime the record was created' AFTER `accepted`");
      $this->db->query("ALTER TABLE `group_invites` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The datetime the record was last updated.' AFTER `created_on`");
      $this->db->query("ALTER TABLE `group_invites` ADD INDEX `group_id`(group_id)");
      $this->db->query("ALTER TABLE `group_invites` ADD CONSTRAINT `FK_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`)   ON UPDATE CASCADE ON DELETE CASCADE");




    }

    public function down()
    {

      // Revert groups invite table
      $this->db->query("RENAME TABLE `group_invites` TO `groups_invites`");
      $this->db->query("ALTER TABLE `groups_invites` DROP FOREIGN KEY `FK_group_id`");
      $this->db->query("ALTER TABLE `groups_invites` DROP INDEX `group_id`");
      $this->db->query("ALTER TABLE `groups_invites` DROP COLUMN `accepted`");
      $this->db->query("ALTER TABLE `groups_invites` DROP COLUMN `last_updated`");
      $this->db->query("ALTER TABLE `groups_invites` CHANGE COLUMN `group_invite_id` `id` int(11) NOT NULL AUTO_INCREMENT");
      $this->db->query("ALTER TABLE `groups_invites` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)");
      $this->db->query("ALTER TABLE `groups_invites` CHANGE COLUMN `group_id` `groupid` int(11) NOT NULL");
      $this->db->query("ALTER TABLE `groups_invites` CHANGE COLUMN `email` `emailaddress` text NOT NULL");
      $this->db->query("ALTER TABLE `groups_invites` ADD COLUMN `invitedby` int(11) NOT NULL AFTER `emailaddress`");
      $this->db->query("ALTER TABLE `groups_invites` CHANGE COLUMN `created_on` `dateinvited` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
      $this->db->query("ALTER TABLE `groups_invites` ADD COLUMN `status` varchar(255) DEFAULT NULL AFTER `dateinvited`");

      // Revert groups table
      $this->db->query("ALTER TABLE `groups` DROP FOREIGN KEY `FK_user_id`");
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

      // Drop labels table
      $this->db->query("DROP TABLE IF EXISTS `labels`");
    }

}