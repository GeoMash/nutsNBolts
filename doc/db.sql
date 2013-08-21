/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `collection` */

DROP TABLE IF EXISTS `collection`;

CREATE TABLE `collection` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `descriptions` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `collection` */

insert  into `collection`(`id`,`name`,`descriptions`,`status`) values (1,'Images','',0);

/*Table structure for table `content_part` */

DROP TABLE IF EXISTS `content_part`;

CREATE TABLE `content_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `widget` varchar(100) NOT NULL,
  `label` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `ref` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `content_type_id` int(10) unsigned NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `content_part` */

/*Table structure for table `content_type` */

DROP TABLE IF EXISTS `content_type`;

CREATE TABLE `content_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `content_type` */

/*Table structure for table `content_type_role` */

DROP TABLE IF EXISTS `content_type_role`;

CREATE TABLE `content_type_role` (
  `content_type_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  UNIQUE KEY `UniquePair` (`content_type_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `content_type_role` */

/*Table structure for table `content_type_user` */

DROP TABLE IF EXISTS `content_type_user`;

CREATE TABLE `content_type_user` (
  `content_type_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  UNIQUE KEY `UniquePair` (`content_type_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `content_type_user` */

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `content_widget` */

insert  into `content_widget`(`id`,`site_id`,`name`,`description`,`template`,`multivalue`) values (1,1,'Text Box','A standard single-lined text box.','<input type=\"text\" name=\"{name}\" value=\"{value}\" />',0),(2,1,'Text Area','A standard text area.','<textarea name=\"{name}\">{value}</textarea>',0),(3,1,'Select Box','A standard select box.','<select name=\"{name}\">\r\n{options}\r\n</select>',1);

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

/*Data for the table `form` */

/*Table structure for table `form_submission` */

DROP TABLE IF EXISTS `form_submission`;

CREATE TABLE `form_submission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `form_id` int(10) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `exported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `form_submission` */

/*Table structure for table `nav` */

DROP TABLE IF EXISTS `nav`;

CREATE TABLE `nav` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `descripton` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `nav` */

/*Table structure for table `nav_part` */

DROP TABLE IF EXISTS `nav_part`;

CREATE TABLE `nav_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `page_id` int(10) NOT NULL,
  `nav_id` int(10) NOT NULL,
  `node_id` int(10) NOT NULL,
  `label` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `nav_part` */

/*Table structure for table `node` */

DROP TABLE IF EXISTS `node`;

CREATE TABLE `node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `content_type_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_published` timestamp NULL DEFAULT NULL,
  `original_user_id` int(10) DEFAULT NULL,
  `last_user_id` int(10) NOT NULL,
  `order` int(5) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `node` */

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

/*Data for the table `node_comment` */

/*Table structure for table `node_map` */

DROP TABLE IF EXISTS `node_map`;

CREATE TABLE `node_map` (
  `node_id` int(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  UNIQUE KEY `UniquePair` (`node_id`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `node_map` */

/*Table structure for table `node_part` */

DROP TABLE IF EXISTS `node_part`;

CREATE TABLE `node_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `content_part_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `node_id` (`node_id`,`content_part_id`),
  UNIQUE KEY `node_id_2` (`node_id`,`content_part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `node_part` */

/*Table structure for table `node_tag` */

DROP TABLE IF EXISTS `node_tag`;

CREATE TABLE `node_tag` (
  `node_id` int(10) NOT NULL,
  `tag` varchar(30) NOT NULL,
  PRIMARY KEY (`node_id`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `node_tag` */

/*Table structure for table `page` */

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `page_type_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `page` */

/*Table structure for table `page_map` */

DROP TABLE IF EXISTS `page_map`;

CREATE TABLE `page_map` (
  `page_id` int(10) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `UniquePair` (`page_id`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `page_map` */

/*Table structure for table `page_type` */

DROP TABLE IF EXISTS `page_type`;

CREATE TABLE `page_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `page_type` */

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `role` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL,
  `session_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `session_data` blob NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sessions` */

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

/*Data for the table `site` */

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

/*Data for the table `subscribers` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `name_first` varchar(100) NOT NULL,
  `name_last` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_lastlogin` datetime NOT NULL,
  `date_lastactive` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`email`,`password`,`salt`,`name_first`,`name_last`,`date_created`,`date_lastlogin`,`date_lastactive`,`status`) values (-100,'root','01a044e786d5ab7b90fd369453b2d6e6','4d15c5636af27bef608d48e53e82a7b7b4ce7521','Root','Root','2013-08-12 14:07:38','0000-00-00 00:00:00','0000-00-00 00:00:00',1);

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  UNIQUE KEY `UniquePair` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_role` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
