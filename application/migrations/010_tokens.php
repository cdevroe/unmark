<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Tokens extends Plain_Migration
{

    public function __construct()
    {
      parent::__construct();
    }

    public function up()
    { // Create tables
        $this->db->query("
          CREATE TABLE IF NOT EXISTS `tokens` (
          `token_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Token ID auto generated',
          `user_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT 'User ID that token belongs to',
          `token_type` ENUM('FORGOT_PASSWORD') NOT NULL  COMMENT 'Token type enum',
          `token_value` VARCHAR(64) NOT NULL COMMENT 'Generated token',
          `created_on` DATETIME NOT NULL COMMENT 'Creation date',
          `valid_until` DATETIME NOT NULL COMMENT 'Expiration date',
          `active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active flag',
          `used_on` DATETIME NULL DEFAULT NULL COMMENT 'Date when token was successfully used',
           PRIMARY KEY (`token_id`),
           UNIQUE INDEX `token_value_UNIQUE` (`token_value` ASC),
           INDEX `token_type_user_id_active_IDX` (`token_type` ASC, `user_id` ASC, `active` ASC),
           CONSTRAINT `tokens_user_id_FK`
           FOREIGN KEY (`user_id`)
           REFERENCES `users` (`user_id`)
           ON DELETE CASCADE
           ON UPDATE CASCADE) ENGINE InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }

    public function down()
    {
        // Drop tables
        $this->dbforge->drop_table('tokens');
    }
}
