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
	`first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
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

-- resource_calendar is for a specific event series
-- like "2015 Bacich Intake Conferences"
CREATE TABLE IF NOT EXISTS `resource_calendars` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `resource_type_id` int(10) unsigned NOT NULL,
 `name` varchar(40) NOT NULL,
 `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;
CREATE UNIQUE INDEX `rcal_name` ON `resource_calendars` (`name`);

CREATE TABLE IF NOT EXISTS `appointment_days` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `schedule_id` int(10) unsigned NOT NULL,
 `resource_calendar_id` int(10) unsigned NOT NULL,
 `schedule_date` date NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `schedules` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `name` varchar(40) NOT NULL,
 `description` varchar(255) NOT NULL,
 `interval_in_minutes` smallint(5) unsigned NOT NULL,
 `duration_in_minutes` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;
CREATE UNIQUE INDEX `sched_name` ON `schedules` (`name`);

CREATE TABLE IF NOT EXISTS `time_blocks` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `schedule_id` int(10) unsigned NOT NULL,
 `start_hour` tinyint(3) unsigned NOT NULL,
 `start_minute` tinyint(3) unsigned NOT NULL,
 `duration_in_minutes` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `user_contacts` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `user_id` int(10) unsigned NOT NULL,
 `contact_type` tinyint(3) unsigned NOT NULL,
 `contact_value` varchar(40) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

-- resource_type is like "Home Room Conference"
-- versus "Alternate Conference"
CREATE TABLE IF NOT EXISTS `resource_types` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `name` varchar(40) NOT NULL,
 `description` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;
CREATE UNIQUE INDEX `rtype_name` ON `resource_types` (`name`);

-- resource is like a teacher, "Jennifer Sterling"
CREATE TABLE IF NOT EXISTS `resources` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `default_location` varchar(40) NOT NULL,
 `name` varchar(40) NOT NULL,
 `description` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;
CREATE UNIQUE INDEX `res_name` ON `resources` (`name`);

-- resource_group is like "Bacich Teacher"
-- versus "Kent Teacher"
CREATE TABLE IF NOT EXISTS `resource_groups` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `name` varchar(40) NOT NULL,
 `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;
CREATE UNIQUE INDEX `rgrp_name` ON `resource_groups` (`name`);

CREATE TABLE IF NOT EXISTS `resource_group_members` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `resource_group_id` int(10) unsigned NOT NULL,
 `resource_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `resource_managers` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `resource_id` int(10) unsigned NOT NULL,
 `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

-- resource_type_id can be found this way:
-- appointment_day.resource_calendar.resource_type_id
CREATE TABLE IF NOT EXISTS `scheduled_slots` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `appointment_day_id` int(10) unsigned NOT NULL,
 `time_block_id` int(10) unsigned NOT NULL,
 `resource_id` int(10) unsigned NOT NULL,
 `location` varchar(40) NOT NULL,
 `available` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `reservations` (
 `id` int(10) unsigned NOT NULL auto_increment,
 `scheduled_slot_id` int(10) unsigned NOT NULL,
 `user_id` int(10) unsigned NOT NULL,
 `last_notified_at` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;
