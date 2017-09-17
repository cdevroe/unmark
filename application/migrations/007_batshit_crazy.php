<?php defined('BASEPATH') OR exit("No direct script access allowed");

class Migration_Batshit_Crazy extends Plain_Migration
{

    public function __construct()
    {
      parent::__construct();
    }

    public function up()
    {

      set_time_limit(0);

      // Check for all tables required for this migration
      parent::checkForTables(array('marks', 'migrations', 'users', 'users_marks', 'users_smartlabels'));

      // Check all columns need per table are found
      // marks, users and users_marks
      parent::checkForColumns(array('recipe', 'id', 'title', 'url', 'oembed', 'dateadded'), 'marks');
      parent::checkForColumns(array('salt', 'status', 'date_joined', 'admin'), 'users');
      parent::checkForColumns(array('addedby', 'groups', 'status', 'id', 'urlid', 'userid', 'tags', 'note', 'dateadded', 'datearchived'), 'users_marks');

      // Make sure all tables are INNODB, UTF-8
      // Original migration they were not
      // If anyone download that version and ran successfully, some keys may not be created correctly
      $this->db->query("ALTER TABLE `marks` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `migrations` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `users_marks` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
      $this->db->query("ALTER TABLE `users_smartlabels` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

      // Drop all group releated tables
      $this->db->query("DROP TABLE IF EXISTS `groups`");
      $this->db->query("DROP TABLE IF EXISTS `groups_invites`");
      $this->db->query("DROP TABLE IF EXISTS `users_groups`");

       /*
      - Start updates for labels table
      - Import default system labels
      - Import any user smart labels
      */

      // Create new labels table
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `labels` (
          `label_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key.',
          `smart_label_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'If a smart label, the label_id to use if a match is found.',
          `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'If a label but owned by a user, place the users.user_id here.',
          `name` varchar(50) DEFAULT NULL COMMENT 'The name of the label.',
          `slug` varchar(50) DEFAULT NULL COMMENT 'The slug of the label.',
          `domain` varchar(255) DEFAULT NULL COMMENT 'The hostname of the domain to match. Keep in all lowercase.',
          `path` varchar(100) DEFAULT NULL COMMENT 'The path to find to for smartlabels to match. If null, just match host.',
          `smart_key` varchar(32) DEFAULT NULL COMMENT 'MD5 checksum of domain and path for lookup purposes.',
          `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 is active, 0 if not. Defaults to 1.',
          `created_on` datetime NOT NULL,
          `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.',
          PRIMARY KEY (`label_id`),
          CONSTRAINT `FK_label_smart_label_id` FOREIGN KEY (`smart_label_id`) REFERENCES `labels` (`label_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT `FK_label_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          UNIQUE `slug`(slug),
          INDEX `smart_label_id`(smart_label_id),
          INDEX `user_id`(user_id),
          INDEX `smart_key`(smart_key)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");

      // Add default data to labels
      // Default: Unlabeled, Read, Watch, Listen, Buy, Eat & Drink, Do
      // Then add all the smart labels
      $default_labels = array(
        'unlabeled' => array('label_id' => 1, 'name' => 'Unlabeled'),
        'read'      => array('label_id' => 2, 'name' => 'Read'),
        'watch'     => array('label_id' => 3, 'name' => 'Watch'),
        'listen'    => array('label_id' => 4, 'name' => 'Listen'),
        'buy'       => array('label_id' => 5, 'name' => 'Buy'),
        'eatdrink'  => array('label_id' => 6, 'name' => 'Eat & Drink'),
        'do'        => array('label_id' => 7, 'name' => 'Do')
      );

      foreach ($default_labels as $label) {
        $slug = generateSlug($label['name']);
        $this->db->query("INSERT INTO `labels` (`label_id`, `name`, `slug`, `created_on`) VALUES ('" . $label['label_id'] . "', '" . $label['name'] . "', '" . $slug . "', '" . date('Y-m-d H:i:s') . "')");
      }

      // Start default system smart labels
      $smart_labels = array(
        // read
        '2' => array('php.net', 'api.rubyonrails.org', 'ruby-doc.org', 'docs.jquery.com', 'codeigniter.com', 'css-tricks.com', 'developer.apple.com'),
        // watch
        '3' => array('youtube.com', 'viddler.com', 'devour.com', 'ted.com', 'vimeo.com'),
        //buy
        '5' => array('svpply.com', 'amazon.com', 'fab.com', 'zappos.com'),
        // eat & drink
        '6' => array('simplyrecipes.com', 'allrecipes.com', 'epicurious.com', 'foodnetwork.com', 'food.com')
      );

      // Loop thru smart labels and add them up
      foreach ($smart_labels as $label_id => $arr) {
        foreach ($arr as $domain) {
            $smart_info = getSmartLabelInfo('http://' . $domain);
            $this->db->query("
              INSERT INTO `labels`
              (`smart_label_id`, `domain`, `smart_key`, `created_on`)
              VALUES ('" . $label_id . "', '" . $smart_info['domain'] . "', '" . $smart_info['key'] . "', '" . date('Y-m-d H:i:s') . "')
            ");
        }
      }

      // Now open users_smartlabels, import into labels
      $labels = $this->db->query("SELECT userid, domain, path, label FROM users_smartlabels");
      if ($labels->num_rows() >= 1) {
        foreach ($labels->result() as $label) {

          // Proceed only if domain is not empty
          $current_label = strtolower($label->label);
          if (! empty($label->domain) && ! empty($label->userid) && is_numeric($label->userid) && ! empty($current_label) && array_key_exists($current_label, $default_labels)) {

            // Standardize label domain, path and smart key
            $smart_info = getSmartLabelInfo($label->domain . $label->path);
            $domain     = $smart_info['domain'];
            $path       = $smart_info['path'];
            $md5        = $smart_info['key'];
            $label_id = $default_labels[$current_label]['label_id'];
            $path     = (empty($label->path)) ? '' : "'" . $label->path . "', ";
            $path_c   = (empty($label->path)) ? '' : "`path`, ";

            // Find an occurences of this record
            $q  = $this->db->query("
              SELECT COUNT(*) FROM labels
              WHERE user_id = '" . $label->userid . "'
              AND domain = '" . $domain . "'
              AND smart_label_id = '" . $label_id . "'
            ");

            $row   = $q->row();
            $total = (integer) $row->{'COUNT(*)'};

            // If not found, add it
            if ($total < 1) {
              $res = $this->db->query("
                INSERT INTO `labels`
                (`smart_label_id`, `user_id`, `domain`, " . $path_c . "`smart_key`, `created_on`)
                VALUES ('" . $label_id . "', '" . $label->userid . "', '" . $domain . "', " . $path . "'" . $md5 . "', '" . date('Y-m-d H:i:s') . "')
              ");
            }

          }
        }
      }

       /*
      - End labels import
      */


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
      $this->db->query("ALTER TABLE `marks` CHANGE COLUMN `dateadded` `created_on` datetime NOT NULL COMMENT 'The datetime this record was created.'");
      $this->db->query("ALTER TABLE `marks` ADD COLUMN `embed_processed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = yes, 0 if not. Defaults to 0.' AFTER `embed`");
      $this->db->query("ALTER TABLE `marks` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.' AFTER `created_on`");

      // Update embed column to NULL if 'None'
      $res = $this->db->query("UPDATE `marks` SET embed = NULL WHERE LOWER(embed) = 'none'");

      // Set embed to processed if NOT NULL OR isn't in LAST DAY
      $res = $this->db->query("UPDATE `marks` SET embed_processed = '1' WHERE embed IS NOT NULL OR UNIX_TIMESTAMP(created_on) < '" . strtotime('today') . "'");

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



      // Update users table
      // We are moving to active = 1 or 0 column, before altering table, switch active = 1, inactive = 0
      $res = $this->db->query("UPDATE `users` SET status = '0' WHERE status <> 'active'");
      $res = $this->db->query("UPDATE `users` SET status = '1' WHERE status = 'active'");
      $this->db->query("ALTER TABLE `users` DROP COLUMN `salt`");
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `status` `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = active, 0 = inactive' AFTER `password`");
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `date_joined` `created_on` datetime NOT NULL COMMENT 'The datetime the account was created'");
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `admin` `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = admin, 0 = admin' AFTER `active`");
      $this->db->query("ALTER TABLE `users` ADD COLUMN `user_token` varchar(30) NOT NULL DEFAULT '0' COMMENT 'Unique user token.' AFTER `password`");

      // Add user tokens
      // Must check if they exist already
      $users = $this->db->query("SELECT user_id FROM `users`");
      if ($users->num_rows() >= 1) {
        foreach ($users->result() as $obj) {
          do {
                $user_token = generateToken(30);
                $res = $this->db->query("SELECT user_id FROM `users` WHERE user_token = '" . $user_token . "'");
          } while (isset($res->user_id));

          // Update user record
          $res = $this->db->query("UPDATE `users` SET user_token = '" . $user_token . "' WHERE user_id = '" . $obj->user_id . "'");
        }
      }

      // Update user token column to be no default
      // Add unique index
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `user_token` `user_token` varchar(30) NOT NULL COMMENT 'Unique user token.'");
      $this->db->query("ALTER TABLE `users` ADD UNIQUE `user_token`(user_token)");


      // Remove the users_smartlabels table
      $this->db->query("DROP TABLE IF EXISTS `users_smartlabels`");

      /*
      - Start users_to_marks transistion
      */

      // Update `tags` field to a single numeric in order to translate to a FK for labels
      // Move group id to user_marks_to_groups table

      $marks = $this->db->query("SELECT id, urlid, tags, groups, status FROM `users_marks`");
      $archived = array();
      $delete   = array();
      if ($marks->num_rows() >= 1) {

        // Loop thru results
        foreach ($marks->result() as $mark) {
          $tags      = explode(',', $mark->tags);
          $label_id  = 1;
          $group_id  = $mark->groups;
          $mark_id   = $mark->id;

          // Figure if we should delete this mark
          $res = $this->db->query("SELECT mark_id FROM marks WHERE mark_id = '" . $mark->urlid . "' LIMIT 1");
          if ($res->num_rows() < 1) {
            array_push($delete, $mark_id);
          }

          // If it was archived, save here to update later
          if (strtolower($mark->status) == 'archive') {
            array_push($archived, $mark_id);
          }

          foreach ($tags as $tag) {
            $tag = trim(strtolower($tag));
            if (array_key_exists($tag, $default_labels)) {
              $label_id = $default_labels[$tag]['label_id'];
              break;
            }
          }

          // Update tags
          $res = $this->db->query("UPDATE `users_marks` SET tags = '" . $label_id . "' WHERE id = '" . $mark_id . "'");
        }
      }

      // Remove any urlids in users_marks that do NOT exist in marks table
      // If we skip this step the FK creation will fail
      // Data is a bit dirty because when marks were deleted, it never deleted their children, leaving little orphan annies
      // Fix it!
      if (! empty($delete)) {
        foreach ($delete as $id) {
          $res = $this->db->query("DELETE FROM `users_marks` WHERE id = '" . $id . "'");
        }
      }


      // Update table name & structure
      $this->db->query("RENAME TABLE `users_marks` TO `users_to_marks`");
      $this->db->query("ALTER TABLE `users_to_marks` DROP COLUMN `addedby`");
      $this->db->query("ALTER TABLE `users_to_marks` DROP COLUMN `groups`");
      $this->db->query("ALTER TABLE `users_to_marks` DROP COLUMN `status`");
      $this->db->query("ALTER TABLE `users_to_marks` CHANGE COLUMN `id` `users_to_mark_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key.'");
      $this->db->query("ALTER TABLE `users_to_marks` DROP PRIMARY KEY, ADD PRIMARY KEY (`users_to_mark_id`)");
      $this->db->query("ALTER TABLE `users_to_marks` CHANGE COLUMN `urlid` `mark_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The mark_id from marks.mark_id.' AFTER `users_to_mark_id`");
      $this->db->query("ALTER TABLE `users_to_marks` CHANGE COLUMN `userid` `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The user_id from users.user_id' AFTER `mark_id`");
      $this->db->query("ALTER TABLE `users_to_marks` CHANGE COLUMN `tags` `label_id` bigint(20) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'The label_id from labels.label_id.'");
      $this->db->query("ALTER TABLE `users_to_marks` CHANGE COLUMN `note` `notes` text DEFAULT NULL");
      $this->db->query("ALTER TABLE `users_to_marks` CHANGE COLUMN `dateadded` `created_on` datetime NOT NULL COMMENT 'The datetime this record was created.'");
      $this->db->query("ALTER TABLE `users_to_marks` CHANGE COLUMN `datearchived` `archived_on` datetime DEFAULT NULL COMMENT 'The datetime this mark was archived. NULL = not archived.'");
      $this->db->query("ALTER TABLE `users_to_marks` ADD COLUMN `last_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'The last datetime this record was updated.' AFTER `archived_on`");
      $this->db->query("ALTER TABLE `users_to_marks` ADD INDEX `mark_id`(mark_id)");
      $this->db->query("ALTER TABLE `users_to_marks` ADD INDEX `user_id`(user_id)");
      $this->db->query("ALTER TABLE `users_to_marks` ADD INDEX `label_id`(label_id)");
      $this->db->query("ALTER TABLE `users_to_marks` ADD CONSTRAINT `FK_utm_mark_id` FOREIGN KEY (`mark_id`) REFERENCES `marks` (`mark_id`)   ON UPDATE CASCADE ON DELETE CASCADE");
      $this->db->query("ALTER TABLE `users_to_marks` ADD CONSTRAINT `FK_utm_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE");
      $this->db->query("ALTER TABLE `users_to_marks` ADD CONSTRAINT `FK_utm_label_id` FOREIGN KEY (`label_id`) REFERENCES `labels` (`label_id`)   ON UPDATE CASCADE ON DELETE CASCADE");

      // Fix archived marks
      // First set all date_archived fields to NULL properly
      $res = $this->db->query("UPDATE `users_to_marks` SET archived_on = NULL WHERE archived_on IS NOT NULL");
      if (! empty($archived)) {
        foreach ($archived as $users_to_mark_id) {
          $res = $this->db->query("UPDATE `users_to_marks` SET archived_on = '" . date('Y-m-d H:i:s') . "' WHERE users_to_mark_id = '" . $users_to_mark_id . "'");
        }
      }

      /*
      - End users_to_marks transistion
      */

      // Create new tags table
      $this->db->query("
        CREATE TABLE `tags` (
          `tag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key fro tags.',
          `name` varchar(100) NOT NULL COMMENT 'The tag name.',
          `slug` varchar(100) NOT NULL COMMENT 'The tag slug name.',
          PRIMARY KEY (`tag_id`),
          INDEX `name`(name),
          UNIQUE `slug`(slug)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");

      // Create new user_marks_to_tags table
      $this->db->query("
        CREATE TABLE `user_marks_to_tags` (
          `user_marks_to_tag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The auto-incremented key.',
          `tag_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The tag id from tags.tag_id',
          `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The user id from users.user_id',
          `users_to_mark_id` bigint(20) UNSIGNED NOT NULL COMMENT 'The user mark id from users_to_marks.users_to_mark_id',
          PRIMARY KEY (`user_marks_to_tag_id`),
          INDEX `users_to_mark_id`(users_to_mark_id),
          INDEX `tag_id`(tag_id),
          INDEX `user_id`(user_id),
          CONSTRAINT `FK_umtt_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT `FK_umtt_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)   ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT `FK_umtt_mark_id` FOREIGN KEY (`users_to_mark_id`) REFERENCES `users_to_marks` (`users_to_mark_id`)   ON UPDATE CASCADE ON DELETE CASCADE
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");
    }

    public function down()
    {
      set_time_limit(0);

      // Check for all tables required for this migration
      parent::checkForTables(array('labels', 'marks', 'migrations', 'tags' , 'users', 'user_marks_to_tags', 'users_to_marks'));

      // Check all columns need per table are found
      // marks, users and users_marks
      parent::checkForColumns(array('last_updated', 'url_key', 'mark_id', 'title', 'url', 'embed', 'created_on', 'embed_processed'), 'marks');
      parent::checkForColumns(array('active', 'created_on'), 'users');
      parent::checkForColumns(array('last_updated', 'users_to_mark_id', 'mark_id', 'user_id', 'label_id', 'notes', 'created_on', 'archived_on'), 'users_to_marks');

      // Set default label/tags
      $default_labels = array('unlabeled', 'read', 'watch', 'listen', 'buy', 'eatdrink', 'do');

      // Drop user_marks_to_tags
      $this->db->query("DROP TABLE IF EXISTS `user_marks_to_tags`");

      // Drop tags
      $this->db->query("DROP TABLE IF EXISTS `tags`");

      /*
      - Start users_marks transistion
      */

      // Find any archived marks and save id for later use
      $archived = array();
      $marks = $this->db->query("SELECT users_to_mark_id FROM `users_to_marks` WHERE archived_on IS NOT NULL");
      if ($marks->num_rows() >= 1) {

        // Loop thru results
        foreach ($marks->result() as $mark) {
          array_push($archived, $mark->users_to_mark_id);
        }
      }

      // Change all date_archived back to 0000-00-00 00:00:00 like they were originally
      $res = $this->db->query("UPDATE `users_to_marks` SET archived_on = '0000-00-00 00:00:00' WHERE archived_on IS NULL");

      // Revert users_marks table
      $this->db->query("RENAME TABLE `users_to_marks` TO `users_marks`");
      $this->db->query("ALTER TABLE `users_marks` DROP FOREIGN KEY `FK_utm_mark_id`");
      $this->db->query("ALTER TABLE `users_marks` DROP FOREIGN KEY `FK_utm_user_id`");
      $this->db->query("ALTER TABLE `users_marks` DROP FOREIGN KEY `FK_utm_label_id`");
      $this->db->query("ALTER TABLE `users_marks` DROP INDEX `mark_id`");
      $this->db->query("ALTER TABLE `users_marks` DROP INDEX `label_id`");
      $this->db->query("ALTER TABLE `users_marks` DROP INDEX `user_id`");
      $this->db->query("ALTER TABLE `users_marks` DROP COLUMN `last_updated`");
      $this->db->query("ALTER TABLE `users_marks` CHANGE COLUMN `users_to_mark_id` `id` int(11) NOT NULL AUTO_INCREMENT");
      $this->db->query("ALTER TABLE `users_marks` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)");
      $this->db->query("ALTER TABLE `users_marks` CHANGE COLUMN `mark_id` `urlid` int(11) NOT NULL");
      $this->db->query("ALTER TABLE `users_marks` CHANGE COLUMN `user_id` `userid` int(11) NOT NULL AFTER `id`");
      $this->db->query("ALTER TABLE `users_marks` CHANGE COLUMN `label_id` `tags` text DEFAULT NULL");
      $this->db->query("ALTER TABLE `users_marks` CHANGE COLUMN `notes` `note` text DEFAULT NULL");
      $this->db->query("ALTER TABLE `users_marks` CHANGE COLUMN `created_on` `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
      $this->db->query("ALTER TABLE `users_marks` CHANGE COLUMN `archived_on` `datearchived` timestamp NOT NULL");
      $this->db->query("ALTER TABLE `users_marks` ADD COLUMN `status` varchar(255) DEFAULT NULL AFTER `dateadded`");
      $this->db->query("ALTER TABLE `users_marks` ADD COLUMN `addedby` int(11) NOT NULL AFTER `tags`");
      $this->db->query("ALTER TABLE `users_marks` ADD COLUMN `groups` int(11) NOT NULL AFTER `addedby`");


      // Revert archived marks
      $res = $this->db->query("UPDATE `users_marks` SET status = NULL WHERE status IS NOT NULL");
      if (! empty($archived)) {
        foreach ($archived as $id) {
          $res = $this->db->query("UPDATE `users_marks` SET status = 'archive' WHERE id = '" . $id . "'");
        }
      }

      // Add tags back
      $marks = $this->db->query("SELECT id, tags FROM `users_marks`");
      if ($marks->num_rows() >= 1) {

        // Loop thru results and update
        foreach ($marks->result() as $mark) {
          $label_id = $mark->tags - 1;
          $label    = (array_key_exists($label_id, $default_labels)) ? $default_labels[$label_id] : 'unlabeled';
          $res      = $this->db->query("UPDATE `users_marks` SET tags = '" . $label . "' WHERE id = '" . $mark->id . "'");
        }
      }

      /*
      - End users_marks transistion
      */



      // Add groups_invites table back
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `groups_invites` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `groupid` int(11) NOT NULL,
          `emailaddress` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
          `invitedby` int(11) NOT NULL,
          `dateinvited` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");

      // Add groups table back
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `groups` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `createdby` int(11) NOT NULL,
          `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
          `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `urlname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          `uid` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
          `datecreated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");

      // Add users_groups table back
      $this->db->query("
        CREATE TABLE `users_groups` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `groupid` int(11) NOT NULL,
          `userid` int(11) NOT NULL,
          `datejoined` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");


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
      $this->db->query("ALTER TABLE `marks` DROP COLUMN `embed_processed`");

      $res = $this->db->query("UPDATE `marks` SET oembed = 'None' WHERE oembed IS NULL");

      // Move recipes back to the recipe column
      $marks = $this->db->query("SELECT id, oembed FROM `marks` WHERE oembed LIKE '%hrecipe%'");
      if ($marks->num_rows() >= 1) {
        foreach ($marks->result() as $mark) {
          $res = $this->db->query("UPDATE `marks` SET recipe = '" . addslashes($mark->oembed) . "', oembed = NULL WHERE `id` = '" . $mark->id . "'");
        }
      }

      // Revert user smart labels
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `users_smartlabels` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `userid` int(11) NOT NULL,
          `domain` varchar(255) NOT NULL,
          `path` varchar(255) NOT NULL,
          `label` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
      ");

      // Get all user smart labels from labels table and add back to users_smartlabels
      $labels = $this->db->query("SELECT smart_label_id, user_id, domain, path FROM `labels` WHERE smart_label_id IS NOT NULL AND user_id IS NOT NULL");
      if ($labels->num_rows() >= 1) {
        foreach ($labels->result() as $label) {

          // Proceed only if domain is not empty
          $label_id      = $label->smart_label_id - 1;
          $current_label = (isset($default_labels[$label_id])) ? $default_labels[$label_id] : null;
          if (! empty($current_label)) {
            $res = $this->db->query("
              INSERT INTO `users_smartlabels`
              (`userid`, `domain`, `path`, `label`)
              VALUES ('" . $label->user_id . "', '" . $label->domain . "', '" . $label->path . "', '" . $current_label . "')
            ");
          }
        }
      }

      // Drop labels table
      $this->db->query("DROP TABLE IF EXISTS `labels`");

      // Revert users table
      // Revert active to status, 0 = inactive, 1 = active
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `active` `status` varchar(25) NOT NULL DEFAULT 'inactive'");
      $res = $this->db->query("UPDATE `users` SET status = 'inactive' WHERE status <> '1'");
      $res = $this->db->query("UPDATE `users` SET status = 'active' WHERE status = '1'");
      $this->db->query("ALTER TABLE `users` ADD COLUMN `salt` varchar(50) DEFAULT NULL COMMENT 'The salt used to generate password.' AFTER `password`");
      $this->db->query("ALTER TABLE `users` CHANGE COLUMN `created_on` `date_joined` datetime NOT NULL");
      $this->db->query("ALTER TABLE `users` DROP INDEX `user_token`");
      $this->db->query("ALTER TABLE `users` DROP COLUMN `user_token`");

    }

}
