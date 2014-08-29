/*Table structure for table `collection` */

DROP TABLE IF EXISTS `collection`;

CREATE TABLE `collection` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `collection_user` */

DROP TABLE IF EXISTS `collection_user`;

CREATE TABLE `collection_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `collection_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `content_part` */

DROP TABLE IF EXISTS `content_part`;

CREATE TABLE `content_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `widget` varchar(100) DEFAULT NULL,
  `label` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `content_type_id` int(10) unsigned NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `content_type` */

DROP TABLE IF EXISTS `content_type`;

CREATE TABLE `content_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `workflow_id` int(10) DEFAULT NULL,
  `ref` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `page_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `content_type_role` */

DROP TABLE IF EXISTS `content_type_role`;

CREATE TABLE `content_type_role` (
  `content_type_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  UNIQUE KEY `UniquePair` (`content_type_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `content_type_user` */

DROP TABLE IF EXISTS `content_type_user`;

CREATE TABLE `content_type_user` (
  `content_type_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  UNIQUE KEY `UniquePair` (`content_type_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `content_widget` */

DROP TABLE IF EXISTS `content_widget`;

CREATE TABLE `content_widget` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `template` text,
  `multivalue` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `facebook` */

DROP TABLE IF EXISTS `facebook`;

CREATE TABLE `facebook` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_uid` int(30) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `firstName` varchar(200) DEFAULT NULL,
  `lastName` varchar(200) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `form` */

DROP TABLE IF EXISTS `form`;

CREATE TABLE `form` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `ref` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `message_success` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `form_submission` */

DROP TABLE IF EXISTS `form_submission`;

CREATE TABLE `form_submission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `form_id` int(10) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `exported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*Table structure for table `message` */

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(10) NOT NULL,
  `to_user_id` int(10) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `nav` */

DROP TABLE IF EXISTS `nav`;

CREATE TABLE `nav` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `nav_part` */

DROP TABLE IF EXISTS `nav_part`;

CREATE TABLE `nav_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nav_id` int(10) DEFAULT NULL,
  `page_id` int(10) DEFAULT NULL,
  `node_id` int(10) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `label` varchar(100) NOT NULL,
  `order` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `node` */

DROP TABLE IF EXISTS `node`;

CREATE TABLE `node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `content_type_id` int(10) NOT NULL,
  `workflow_step_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_published` timestamp NULL DEFAULT NULL,
  `owner_user_id` int(10) NOT NULL,
  `original_user_id` int(10) DEFAULT NULL,
  `last_user_id` int(10) NOT NULL,
  `order` int(5) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `node_comment` */

DROP TABLE IF EXISTS `node_comment`;

CREATE TABLE `node_comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_bin NOT NULL,
  `body` text COLLATE utf8_bin NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Table structure for table `node_map` */

DROP TABLE IF EXISTS `node_map`;

CREATE TABLE `node_map` (
  `node_id` int(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  UNIQUE KEY `UniquePair` (`node_id`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `node_part` */

DROP TABLE IF EXISTS `node_part`;

CREATE TABLE `node_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `content_part_id` int(10) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `node_id` (`node_id`,`content_part_id`),
  UNIQUE KEY `node_id_2` (`node_id`,`content_part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `node_rating` */

DROP TABLE IF EXISTS `node_rating`;

CREATE TABLE `node_rating` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rating` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `node_read` */

DROP TABLE IF EXISTS `node_read`;

CREATE TABLE `node_read` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `content_type_id` int(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `node_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `node_tag` */

DROP TABLE IF EXISTS `node_tag`;

CREATE TABLE `node_tag` (
  `node_id` int(10) NOT NULL,
  `tag` varchar(30) NOT NULL,
  PRIMARY KEY (`node_id`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `page` */

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `page_type_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `page_map` */

DROP TABLE IF EXISTS `page_map`;

CREATE TABLE `page_map` (
  `page_id` int(10) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `UniquePair` (`page_id`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `page_type` */

DROP TABLE IF EXISTS `page_type`;

CREATE TABLE `page_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) DEFAULT NULL,
  `ref` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `permission` */

DROP TABLE IF EXISTS `permission`;

CREATE TABLE `permission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `permission_node` */

DROP TABLE IF EXISTS `permission_node`;

CREATE TABLE `permission_node` (
  `node_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `update` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `Unique` (`node_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `permission_role` */

DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE `permission_role` (
  `permission_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `permission_user` */

DROP TABLE IF EXISTS `permission_user`;

CREATE TABLE `permission_user` (
  `permission_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `policy` */

DROP TABLE IF EXISTS `policy`;

CREATE TABLE `policy` (
  `password_force_random` tinyint(1) DEFAULT NULL,
  `password_length_minimum` tinyint(2) DEFAULT NULL,
  `password_length_maximum` tinyint(2) DEFAULT NULL,
  `password_special_characters` tinyint(1) DEFAULT NULL,
  `password_numeric_digits` tinyint(1) DEFAULT NULL,
  `password_upper_lower_characters` tinyint(1) DEFAULT NULL,
  `password_expiry` tinyint(1) DEFAULT NULL,
  `password_past_passwords` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ref` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL,
  `session_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `session_data` blob NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `site` */

DROP TABLE IF EXISTS `site`;

CREATE TABLE `site` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ref` varchar(50) NOT NULL,
  `domain` varchar(100) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `site_settings` */

DROP TABLE IF EXISTS `site_settings`;

CREATE TABLE `site_settings` (
  `label` varchar(200) NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sms` */

DROP TABLE IF EXISTS `sms`;

CREATE TABLE `sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_sent` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `subscribers` */

DROP TABLE IF EXISTS `subscribers`;

CREATE TABLE `subscribers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fb_uid` int(30) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `firstName` varchar(200) DEFAULT NULL,
  `lastName` varchar(200) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `access_token` text,
  `date_last_access_token` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `subscription` */

DROP TABLE IF EXISTS `subscription`;

CREATE TABLE `subscription` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `duration` tinyint(3) NOT NULL,
  `recurring` tinyint(1) NOT NULL,
  `amount` int(10) NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `subscription_invoice` */

DROP TABLE IF EXISTS `subscription_invoice`;

CREATE TABLE `subscription_invoice` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `subscription_user_id` int(10) NOT NULL,
  `subscription_transaction_id` int(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `meta` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `subscription_transaction` */

DROP TABLE IF EXISTS `subscription_transaction`;

CREATE TABLE `subscription_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gateway_transaction_id` bigint(20) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `subscription_user` */

DROP TABLE IF EXISTS `subscription_user`;

CREATE TABLE `subscription_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `subscription_id` int(10) NOT NULL,
  `arb_id` bigint(20) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_timestamp` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `workflow_step_id` int(10) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `name_first` varchar(100) NOT NULL,
  `name_last` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `about` text,
  `date_created` datetime NOT NULL,
  `date_lastlogin` datetime NOT NULL,
  `date_lastactive` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `phone` varchar(50) DEFAULT NULL,
  `dob` varchar(200) DEFAULT NULL,
  `income_range` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `force_password_change` tinyint(1) DEFAULT '0',
  `confirmation_code` varchar(200) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `zip` int(6) DEFAULT NULL,
  `video` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  UNIQUE KEY `UniquePair` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow` */

DROP TABLE IF EXISTS `workflow`;

CREATE TABLE `workflow` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow_action` */

DROP TABLE IF EXISTS `workflow_action`;

CREATE TABLE `workflow_action` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow_step` */

DROP TABLE IF EXISTS `workflow_step`;

CREATE TABLE `workflow_step` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow_step_action` */

DROP TABLE IF EXISTS `workflow_step_action`;

CREATE TABLE `workflow_step_action` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transition_id` int(10) NOT NULL,
  `from_step_id` int(10) NOT NULL,
  `to_step_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow_step_transition` */

DROP TABLE IF EXISTS `workflow_step_transition`;

CREATE TABLE `workflow_step_transition` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `transition_id` INT(10) NOT NULL,
  `from_step_id` INT(10) NOT NULL,
  `to_step_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow_transition` */

CREATE TABLE `workflow_transition` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow_transition_role` */

DROP TABLE IF EXISTS `workflow_transition_role`;

CREATE TABLE `workflow_transition_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transition_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `workflow_step_transition_action` */

CREATE TABLE `workflow_step_transition_action` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `step_transition_id` INT(10) NOT NULL,
  `action_id` INT(10) NOT NULL,
  `params` TEXT NOT NULL,
  `order` INT(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;