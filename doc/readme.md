TODO
====
User Feedback
Tags
Templates
Plugins
Comments
User Groups
User Permissions
User Registration
User Registration - Facebook, Twitter (Any Open ID?)
User Login





Main Nav
========
Dashboard
Content
	- News Article
	- Blog Entry
	(dynamic)
Configure Content
	- Types
	- Widgets
Navigation
Templates
System Settings

Content Types
=============

id
name
description
icon
status



Content Part
=============

id
content_type_id
content_widget_id
label



Content Widget
==============
id
name
description
template





Node
=============

id
content_type_id
date_created
date_updated
date_published
original_user_id
last_user_id
status



Content Node Part
=================

id
node_id
widget_id
content





Content Templates
=================




Template Zones
==============








Navigation
==========














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
) ENGINE=InnoDB DEFAULT CHARSET=utf8




