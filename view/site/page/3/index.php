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
			<?php
			$node1=$tpl->getNode(array('title'=>'About Us - About Us'));
			$node2=$tpl->getNode(array('title'=>'About Us - Foo'));
			$node3=$tpl->getNode(array('title'=>'About Us - Bar'));
			?>
			<section id="content-about">
				<div class="about-1-wrapper">
					<div class="about-1a">
						<h1 class="massive-text white"><?php print $node1['header']; ?></h1>
						<p class="white"> 
							<?php print $node1['intro']; ?>
						</p>
						<span class="more">
							<a class="vlarge-text right white" href="javascript:" id="oneMore">More ></a>
						</span>
					</div>
					<div class="about-1b">
						<h1 class="massive-text white"><?php print $node1['header']; ?></h1>
						<p class="white"> 
							<?php print $node1['body']; ?>
						</p>
					</div>
				</div>
				<div class="about-1-content">
					<div class="less">
						<span class="vlarge-text right"><a class="white" href="javascript:" data-action="reset">Less &lt;</a>
					</div>
				</div>
				<div class="about-2-wrapper">
					<div class="about-2a">
						<h1 class="massive-text white"><?php print $node2['header']; ?></h1>
						<p class="white"> 
							<?php print $node2['intro']; ?>
						</p>
						<span class="more">
							<a class="vlarge-text right white" href="javascript:" id="twoMore">More ></a>
						</span>
					</div>
					<div class="about-2b">
						<h1 class="massive-text white"><?php print $node2['header']; ?></h1>
						<p class="white"> 
							<?php print $node2['body']; ?>
						</p>
					</div>
				</div>
				<div class="about-2-content">
					<div class="less">
						<span class="vlarge-text right"><a class="white" href="javascript:" data-action="reset">Less &lt;</a>
					</div>
				</div>
				<div class="about-3-wrapper">
					<div class="about-3a">
						<h1 class="massive-text white"><?php print $node3['header']; ?></h1>
						<p class="white"> 
							<?php print $node3['intro']; ?>
						</p>
						<span class="more">
							<a class="vlarge-text right white" href="javascript:" id="threeMore">More ></a>
						</span>
					</div>
					<div class="about-3b">
						<h1 class="massive-text white"><?php print $node3['header']; ?></h1>
						<p class="white"> 
							<?php print $node3['body']; ?>
						</p>
					</div>
				</div>
				<div class="about-3-content">
					<div class="less">
						<span class="vlarge-text right"><a class="white" href="javascript:" data-action="reset">Less &lt;</a>
					</div>
				</div>
			</section>
		</div>
		<?php
			$tpl->defineZone
			(
				array
				(
					'name'			=>'Scripts',
					'type'			=>'template',
					'template'		=>'global.scripts'
				)
			);
		?>
	</body>
</html>