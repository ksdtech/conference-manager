SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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

CREATE TABLE IF NOT EXISTS `user_roles` (
 `id` int(11) unsigned NOT NULL auto_increment,
 `user_id` int(11) unsigned,
 `role_id` int(11) unsigned,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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

