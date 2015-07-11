SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `ci_sessions`;
DROP TABLE IF EXISTS `ips_on_hold`;
DROP TABLE IF EXISTS `login_errors`;
DROP TABLE IF EXISTS `denied_access`;
DROP TABLE IF EXISTS `username_or_email_on_hold`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `appointment_days`;
DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `resource_calendars`;
DROP TABLE IF EXISTS `resource_group_members`;
DROP TABLE IF EXISTS `resource_groups`;
DROP TABLE IF EXISTS `resource_managers`;
DROP TABLE IF EXISTS `resource_types`;
DROP TABLE IF EXISTS `resources`;
DROP TABLE IF EXISTS `scheduled_resources`;
DROP TABLE IF EXISTS `scheduled_slots`;
DROP TABLE IF EXISTS `schedules`;
DROP TABLE IF EXISTS `time_blocks`;
DROP TABLE IF EXISTS `user_contacts`;

-- Community Auth - MySQL table install
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `ai` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`ai`),
  UNIQUE KEY `ci_sessions_id_ip` (`id`,`ip_address`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ips_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `login_errors` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `denied_access` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `username_or_email_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(12) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pass` varchar(60) NOT NULL,
  `user_salt` varchar(32) NOT NULL,
  `user_last_login` datetime DEFAULT NULL,
  `user_login_time` datetime DEFAULT NULL,
  `user_session_id` varchar(40) DEFAULT NULL,
  `user_date` datetime NOT NULL,
  `user_modified` datetime NOT NULL,
  `user_agent_string` varchar(32) DEFAULT NULL,
  `user_level` tinyint(2) unsigned NOT NULL,
  `user_banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- conference-manager - MySQL table install

CREATE TABLE IF NOT EXISTS `resource_calendars` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `name` varchar(75),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `appointment_days` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `schedule_id` int(11) unsigned,
 `resource_calendar_id` int(11) unsigned,
 `schedule_date` date, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `schedules` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `name` varchar(40),
 `interval_in_minutes` int(11),
 `duration_in_minutes` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `time_blocks` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `schedule_id` int(11) unsigned,
 `start_hour` int(11),
 `start_minute` int(11),
 `duration_in_minutes` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `user_contacts` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `user_id` int(11) unsigned,
 `contact_type` smallint(8),
 `contact_value` varchar(75),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `resource_types` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `name` varchar(75),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `resources` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `name` varchar(75),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `resource_groups` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `name` varchar(75),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `resource_group_members` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `resource_group_id` int(11) unsigned,
 `resource_id` int(11) unsigned,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `scheduled_resources` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `resource_type_id` int(11) unsigned,
 `resource_id` int(11) unsigned,
 `location` varchar(75),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `resource_managers` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `resource_id` int(11),
 `user_id` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `scheduled_slots` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `appointment_day_id` int(11) unsigned,
 `time_block_id` int(11) unsigned,
 `schedule_resource_id` int(11) unsigned,
 `available` tinyint(1) unsigned,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `reservations` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `scheduled_slot_id` int(11) unsigned,
 `user_id` int(11) unsigned,
 `last_notified_at` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

