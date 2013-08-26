Nuts n Bolts Installation Guide
===============================

Requirements
============

PHP 5.3.4
Nutshell 1.1.0


Basic Setup
================

For the purposes of this setup guide, the website application name will be "MyBlog".

Setup the following file and folder structure:

/private
	/application
		/myBlog
			/config
				/cache
			/logs
			/view
				/block
				/page
		/nutsNBolts
	/nutshell
/public
	/_collections
	/sites
		/BLOG
	index.php
	
In the nutshell folder, clone the Nutshell repository from github.
https://github.com/SpinifexGroup/nutshell

Once cloned, switch it to the "1.1.0.dev" branch.

In the nutsNBolts folder, clone the Nuts n Bolts repositry from stash.
http://tim@stash.gaia.geomash.com/scm/NUT/nutsnbolts.git

Once cloned, switch to the "dev" branch.


Database Setup
==============

Load "db.sql" from the nutsNBolts doc folder into a database of your choosing.
In this example, the database name is "myblog".

Inset a record into the "site" table which reflects the domain you'll be developing or deploying onto.

Example:
INSERT INTO `myblog`.`site` (`ref`, `domain`, `name`, `description`, `status`) VALUES ('BLOG', 'myblog.dev.lan', 'My Blog', 'My very own blog.', '1'); 


Config Setup
============
Configure the connection for the Nuts n Bolts database by going into the Nuts N Bolts config folder and editing the "production.json" or "dev.json" files.

Then edit the plupload settings to point to the correct folders.

Example (dev.json):
{
	"extends":	"production",
	"config":
	{
		"application":
		{
			"mode": "development"
		},
		"plugin":
		{
			"Db":
			{
				"connections":
				{
					"nutsnbolts":
					{
						"handler":		"MySQL",
						"username":		"root",
						"password":		"root",
						"host":			"localhost",
						"port":			"3306",
						"database":		"myblog"
					}
				}
			},
			"Plupload":
			{
				"temporary_dir":	"D:/tmp/",
				"completed_dir":	"D:/www/myblog/public/_collections/"
			}
		}
	}
}

Admin Panel Setup
==============
To install the admin panel, unzip the admin.frontend.zip file into the "public" directory.


Completion
==========
You should now be good to start building your website. You can access the admin panel by browsing to:
http://myblog.dev.lan/admin
Login with the username and password of root/root. Please change this user's password in System Setings -> Users.
