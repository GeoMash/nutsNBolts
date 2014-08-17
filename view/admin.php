<!doctype html>
<html>
	<head>

		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
		<link href="/admin/css/application.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/admin/css/bootstrap-wysihtml5.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/admin/js/vendor/jquery/fancybox/source/jquery.fancybox.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/admin/js/vendor/jquery/fancybox/source/helpers/jquery.fancybox-buttons.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/admin/css/main.css" media="screen" rel="stylesheet" type="text/css" />
		
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

		<!-- Use title if it's in the page YAML frontmatter -->
		<title><?php $tpl->websiteTitle; ?></title>

		<?php $tpl->loadView('scripts'); ?>

		
	</head>
	<body class="topBodyPadding">
		<div class="navbar navbar-top navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="brand" href="/admin"><?php $tpl->brandTitle; ?></a>
					<!-- the new toggle buttons -->
					<ul class="nav pull-right">
						<li class="toggle-primary-sidebar hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-primary"><button type="button" class="btn btn-navbar"><i class="icon-th-list"></i></button></li>
						<li class="hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-top"><button type="button" class="btn btn-navbar"><i class="icon-align-justify"></i></button></li>
					</ul>
					<?php $tpl->loadView('admin/topNav'); ?>
				</div>
			</div>
		</div>
		<div class="main-content">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="area-top clearfix">
						<div class="pull-left header">
							<h3 class="title">
								<?php
								switch ($tpl->get('nav_active_main'))
								{
									case '':
									case 'dashboard':
									{
										print '<i class="icon-dashboard"></i>';
										print 'Dashboard';
										break;
									}
									case 'content':
									{
										print '<i class="icon-edit"></i>';
										print 'Content';
										break;
									}
									case 'configurepages':
									{
										print '<i class="icon-qrcode"></i>';
										print 'Configure Pages';
										break;
									}
									case 'configurecontent':
									{
										print '<i class="icon-cogs"></i>';
										print 'Configure Content';
										break;
									}
									case 'settings':
									{
										print '<i class="icon-wrench"></i>';
										print 'Settings';
										break;
									}
								}
								?>
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
		</div>
		<?php $tpl->loadView('admin/fileManager/main'); ?>
		<footer>
			<div class="container">
				<p class="text-center">Nuts n' Bolts version <?php print $tpl->version; ?></p>
			</div>
		</footer>
	</body>
</html>