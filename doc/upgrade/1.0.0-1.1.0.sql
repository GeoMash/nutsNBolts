ALTER TABLE `content_type` ADD COLUMN `workflow_id` INT(10) NOT NULL AFTER `site_id`;
ALTER TABLE `node` ADD COLUMN `workflow_step_id` INT(10) DEFAULT 0 NOT NULL AFTER `content_type_id`;
ALTER TABLE `collection` CHANGE `descriptions` `description` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL;
CREATE TABLE `workflow` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `workflow_action` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `workflow_step` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `workflow_step_action` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transition_id` int(10) NOT NULL,
  `from_step_id` int(10) NOT NULL,
  `to_step_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `workflow_step_transition` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `step_transition_id` int(10) NOT NULL,
  `action_id` int(10) NOT NULL,
  `params` text NOT NULL,
  `order` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `workflow_transition` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
CREATE TABLE `workflow_transition_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transition_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `collection_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `collection_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* MD changes */
ALTER TABLE `user` ADD COLUMN `position` VARCHAR(200) NOT NULL AFTER `income_range`;
ALTER TABLE `user` ADD COLUMN `company` VARCHAR(200) NOT NULL AFTER `position`;
ALTER TABLE `user` ADD COLUMN `about` VARCHAR(1024) NOT NULL AFTER `company`;
ALTER TABLE `user` ADD COLUMN `phone` VARCHAR(50) NOT NULL AFTER `about`;
ALTER TABLE `user` ADD COLUMN `dob` DATE() NOT NULL AFTER `phone`;
ALTER TABLE `user` ADD COLUMN `income_range` VARCHAR(20) NOT NULL AFTER `dob`;
ALTER TABLE `user` ADD COLUMN `gender` TINYINT(1) NOT NULL AFTER `income_range`;
ALTER TABLE `user` ADD COLUMN `image` VARCHAR(200) NOT NULL AFTER `gender`;

/* page bunching */

ALTER TABLE `content_type` ADD COLUMN `page_name` VARCHAR(20) NOT NULL;

ALTER TABLE `user`
ADD COLUMN `image` VARCHAR(100) NOT NULL AFTER `name_last`,
ADD COLUMN `position` VARCHAR(100) NOT NULL AFTER `image`,
ADD COLUMN `company` VARCHAR(100) NOT NULL AFTER `position`,
ADD COLUMN `about` TEXT NOT NULL AFTER `company`;


ALTER TABLE `content_type` ADD COLUMN `page_name` VARCHAR(20) NOT NULL;

