/*
SQLyog Enterprise v10.3 
MySQL - 5.5.24-log : Database - nutsnbolts
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`nutsnbolts` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `nutsnbolts`;

/*Table structure for table `content_part` */

DROP TABLE IF EXISTS `content_part`;

CREATE TABLE `content_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content_type_id` int(10) NOT NULL,
  `content_widget_id` int(10) NOT NULL,
  `label` varchar(100) COLLATE utf8_estonian_ci NOT NULL,
  `ref` varchar(100) COLLATE utf8_estonian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

/*Data for the table `content_part` */

insert  into `content_part`(`id`,`content_type_id`,`content_widget_id`,`label`,`ref`) values (2,7,1,'Main Text','main_text'),(14,7,1,'Image','image'),(15,7,1,'Article Link','article_link'),(17,1,2,'Preview','preview'),(18,1,2,'Body','body'),(19,1,2,'Related Articles','related_articles'),(20,8,1,'Header','header'),(21,8,2,'Intro','intro'),(22,8,2,'Body','body');

/*Table structure for table `content_type` */

DROP TABLE IF EXISTS `content_type`;

CREATE TABLE `content_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `content_type` */

insert  into `content_type`(`id`,`name`,`description`,`icon`,`status`) values (1,'News Article','A basic content type for news articles.','icon-list',1),(2,'Blog Entry','A basic content type for Blogs.','icon-file',1),(3,'Gallery','A gallery for pictures and/or videos.','icon-picture',1),(7,'Home Ticker','The home page ticker.','icon-random',1),(8,'Simple Article','Simple articles have a header, preview and body.','icon-list',1);

/*Table structure for table `content_widget` */

DROP TABLE IF EXISTS `content_widget`;

CREATE TABLE `content_widget` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `template` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `content_widget` */

insert  into `content_widget`(`id`,`name`,`description`,`template`) values (1,'Text Box','A standard single-lined text box.','<input type=\"text\" name=\"{name}\" value=\"{value}\" />'),(2,'Text Area','A standard text area.','<textarea name=\"{name}\">{value}</textarea>');

/*Table structure for table `nav` */

DROP TABLE IF EXISTS `nav`;

CREATE TABLE `nav` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `content_type_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_published` timestamp NULL DEFAULT NULL,
  `original_user_id` int(10) NOT NULL,
  `last_user_id` int(10) NOT NULL,
  `order` int(5) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `node` */

insert  into `node`(`id`,`content_type_id`,`title`,`date_created`,`date_updated`,`date_published`,`original_user_id`,`last_user_id`,`order`,`status`) values (1,1,'Test123 4','2013-05-12 00:00:00','2013-05-12 00:00:00',NULL,1,1,0,0),(2,1,'Test 2','2013-05-12 00:51:01','2013-05-12 00:51:01',NULL,1,1,0,1),(4,1,'foo','2013-05-18 16:46:57','0000-00-00 00:00:00',NULL,0,0,0,1),(5,7,'First (about)','2013-05-18 18:43:57','0000-00-00 00:00:00',NULL,0,0,0,1),(6,7,'Second (Foo)','2013-05-19 00:50:05','0000-00-00 00:00:00',NULL,0,0,0,1),(7,8,'About Us - About Us','2013-05-20 16:38:00','0000-00-00 00:00:00',NULL,0,0,0,1),(8,8,'About Us - Foo','2013-05-20 17:12:25','0000-00-00 00:00:00',NULL,0,0,0,1),(9,8,'About Us - Bar','2013-05-20 17:13:01','0000-00-00 00:00:00',NULL,0,0,0,1);

/*Table structure for table `node_map` */

DROP TABLE IF EXISTS `node_map`;

CREATE TABLE `node_map` (
  `node_id` int(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `node_map` */

insert  into `node_map`(`node_id`,`url`) values (5,'/'),(6,'/'),(7,'/about/'),(8,'/about/'),(9,'/about/');

/*Table structure for table `node_part` */

DROP TABLE IF EXISTS `node_part`;

CREATE TABLE `node_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `content_part_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `node_part` */

insert  into `node_part`(`id`,`node_id`,`content_part_id`,`value`) values (1,1,17,'Foo bar baz 1 x'),(2,1,18,'Baz bar foo 1 x'),(3,1,19,'fooooooooooo'),(4,4,17,'bar'),(5,4,18,'baz'),(6,4,19,'meh'),(7,5,2,'Alliance Bank would love to give the local SMEs a kick start and Alliance BizSmart Accademy was born.'),(8,5,14,''),(9,5,15,'2'),(10,6,2,'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),(11,6,14,''),(12,6,15,'1'),(13,7,20,'About Us'),(14,7,21,'<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>'),(15,7,22,'<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>'),(16,8,20,'Foo'),(17,8,21,'<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>'),(18,8,22,'<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>'),(19,9,20,'Bar'),(20,9,21,'<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>'),(21,9,22,'<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>\r\n<p class=\"white\">Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank. Here be many pirates that will make ye walk the plank.</p>');

/*Table structure for table `page` */

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_type_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `page` */

insert  into `page`(`id`,`page_type_id`,`title`,`description`,`url`,`status`) values (1,1,'Home Page','Home page with ticker content.','/',1),(2,3,'About Us','About us?','/about/',1);

/*Table structure for table `page_type` */

DROP TABLE IF EXISTS `page_type`;

CREATE TABLE `page_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `page_type` */

insert  into `page_type`(`id`,`name`,`description`,`status`) values (1,'Home Page','Home page type.',1),(2,'Library Feature Main','Page for feature news.',1),(3,'About Page','Small articles in the about section of the website.',1),(4,'Library Feature Archive','Archive Listing for old articles.',1);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `name_first` varchar(100) NOT NULL,
  `name_last` varchar(100) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_lastlogin` datetime NOT NULL,
  `date_lastactive` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
