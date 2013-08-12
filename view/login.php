<!doctype html>
<html>
	<head>

		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
		<link href="/css/application.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/js/vendor/jquery/fancybox/source/jquery.fancybox.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/js/vendor/jquery/fancybox/source/helpers/jquery.fancybox-buttons.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/css/main.css" media="screen" rel="stylesheet" type="text/css" />

		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

		<!-- Use title if it's in the page YAML frontmatter -->
		<title><?php $tpl->websiteTitle; ?></title>
		
	</head>
	<body>
		<div class="navbar navbar-top navbar-inverse">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="brand" href="#"><?php $tpl->brandTitle; ?></a>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="span4 offset4">
				<div class="padded">
					<div class="login box" style="margin-top: 80px;">

						<div class="box-header">
							<span class="title">Login</span>
						</div>

						<div class="box-content padded">
							<form class="separate-sections" method="post">
								<div class="input-prepend">
								<span class="add-on" href="#">
									<i class="icon-user"></i>
								</span>
									<input type="text" name="username" placeholder="username">
								</div>

								<div class="input-prepend">
								<span class="add-on" href="#">
									<i class="icon-key"></i>
								</span>
									<input type="password" name="password" placeholder="password">
								</div>

								<div>
									<button class="btn btn-blue btn-block">
											Login <i class="icon-signin"></i>
									</button>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>