<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_nilai extends Plain_Migration
{

	public function up()
	{	// Create tables
		if ( !$this->db->table_exists('groups') ) {
			$this->db->query("CREATE TABLE `groups` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `createdby` int(11) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `description` text,
			  `urlname` varchar(255),
			  `uid` text NOT NULL,
			  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		}

		if ( !$this->db->table_exists('groups_invites') ) {
			$this->db->query("CREATE TABLE `groups_invites` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `groupid` int(11) NOT NULL,
			  `emailaddress` text NOT NULL,
			  `invitedby` int(11) NOT NULL,
			  `dateinvited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `status` varchar(255),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		}
		if ( !$this->db->table_exists('marks') ) {
			$this->db->query("CREATE TABLE `marks` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` text NOT NULL,
			  `url` text NOT NULL,
			  `oembed` text,
			  `recipe` text,
			  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		}
		if ( !$this->db->table_exists('users') ) {
			$this->db->query("CREATE TABLE `users` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `emailaddress` varchar(255) NOT NULL,
			  `password` varchar(255) NOT NULL,
			  `datejoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `status` varchar(255) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `emailaddress` (`emailaddress`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		}
		if ( !$this->db->table_exists('users_groups') ) {
			$this->db->query("CREATE TABLE `users_groups` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `groupid` int(11) NOT NULL,
			  `userid` int(11) NOT NULL,
			  `datejoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `status` varchar(255),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		}
		if ( !$this->db->table_exists('users_marks') ) {
			$this->db->query("CREATE TABLE `users_marks` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `userid` int(11) NOT NULL,
			  `urlid` int(11) NOT NULL,
			  `tags` text,
			  `addedby` int(11) NOT NULL,
			  `groups` int(11),
			  `note` text,
			  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `status` varchar(255),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		}
		if ( !$this->db->table_exists('users_smartlabels') ) {
			$this->db->query("CREATE TABLE `users_smartlabels` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `userid` int(11) NOT NULL,
			  `domain` varchar(255) NOT NULL,
			  `path` varchar(255) NOT NULL,
			  `label` varchar(255) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		}
	}

	public function down()
	{
	    // Drop tables
	    $this->dbforge->drop_table('groups');
	    $this->dbforge->drop_table('groups_invites');
	    $this->dbforge->drop_table('marks');
	    $this->dbforge->drop_table('users');
	    $this->dbforge->drop_table('users_groups');
	    $this->dbforge->drop_table('users_marks');
	    $this->dbforge->drop_table('users_smartlabels');
	}
}

/* End of file 001_create_nilai.php */
/* Location: ./application/migrations/001_create_nilai.php */