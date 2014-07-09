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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



insert into `user`(`id`,`workflow_step_id`,`email`,`password`,`salt`,`name_first`,`name_last`,`image`,`position`,`company`,`about`,`date_created`,`date_lastlogin`,`date_lastactive`,`status`,`phone`,`dob`,`income_range`,`gender`,`force_password_change`,`confirmation_code`,`address1`,`address2`,`country`,`zip`)
values
(-100,0,'root','01a044e786d5ab7b90fd369453b2d6e6','4d15c5636af27bef608d48e53e82a7b7b4ce7521','Root','Root',NULL,NULL,NULL,'','2013-08-12 14:07:38','2014-06-09 15:21:43','0000-00-00 00:00:00',1,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),
(-200,0,'guest','ccef3aae8ade46571f237d95e2f92c3e','baf82f9665f5bd8983881b88d3adcc7a2c561d4c','Guest','Guest',NULL,'','',NULL,'2014-07-05 06:49:58','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'','','','0',0,NULL,NULL,NULL,NULL,NULL);

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  UNIQUE KEY `UniquePair` (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `user_role` (`user_id`,`role_id`)
values
(-100,-100),
(-200,-200);