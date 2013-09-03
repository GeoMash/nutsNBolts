<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="box">
			<div class="box-header">
				<span class="title">Forms</span>
				<ul class="box-toolbar">
					<li>
						<a href="/admin/configurecontent/forms/add">
							<span class="triangle-button green"><i class="icon-plus"></i></span>
						</a>
					</li>
				</ul>
			</div>
			<div class="box-content scrollable" style="height: 410px; overflow-y: auto">
				<?php $tpl->getFormsList(); ?>
			</div>
			<div class="box-content padded">
				<a href="/admin/configurecontent/forms/uploads/">View form uploads</a>
			</div>
		</div>
	</div>
</div>