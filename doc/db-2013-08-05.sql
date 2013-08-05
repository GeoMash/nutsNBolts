/*
SQLyog Enterprise v10.3 
MySQL - 5.5.31-MariaDB : Database - nutsnbolts
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`nutsnbolts` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `nutsnbolts`;

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `content_part` */

insert  into `content_part`(`id`,`widget`,`label`,`ref`,`content_type_id`,`config`) values (1,'application\\nutsnbolts\\widget\\textbox','Image','image',7,''),(17,'application\\nutsnbolts\\widget\\textarea','Preview','preview',1,''),(18,'application\\nutsnbolts\\widget\\textarea','Body','body',1,''),(19,'application\\nutsnbolts\\widget\\textarea','Related Articles','related_articles',1,''),(20,'application\\nutsnbolts\\widget\\textbox','Header','header',8,''),(21,'application\\nutsnbolts\\widget\\textarea','Intro','intro',8,''),(22,'application\\nutsnbolts\\widget\\textarea','Body','body',8,''),(23,'application\\nutsnbolts\\widget\\textbox','Image','image',9,''),(24,'application\\nutsnbolts\\widget\\textbox','Text','text',9,''),(27,'application\\nutsnbolts\\widget\\select','Type','type',9,'{\"options\":[{\"label\":\"foo\",\"value\":\"bar\"}]}'),(28,'application\\nutsnbolts\\widget\\select','Type2','type2',9,'{\"multiselect\":\"yes\",\"options\":[{\"label\":\"a\",\"value\":\"aa\"},{\"label\":\"c\",\"value\":\"cc\"}]}');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `content_type` */

insert  into `content_type`(`id`,`site_id`,`ref`,`name`,`description`,`icon`,`status`) values (1,1,'','News Article','A basic content type for news articles.','icon-list',1),(2,1,'','Blog Entry','A basic content type for Blogs.','icon-file',1),(3,1,'','Gallery','A gallery for pictures and/or videos.','icon-picture',1),(7,1,'HOME_SLIDER','Home Slider','The home page slider.','icon-random',1),(8,1,'ARTICLE_SIMPLE','Simple Article','Simple articles have a header, preview and body.','icon-list',1),(9,1,'IMAGE_BLOCK_FLIPPER','Image Block Flipper','Image which when hovered flips to text','icon-picture',1);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `node` */

insert  into `node`(`id`,`site_id`,`content_type_id`,`title`,`date_created`,`date_updated`,`date_published`,`original_user_id`,`last_user_id`,`order`,`status`) values (1,1,7,'Image 1','2013-08-02 17:52:41','0000-00-00 00:00:00',NULL,NULL,1,0,1),(2,1,7,'Image 2','2013-08-02 17:53:16','0000-00-00 00:00:00',NULL,NULL,1,0,1),(3,1,7,'Image 3','2013-08-02 17:54:08','0000-00-00 00:00:00',NULL,NULL,1,0,1),(4,1,7,'Image 4','2013-08-02 17:54:30','0000-00-00 00:00:00',NULL,NULL,1,0,1),(5,1,8,'Home Page Content','2013-08-02 20:02:04','0000-00-00 00:00:00',NULL,NULL,1,0,1),(6,1,9,'Home Page Block 1','2013-08-02 21:57:46','0000-00-00 00:00:00',NULL,NULL,1,0,1),(7,1,9,'Home Page Block 2','2013-08-02 21:59:43','0000-00-00 00:00:00',NULL,NULL,1,0,1);

/*Table structure for table `node_map` */

DROP TABLE IF EXISTS `node_map`;

CREATE TABLE `node_map` (
  `node_id` int(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `node_map` */

insert  into `node_map`(`node_id`,`url`) values (1,'/'),(2,'/'),(3,'/'),(4,'/'),(5,'/'),(6,'/'),(7,'/');

/*Table structure for table `node_part` */

DROP TABLE IF EXISTS `node_part`;

CREATE TABLE `node_part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) NOT NULL,
  `content_part_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `node_part` */

insert  into `node_part`(`id`,`node_id`,`content_part_id`,`value`) values (1,1,1,'/sites/ABS/img/box_images/1.jpg'),(2,2,1,'/sites/ABS/img/box_images/2.jpg'),(3,3,1,'/sites/ABS/img/box_images/3.jpg'),(4,4,1,'/sites/ABS/img/box_images/4.jpg'),(5,5,20,''),(6,5,21,''),(7,5,22,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed bibendum dolor turpis. Suspendisse auctor dui ut justo congue, quis posuere mi congue. Sed at ligula tortor. Duis adipiscing ultrices massa sed consectetur. Sed ac sem ut lectus consequat dictum. Suspendisse et felis ac metus egestas rhoncus. Pellentesque in lacus ac magna facilisis placerat non a est.'),(8,6,23,'/sites/ABS/img/box_images/small1.jpg'),(9,6,24,'Life would be infinitely happier if we could only be born at the age of eighty and gradually approach eighteen'),(10,7,23,'/sites/ABS/img/box_images/small2.jpg'),(11,7,24,'Life would be infinitely happier if we could only be born at the age of eighty and gradually approach eighteen');

/*Table structure for table `page` */

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `page_type_id` int(11) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `page` */

insert  into `page`(`id`,`site_id`,`page_type_id`,`ref`,`title`,`description`,`url`,`status`) values (1,1,1,'home','Home Page','Home page with ticker content.','/',1),(2,1,3,'about','About Us','About Us','/about/',1),(3,1,1,'knowledgeBase','Knowledge Base','Knowledge Base','/knowledgeBase/',1);

/*Table structure for table `page_type` */

DROP TABLE IF EXISTS `page_type`;

CREATE TABLE `page_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `page_type` */

insert  into `page_type`(`id`,`site_id`,`name`,`description`,`status`) values (1,1,'Home Page','Home page type.',1);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `site` */

insert  into `site`(`id`,`ref`,`domain`,`name`,`description`,`status`) values (1,'ABS','bizsmart.dev.lan','Biz Smart Academy','Alliance Biz Smart Academy Wesbite.',1);

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
