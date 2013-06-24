<!DOCTYPE html>
<html>
	<head>
		<?php
			$tpl->defineZone
			(
				array
				(
					'name'			=>'Head',
					'type'			=>'template',
					'template'		=>'global.head'
				)
			);
		?>
	</head>
	<body id="template-default" class="is-full-width home">
		<!-- #wrapper -->
		<div id="wrapper">
			<!-- #header-->
			<header id="header">
				<?php
					$tpl->defineZone
					(
						array
						(
							'name'			=>'Header',
							'type'			=>'template',
							'template'		=>'global.header'
						)
					);
				?>
			</header>
			<header id="header2a">
				<?php
					$tpl->defineZone
					(
						array
						(
							'name'			=>'Header 2A',
							'type'			=>'template',
							'template'		=>'global.header2a'
						)
					);
				?>
			</header>
			<header id="header2b">
				<?php
					$tpl->defineZone
					(
						array
						(
							'name'			=>'Header 2B',
							'type'			=>'template',
							'template'		=>'global.header2b'
						)
					);
				?>
			</header>
			<!-- #content-->
			<section id="content-home">
				<?php
					$tpl->defineZone
					(
						array
						(
							'name'			=>'Home Ticker Content',
							'type'			=>'node',
							'typeConfig'	=>array
							(
								'type'		=>7,
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
			<?php
					$tpl->defineZone
					(
						array
						(
							'name'			=>'Footer',
							'type'			=>'template',
							'template'		=>'global.footer'
						)
					);
				?>
		</footer>
	</body>
</html>