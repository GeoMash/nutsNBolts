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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`email`,`password`,`salt`,`name_first`,`name_last`,`date_created`,`date_lastlogin`,`date_lastactive`,`status`) values (-100,'root','01a044e786d5ab7b90fd369453b2d6e6','4d15c5636af27bef608d48e53e82a7b7b4ce7521','Root','Root','2013-08-12 14:07:38','0000-00-00 00:00:00','0000-00-00 00:00:00',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
