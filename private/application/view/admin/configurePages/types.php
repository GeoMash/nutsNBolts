<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="box">
			<div class="box-header">
				<span class="title">Page Types</span>
				<ul class="box-toolbar">
					<li>
						<a href="/admin/configurepages/types/add">
							<span class="triangle-button green"><i class="icon-plus"></i></span>
						</a>
					</li>
				</ul>
			</div>
			<div class="box-content scrollable" style="height: 410px; overflow-y: auto">
				<?php $tpl->getTypesList(); ?>
			</div>
		</div>
	</div>
</div>