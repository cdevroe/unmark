<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Migration_Batshit_Crazy extends CI_Migration {

    public function up()
    {

      set_time_limit(0);
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
          `smart_label_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'If a smart label, the label_id to use if a match is found.',
          `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'If a label but owned by a user, place the users.user_id here.',
          `name` varchar(50) DEFAULT NULL COMMENT 'The name of the label.',
          `domain` varchar(255) DEFAULT NULL COMMENT 'The hostname of the domain to match. Keep in all lowercase.',
          `path` varchar(100) DEFAULT NULL COMMENT 'The path to find to for smartlabels to match. If null, just match host.',
          `active` tinyint NOT NULL DEFAULT '1' COMMENT '1 is active, 0 if not. Defaults to 1.',
          `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.',
          PRIMARY KEY (`label_id`),
          CONSTRAINT `FK_label_smart_label_id` FOREIGN KEY (`smart_label_id`) REFERENCES `labels` (`label_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT `FK_label_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          INDEX `smart_label_id`(smart_label_id),
          INDEX `user_id`(user_id)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");

      // Add default data to labels
      // Default: Unlabeled, Read, Watch, Listen, Buy, Eat & Drink, Do
      // Then add all the smart labels
      $this->db->query("INSERT INTO `labels` (`name`, `created_on`) VALUES ('Unlabeled', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`name`, `created_on`) VALUES ('Read', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`name`, `created_on`) VALUES ('Watch', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`name`, `created_on`) VALUES ('Listen', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`name`, `created_on`) VALUES ('Buy', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`name`, `created_on`) VALUES ('Eat & Drink', '" . date('Y-m-d H:i:s') . "')");

      // watch
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('3', 'youtube.com', '/watch', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('3', 'viddler.com', '/v', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('3', 'devour.com', '/video', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('3', 'ted.com', '/talks', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `created_on`) VALUES ('3', 'vimeo.com', '" . date('Y-m-d H:i:s') . "')");

      // Read
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('2', 'php.net', '/manual', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `created_on`) VALUES ('2', 'api.rubyonrails.org', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `created_on`) VALUES ('2', 'ruby-doc.org', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `created_on`) VALUES ('2', 'docs.jquery.com', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('2', 'codeigniter.com', '/user_guide', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('2', 'css-tricks.com', '/almanac', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('2', 'developer.apple.com', '/library', '" . date('Y-m-d H:i:s') . "')");

      // Eat & Drink
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('6', 'simplyrecipes.com', '/recipes', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `created_on`) VALUES ('6', 'allrecipes.com', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('6', 'epicurious.com', '/recipes', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('6', 'foodnetwork.com', '/recipes', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('6', 'food.com', '/recipe', '" . date('Y-m-d H:i:s') . "')");

      // Buy
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('5', 'svpply.com', '/item', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('5', 'amazon.com', '/gp/product', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `path`, `created_on`) VALUES ('5', 'fab.com', '/sale', '" . date('Y-m-d H:i:s') . "')");
      $this->db->query("INSERT INTO `labels` (`smart_label_id`, `domain`, `created_on`) VALUES ('5', 'zappos.com', '" . date('Y-m-d H:i:s') . "')");


      /*
      - Start updates for marks table
      */

      // Move all recipes to oembed column
      // oembed will be renamed embed for all embeddable content
      $marks = $this->db->query("SELECT id, recipe FROM `marks` WHERE recipe != '' AND LOWER(recipe) != 'none' AND recipe IS NOT NULL");
      if ($marks->num_rows() >= 1) {
        foreach ($marks->result() as $mark) {
          $res = $this->db->query("UPDATE `marks` SET `oembed` = '" . addslashes($mark->recipe) . "' WHERE `id` = '" . $mark->id . "'");
        }
      }

      // Update marks table
      $this->db->query("ALTER TABLE `marks` DROP COLUMN `recipe`");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `id` `mark_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented id for marks.'");
      $this->db->query("ALTER TABLE `marks` DROP PRIMARY KEY, ADD PRIMARY KEY (`mark_id`)");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `title` `title` varchar(150) NOT NULL COMMENT 'The title from the page being bookmarked.'");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `url` `url` text NOT NULL COMMENT 'The full url from the page being bookmarked.'");
      $this->db->query("ALTER TABLE `marks` ADD COLUMN `url_key` varchar(32) DEFAULT NULL COMMENT 'The MD5 checksum of the url for lookup purposes.' AFTER `url`");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `oembed` `embed` text DEFAULT NULL COMMENT 'The embedded content that could appear on the mark\'s info page.'");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `dateadded` `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The datetime this record was created.'");
      $this->db->query("ALTER TABLE `marks` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.' AFTER `created_on`");

      // Get all urls from marks table, create MD5 checksum, update record
      $marks = $this->db->query("SELECT mark_id, url FROM `marks`");

      // If any exists, move on
      if ($marks->num_rows() >= 1) {

        // Set up empty arrays to hold marks that may get deleted or changed
        $deleted = array();
        $changed = array();

        // Loop thru results
        foreach ($marks->result() as $mark) {

          // If no url, remove it from DB and add to deleted list
          if (empty($mark->url)) {
            $res = $this->db->query("DELETE FROM `marks` WHERE `mark_id` = '" . $mark->mark_id . "'");
            array_push($deleted, $mark->mark_id);
          }
          // Else, create checksum from url
          // See if any records already exist with this checksum
          // If so, get the current mark id and the one from DB that has matching checksum
          // Remove the current mark
          // Add to changed array where key is the current mark's id and the value is the record's mark id that was foun
          // If no matches, simply add the checksum for the current mark
          else {
            $checksum = md5($mark->url);
            $record = $this->db->query("SELECT mark_id FROM `marks` WHERE `url_key` = '" . $checksum . "' LIMIT 1");
            if ($record->num_rows() == 1) {
              $record = $record->row();
              $changed[$mark->mark_id] = $record->mark_id;
              $res = $this->db->query("DELETE FROM `marks` WHERE `mark_id` = '" . $mark->mark_id . "'");
            }
            else {
              $res = $this->db->query("UPDATE `marks` SET `url_key` = '" . $checksum . "' WHERE `mark_id` = '" . $mark->mark_id . "'");
            }
          }
        }

        // If the deleted array is not empty
        // Remove any user marks that are attached to it
        if (! empty($deleted)) {
          foreach ($deleted as $mark_id) {
            $res = $this->db->query("DELETE FROM `users_marks` WHERE `urlid` = '" . $mark_id . "'");
          }
        }

        // If the changed array is not empty
        // Update any user marks from the old mark id to the new one
        if (! empty($changed)) {
          foreach ($changed as $old_id => $new_id) {
            $res = $this->db->query("UPDATE `users_marks` SET `urlid` = '" . $new_id . "' WHERE `urlid` = '" . $old_id . "'");
          }
        }
      }

      // Finally, add a unique index for url key
      $this->db->query("ALTER TABLE `marks` ADD UNIQUE `url_key`(url_key)");


      /*
      - End updates for marks table
      */


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


      // Create marks_to_groups table
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `marks_to_groups` (
          `marks_to_group` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key.',
          `mark_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The mark_id from marks.mark_id',
          `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The user_id from users.user_id',
          `group_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The group_id from groups.group_id',
          PRIMARY KEY (`marks_to_group`),
          CONSTRAINT `FK_mtg_mark_id` FOREIGN KEY (`mark_id`) REFERENCES `marks` (`mark_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT `FK_mtg_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT `FK_mtg_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          INDEX `mark_id`(mark_id),
          INDEX `user_id`(user_id),
          INDEX `group_id`(group_id)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
      ");


      // Update users table
      // We are moving to active = 1 or 0 column, before altering table, switch active = 1, inactive = 0
      $res = $this->db->query("UPDATE `users` SET status = '0' WHERE status <> 'active'");
      $res = $this->db->query("UPDATE `users` SET status = '1' WHERE status = 'active'");
      $this->db->query("ALTER TABLE `users` DROP COLUMN `salt`");
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `status` `active` tinyint NOT NULL DEFAULT '0' COMMENT '1 = active, 0 = inactive'");
      $this->db->query("ALTER TABLE `users` ADD COLUMN `admin` tinyint NOT NULL DEFAULT '0' COMMENT '1 = yes, 0 = no' AFTER `active`");

      // Update users_smartlabels
      /*$this->db->query("RENAME TABLE `users_smartlabels` TO `user_smart_labels`");
      $this->db->query("ALTER TABLE `user_smart_labels` DROP COLUMN `path`");
      $this->db->query("ALTER TABLE `user_smart_labels` DROP COLUMN `label`");
      $this->db->query("ALTER TABLE `user_smart_labels` CHANGE COLUMN `id` `user_smart_label_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key.'");
      $this->db->query("ALTER TABLE `user_smart_labels` DROP PRIMARY KEY, ADD PRIMARY KEY (`user_smart_label_id`)");
      $this->db->query("ALTER TABLE `user_smart_labels` CHANGE COLUMN `userid` `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The user_id from users.user_id'");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD COLUMN `label_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The label_id from labels.label_id' AFTER `user_id`");
      $this->db->query("ALTER TABLE `user_smart_labels` CHANGE COLUMN `domain` `domain` varchar(255) NOT NULL COMMENT 'The hostname of the domain to match. In all lowercase.'");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD COLUMN `active` tinyint NOT NULL DEFAULT '1' COMMENT '0 = active, 1 = inactive' AFTER `domain`");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD COLUMN `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The datetime this record was created.' AFTER `active`");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.' AFTER `created_on`");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD INDEX `user_id`(user_id)");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD INDEX `label_id`(label_id)");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD CONSTRAINT `FK_usl_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE");
      $this->db->query("ALTER TABLE `user_smart_labels` ADD CONSTRAINT `FK_usl_label_id` FOREIGN KEY (`label_id`) REFERENCES `labels` (`label_id`)   ON UPDATE CASCADE ON DELETE CASCADE");*/

    }

    public function down()
    {
      set_time_limit(0);
      // Drop marks_to_groups table
      $this->db->query("DROP TABLE IF EXISTS `marks_to_groups`");

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

      // Revert marks table
      $this->db->query("ALTER TABLE `marks` DROP INDEX `url_key`");
      $this->db->query("ALTER TABLE `marks` DROP COLUMN `last_updated`");
      $this->db->query("ALTER TABLE `marks` DROP COLUMN `url_key`");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `mark_id` `id` int(11) NOT NULL AUTO_INCREMENT");
      $this->db->query("ALTER TABLE `marks` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `title` `title` text NOT NULL");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `url` `url` text NOT NULL");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `embed` `oembed` text DEFAULT NULL");
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `created_on` `dateadded` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
      $this->db->query("ALTER TABLE `marks` ADD COLUMN `recipe` text DEFAULT NULL AFTER `oembed`");

      // Move recipes back to the recipe column
      $marks = $this->db->query("SELECT id, oembed FROM `marks` WHERE oembed LIKE '%hrecipe%'");
      if ($marks->num_rows() >= 1) {
        foreach ($marks->result() as $mark) {
          $res = $this->db->query("UPDATE `marks` SET recipe = '" . addslashes($mark->oembed) . "', oembed = NULL WHERE `id` = '" . $mark->id . "'");
        }
      }

      // Revert user smart labels

      // Drop labels table
      $this->db->query("DROP TABLE IF EXISTS `labels`");

      // Revert users table
      // Revert active to status, 0 = inactive, 1 = active
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `active` `status` varchar(25) NOT NULL DEFAULT 'inactive'");
      $res = $this->db->query("UPDATE `users` SET status = 'inactive' WHERE status <> '1'");
      $res = $this->db->query("UPDATE `users` SET status = 'active' WHERE status = '1'");
      $this->db->query("ALTER TABLE `users` DROP COLUMN `admin`");
      $this->db->query("ALTER TABLE `users` ADD COLUMN `salt` varchar(50) DEFAULT NULL COMMENT 'The salt used to generate password.' AFTER `password`");

      // Revert user smart labels
      //$this->db->query("RENAME TABLE `user_smart_labels` TO `users_smartlabels`");

    }

}