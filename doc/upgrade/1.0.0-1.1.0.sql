ALTER TABLE `alliance_nutsnbolts`.`content_type` ADD COLUMN `workflow_id` INT(10) NOT NULL AFTER `site_id`;
ALTER TABLE `alliance_nutsnbolts`.`node` ADD COLUMN `workflow_id` INT(10) DEFAULT 0 NOT NULL AFTER `content_type_id`;
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
  `step_id` int(10) NOT NULL,
  `action_id` int(10) NOT NULL,
  `direction` tinyint(1) NOT NULL,
  `order` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `workflow_step_transition` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `from_step_id` int(10) NOT NULL,
  `to_step_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `workflow_transition_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transition_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
