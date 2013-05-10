<!doctype html>
<html>
	<head>

		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">


		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

		<!-- Use title if it's in the page YAML frontmatter -->
		<title><?php $tpl->websiteTitle; ?></title>

		<?php $tpl->loadView('scripts'); ?>

		<script src="/js/application.js" type="text/javascript"></script>
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
								Dashboard
							</h3>
							<h5>
								A subtitle can be added here
							</h5>
						</div>

						<ul class="inline pull-right sparkline-box">

							<li class="sparkline-row">
								<h4 class="blue"><span>Orders</span> 847</h4>
								<div class="sparkline big" data-color="blue"><!--10,14,20,21,18,13,24,14,6,7,28,24--></div>
							</li>

							<li class="sparkline-row">
								<h4 class="green"><span>Reviews</span> 223</h4>
								<div class="sparkline big" data-color="green"><!--8,11,26,14,27,28,8,13,10,22,6,15--></div>
							</li>

							<li class="sparkline-row">
								<h4 class="red"><span>New visits</span> 7930</h4>
								<div class="sparkline big"><!--7,3,24,18,24,3,4,9,16,24,16,29--></div>
							</li>

						</ul>
					</div>
				</div>
			</div>

			<div class="container-fluid padded">
				<div class="row-fluid">
					<?php $tpl->breadcrumbs(); ?>
				</div>
			</div>

			<?php $tpl->loadView($tpl->get('contentView')); ?>
	</body>
</html>