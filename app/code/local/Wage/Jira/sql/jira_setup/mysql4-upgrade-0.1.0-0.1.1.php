<?php
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('jira/tickets')};
CREATE TABLE {$this->getTable('jira/tickets')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `issues_id` int(11) NOT NULL,
  `issues_key` varchar(255) NOT NULL default '',
  `summary` text NULL default '',
  `description` text NULL default '',
  `issue_type` varchar(255) NULL default '',
  `project_name` varchar(255) NOT NULL default '',
  `project_key` varchar(255) NOT NULL default '',
  `assignee` varchar(255) NULL default '',
  `reporter` varchar(255) NULL default '',
  `resolution` varchar(255) NULL default '',
  `priority` varchar(255) NULL default '',
  `status` varchar(255) NULL default '',
  `created_at` datetime NULL,
  `updated_at` datetime NULL,
  `codebase_ticket_id` int(11) NOT NULL,
  `codebase_permalink` varchar(255) NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();
