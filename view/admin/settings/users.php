<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<span class="title">Users</span>
					<ul class="box-toolbar">
						<li>
							<a href="/admin/settings/users/add/">
								<span class="triangle-button green"><i class="icon-plus"></i></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="box-content">
					<div id="dataTables">
						<table cellpadding="0" cellspacing="0" border="0" class="dTable responsive">
							<thead>
								<tr>
									<th><div>Email Address</div></th>
									<th><div>Name</div></th>
									<th><div>Last Login</div></th>
									<th><div>Status</div></th>
								</tr>
							</thead>
							<tbody>
								<?php $tpl->getUserList(); ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>