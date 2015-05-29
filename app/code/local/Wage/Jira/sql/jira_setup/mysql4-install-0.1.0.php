<?php
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('jira/projects')};
CREATE TABLE {$this->getTable('jira/projects')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `project_name` varchar(255) NOT NULL default '',
  `project_id` int(11),
  `key` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


    ");

$installer->endSetup();
