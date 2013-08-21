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

Load "db.sql" from the nutsNBolts doc folder.

