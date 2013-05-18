<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php #$tpl->loadView('/site/blocks/head'); ?>
	</head>
	<body id="template-default" class="is-full-width home">
		<!-- #wrapper -->
		<div id="wrapper">
			<!-- #header-->
			<header id="header">
				<?php #$tpl->loadView('/site/blocks/header'); ?>
				<?php
					$tpl->defineZone
					(
						array
						(
							'name'			=>'Header',
							'type'			=>'template',
							'template'		=>'global.header',
							'multiple'		=>true
						)
					);
				?>
			</header>
			<header id="header2a">
				<?php #$tpl->loadView('/site/blocks/header2a'); ?>
			</header>
			<header id="header2b">
				<?php #$tpl->loadView('/site/blocks/header2b'); ?>
			</header>
			<!-- #content-->
			<section id="content-home">
				<?php
					$tpl->defineZone
					(
						array
						(
							'name'			=>'Home Ticker Content',
							'type'			=>'content',
							'typeConfig'	=>array
							(
								'type'		=>'Home Ticker',
								'multiple'	=>true
							),
							'template'		=>'local.contentItem'
						)
					);
				?>
				
				
				
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
			<?php #$tpl->loadView('/site/blocks/footer'); ?>
		</footer>
	</body>
</html>