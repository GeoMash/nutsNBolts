ALTER TABLE `alliance_nutsnbolts`.`content_type` ADD COLUMN `workflow_id` INT(10) NOT NULL AFTER `site_id`;
ALTER TABLE `alliance_nutsnbolts`.`node` ADD COLUMN `workflow_step_id` INT(10) DEFAULT 0 NOT NULL AFTER `content_type_id`;
ALTER TABLE `alliance_nutsnbolts`.`collection` CHANGE `descriptions` `description` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL;
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
CREATE TABLE `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(10) NOT NULL,
  `to_user_id` int(10) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8