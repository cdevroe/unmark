<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Label_Sort extends Plain_Migration
{

    public function __construct()
    {
        parent::__construct();
        parent::checkForTables('labels');
    }

    public function up()
    {
        // Add order column
        $this->db->query("ALTER TABLE `labels` ADD COLUMN `order` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'The order to sort static system level labels.' AFTER `slug`");

        // Now update all current system level labels to add correct order
        $slugs = array('read', 'watch', 'listen', 'buy', 'eat-drink', 'do', 'unlabeled');
        foreach ($slugs as $k => $slug) {
            $order = $k + 1;
            $this->db->query("UPDATE `labels` SET labels.order = '" . $order . "' WHERE labels.slug = '" . $slug . "'");
        }

    }

    public function down()
    {
        parent::checkForColumns('order', 'labels');
        $this->db->query("ALTER TABLE `labels` DROP COLUMN `order`");
    }
}