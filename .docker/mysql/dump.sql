CREATE TABLE IF NOT EXISTS `session_data` (
    `id` VARCHAR(35) NOT NULL COLLATE 'utf8_general_ci',
    `data` TEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `created_on` INT(10) UNSIGNED NOT NULL,
    `modified_on` INT(10) UNSIGNED NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE
) COLLATE='utf8_general_ci' ENGINE=InnoDB;


CREATE DATABASE IF NOT EXISTS `phalcon5_cli` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `phalcon5_cli`;

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `script_name` varchar(1024) NOT NULL DEFAULT '',
  `params` varchar(1024) DEFAULT '',
  `task_name` varchar(50) DEFAULT NULL,
  `action_name` varchar(50) DEFAULT NULL,
  `server_name` varchar(30) DEFAULT '',
  `server_user` varchar(30) DEFAULT '',
  `start_time` datetime DEFAULT NULL,
  `stop_time` datetime DEFAULT NULL,
  `state` enum('RUNNING','SUCCESS','FAIL') DEFAULT 'RUNNING',
  `exit_status` int(10) unsigned DEFAULT NULL,
  `stdout` text DEFAULT NULL,
  `stderr` text DEFAULT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `task_runtime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(2048) NOT NULL DEFAULT '',
  `file` varchar(1024) DEFAULT '',
  `line` smallint(5) unsigned DEFAULT NULL,
  `error_type` int(10) unsigned NOT NULL DEFAULT 0,
  `create_time` datetime DEFAULT NULL,
  `server_name` varchar(100) DEFAULT NULL,
  `execution_script` varchar(1024) NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `ip_address` varchar(16) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
