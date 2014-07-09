CREATE TABLE `permission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `permission_role` (
  `permission_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `permission_user` (
  `permission_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `permission_node` (
  `node_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `update` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `Unique` (`node_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert  into `permission`
(`id`,`key`,`name`,`description`,`category`)
values
(1,'login','Login','Can Login.','Core'),
(2,'admin.access','Access Admin Panel','Can access the admin panel after logging in.','Core'),
(3,'admin.dashboard.create','Create Dashboard','Creates a new dashboard for a user','Dashboard'),
(4,'admin.dashboard.read','Read Dashboard','','Dashboard'),
(5,'admin.dashboard.update','Update Dashboard','','Dashboard'),
(6,'admin.dashboard.delete','Delete Dashboard','','Dashboard'),
-- Settings
(7,'admin.setting.nutsNBolts.read','Read Nuts n Bolts Settings','','Settings'),
(8,'admin.setting.nutsNBolts.update','Update Nuts n Bolts Settings','','Settings'),
(9,'admin.setting.site.create','Create Site Settings','','Settings'),
(10,'admin.setting.site.read','Read Site Settings','','Settings'),
(11,'admin.setting.site.update','Update Site Settings','','Settings'),
(12,'admin.setting.site.delete','Delete Site Settings','','Settings'),
-- Users
(13,'admin.user.create','Create Users','','User'),
(14,'admin.user.read','Read Users','','User'),
(15,'admin.user.update','Update Users','','User'),
(16,'admin.user.delete','Delete Users','','User'),
(17,'admin.user.impersonate','Impersonate User','','User'),
(18,'user.messsage.create','Create Messages','','User'),
(19,'user.messsage.read','Read Messages','','User'),
(20,'user.messsage.update','Update Messages','','User'),
(21,'user.messsage.delete','Delete Messages','','User'),
(22,'user.profile.read','Read Profile','','User'),
(23,'user.profile.update','Update Profile','','User'),
-- Security
(24,'admin.permission.create','Create Permissions','','Security'),
(25,'admin.permission.read','Read Permissions','','Security'),
(26,'admin.permission.update','Update Permissions','','Security'),
(27,'admin.permission.delete','Delete Permissions','','Security'),
(28,'admin.permission.user.assign','Assign Permissions to Users','','Security'),
(29,'admin.role.create','Create Roles','','Security'),
(30,'admin.role.read','Read Roles','','Security'),
(31,'admin.role.update','Update Roles','','Security'),
(32,'admin.role.delete','Delete Roles','','Security'),
(33,'admin.policy.create','Create Policies','','Security'),
(34,'admin.policy.read','Read Policies','','Security'),
(35,'admin.policy.update','Update Policies','','Security'),
(36,'admin.policy.delete','Delete Policies','','Security'),
(37,'admin.policy.password.create','Create Password Policies','','Security'),
(38,'admin.policy.password.read','Read Password Policies','','Security'),
(39,'admin.policy.password.update','Update Password Policies','','Security'),
(40,'admin.policy.password.delete','Delete Password Policies','','Security'),
-- Pages
(41,'admin.page.create','Create Pages','','Page'),
(42,'admin.page.read','Read Pages','','Page'),
(43,'admin.page.update','Update Pages','','Page'),
(44,'admin.page.delete','Delete Pages','','Page'),
(45,'admin.pageType.create','Create Page Types','','Page'),
(46,'admin.pageType.read','Read Page Types','','Page'),
(47,'admin.pageType.update','Update Page Types','','Page'),
(48,'admin.pageType.delete','Delete Page Types','','Page'),
-- Content
(49,'admin.content.contentType.create','Create Content Types','','Content'),
(50,'admin.content.contentType.read','Read Content Types','','Content'),
(51,'admin.content.contentType.update','Update Content Types','','Content'),
(52,'admin.content.contentType.delete','Delete Content Types','','Content'),
(53,'admin.content.navigation.create','Create Navigations','','Content'),
(54,'admin.content.navigation.read','Read Navigations','','Content'),
(55,'admin.content.navigation.update','Update Navigations','','Content'),
(56,'admin.content.navigation.delete','Delete Navigations','','Content'),
(57,'admin.content.form.create','Create Forms','','Content'),
(58,'admin.content.form.read','Read Forms','','Content'),
(59,'admin.content.form.update','Update Forms','','Content'),
(60,'admin.content.form.delete','Delete Forms','','Content'),
(61,'admin.content.node.create','Create Nodes','','Content'),
(62,'admin.content.node.read','Read Nodes','','Content'),
(63,'admin.content.node.update','Update Nodes','','Content'),
(64,'admin.content.node.delete','Delete Nodes','','Content'),
(65,'admin.content.node.ownership','Change Node Ownership','','Content'),
(66,'admin.content.node.archive','Archive Node','','Content'),
(67,'admin.content.node.permit','Permit Access to Nodes','','Content'),
-- Subscriptions
(68,'admin.subscription.package.create','Create Subscription Packages','','Subscriptions'),
(69,'admin.subscription.package.read','Read Subscription Packages','','Subscriptions'),
(70,'admin.subscription.package.update','Update Subscription Packages','','Subscriptions'),
(71,'admin.subscription.package.delete','Delete Subscription Packages','','Subscriptions'),
(72,'admin.subscription.subscriber.create','Create Subscription Subscribers','','Subscriptions'),
(73,'admin.subscription.subscriber.read','Read Subscription Subscribers','','Subscriptions'),
(74,'admin.subscription.subscriber.update','Update Subscription Subscribers','','Subscriptions'),
(75,'admin.subscription.subscriber.delete','Delete Subscription Subscribers','','Subscriptions'),
-- Collections
(76,'admin.collection.create','Create Collections','','Collections'),
(77,'admin.collection.read','Read Collections','','Collections'),
(78,'admin.collection.update','Update Collections','','Collections'),
(79,'admin.collection.delete','Delete Collections','','Collections'),
-- Workflows
(80,'admin.workflow.create','Create Workflows','','Workflow'),
(81,'admin.workflow.read','Read Workflows','','Workflow'),
(82,'admin.workflow.update','Update Workflows','','Workflow'),
(83,'admin.workflow.delete','Delete Workflows','','Workflow');

