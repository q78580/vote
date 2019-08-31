-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DELIMITER ;;

DROP EVENT IF EXISTS `update_events_status`;;
CREATE EVENT `update_events_status` ON SCHEDULE EVERY 5 MINUTE STARTS '2018-08-15 14:37:34' ON COMPLETION PRESERVE ENABLE DO BEGIN

UPDATE events e
SET e.status=0 
WHERE e.voting_deadline < unix_timestamp(now()) and e.status = 1;

END;;

DELIMITER ;

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


SET NAMES utf8mb4;

DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `id` (`id`),
  CONSTRAINT `class_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `scan` varchar(255) DEFAULT NULL,
  `content` text,
  `user_max` int(5) DEFAULT '0',
  `ip_max` int(5) DEFAULT '0',
  `fake_view_num` int(5) DEFAULT '0',
  `fake_voting_num` int(5) DEFAULT '0',
  `real_view_num` int(5) DEFAULT '0',
  `real_voting_num` int(5) DEFAULT '0',
  `need_cheek` tinyint(1) NOT NULL DEFAULT '1',
  `report_time` int(10) DEFAULT NULL COMMENT '报名开始时间',
  `report_deadline` int(10) DEFAULT NULL COMMENT '报名截止时间',
  `voting_time` int(10) DEFAULT NULL COMMENT '投票开始时间',
  `voting_deadline` int(10) DEFAULT NULL COMMENT '投票截止时间',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `opus_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ips` varchar(100) NOT NULL,
  `created_at` int(11) NOT NULL,
  `lasted_at` int(11) DEFAULT NULL,
  `remarka` int(11) DEFAULT NULL,
  `remarkb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `opus`;
CREATE TABLE `opus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `pica` varchar(255) NOT NULL,
  `picb` varchar(255) DEFAULT NULL,
  `picc` varchar(255) DEFAULT NULL,
  `picd` varchar(255) DEFAULT NULL,
  `pice` varchar(255) DEFAULT NULL,
  `content` text,
  `is_check` tinyint(1) DEFAULT '1',
  `voting_num` int(5) DEFAULT '0',
  `fake_voting_num` int(5) DEFAULT '0',
  `view_num` int(5) DEFAULT '0',
  `is_display` tinyint(1) DEFAULT '1',
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `opus_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `opus_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `class` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `jsnick` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `username` varchar(255) DEFAULT NULL COMMENT '姓名',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别',
  `city` varchar(255) DEFAULT NULL COMMENT '城市',
  `address` varchar(255) DEFAULT NULL COMMENT '邮寄地址',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `status` int(2) DEFAULT '0' COMMENT '账号状态',
  `created_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1,	'admin',	'0LXhoo5TRsmNUWEZiofl1146Z9tUnvmK',	'$2y$13$iZZC/AqWWgmvzyCZwqY.luV/z3rOsNdMeiOIKXg.hxj.Mm4wlU.Sm',	'',	'admin@demo.com',	'admin',	1,	1462433023,	1462433023);

-- 2019-08-31 15:15:43