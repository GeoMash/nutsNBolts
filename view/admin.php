<!doctype html>
<html>
	<head>

		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
		<link href="/css/application.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/css/bootstrap-wysihtml5.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/js/vendor/jquery/fancybox/source/jquery.fancybox.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/js/vendor/jquery/fancybox/source/helpers/jquery.fancybox-buttons.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/css/main.css" media="screen" rel="stylesheet" type="text/css" />
		
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

		<!-- Use title if it's in the page YAML frontmatter -->
		<title><?php $tpl->websiteTitle; ?></title>

		<?php $tpl->loadView('scripts'); ?>

		
	</head>
	<body>
		<div class="navbar navbar-top navbar-inverse">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="brand" href="#"><?php $tpl->brandTitle; ?></a>
					<!-- the new toggle buttons -->
					<ul class="nav pull-right">
						<li class="toggle-primary-sidebar hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-primary"><button type="button" class="btn btn-navbar"><i class="icon-th-list"></i></button></li>
						<li class="hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-top"><button type="button" class="btn btn-navbar"><i class="icon-align-justify"></i></button></li>
					</ul>
					<?php $tpl->loadView('admin/topNav'); ?>
				</div>
			</div>
		</div>
		<div class="sidebar-background">
			<div class="primary-sidebar-background"></div>
		</div>
		<div class="primary-sidebar">
			<?php $tpl->loadView('admin/mainNav'); ?>
		</div>
		<div class="main-content">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="area-top clearfix">
						<div class="pull-left header">
							<h3 class="title">
								<i class="icon-dashboard"></i>
								CHANGEME
							</h3>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid padded">
				<div class="row-fluid">
					<?php $tpl->breadcrumbs(); ?>
				</div>
				<div class="container-fluid padded">
					<?php $tpl->getNotifications(); ?>
				</div>
			</div>
			<?php $tpl->loadView($tpl->get('contentView')); ?>
			<?php $tpl->loadView('admin/fileManager/main'); ?>
	</body>
</html>