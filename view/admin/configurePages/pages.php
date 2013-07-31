<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="box">
			<div class="box-header">
				<span class="title">Pages</span>
				<ul class="box-toolbar">
					<li>
						<a href="/admin/configurepages/pages/add">
							<span class="triangle-button green"><i class="icon-plus"></i></span>
						</a>
					</li>
				</ul>
			</div>
			<div class="box-content scrollable" style="height: 410px; overflow-y: auto">
				<?php $tpl->getPagesList(); ?>
			</div>
		</div>
	</div>
</div>