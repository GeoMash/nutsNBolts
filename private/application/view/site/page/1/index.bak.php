<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php $tpl->loadView('sites/1/includes/head'); ?>
	</head>
	<body id="template-default" class="is-full-width home">
		<!-- #wrapper -->
		<div id="wrapper">
			<!-- #header-->
			<header id="header">
				<?php $tpl->loadView('sites/1/includes/header'); ?>
			</header>
			<header id="header2a">
				<?php $tpl->loadView('sites/1/includes/header2a'); ?>
			</header>
			<header id="header2b">
				<?php $tpl->loadView('sites/1/includes/header2b'); ?>
			</header>
			<!-- #content-->
			<section id="content-home">
				<div class="ticker-left"></div>
				<div class="ticker-right">
					<span class="ticker-text">
						<img src="<?php $tpl->SITEPATH; ?>images/sic.png">
						<p>&nbsp;</p>
						Alliance Bank would love to give the local SMEs a kick start and Alliance BizSmart Accademy was born.
						<p>&nbsp;</p>
						<a href="/about/">
							<img border="0" src="<?php $tpl->SITEPATH; ?>images/discover.png">
						</a>
					</span>
				</div>
				<div class="ticker-bottom">
					<a href="javascript:{};">
						<img src="<?php $tpl->SITEPATH; ?>images/icons/bd.png">
					</a>
					<a href="javascript:{};">
						<img src="<?php $tpl->SITEPATH; ?>images/icons/gd.png">
					</a>
					<a href="javascript:{};">
						<img src="<?php $tpl->SITEPATH; ?>images/icons/gd.png">
					</a>
					</div>
			</section>
		</div>

		<!-- #footer -->
		<div class="mark"></div>
		<footer id="footer">
			<?php $tpl->loadView('sites/1/includes/footer'); ?>
		</footer>
	</body>
</html>