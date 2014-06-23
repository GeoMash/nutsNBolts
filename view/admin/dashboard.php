<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="action-nav-normal">
			<div class="row-fluid">
				<?php if ($tpl->can('admin.collection.read')): ?>
				<div class="span2 action-nav-button">
					<a data-toggle="bigmodal" role="button" href="#fileManagerWindow" title="File Manager">
						<i class="icon-folder-open-alt"></i>
						<span>Files</span>
					</a>
				</div>
				<?php
				endif;
				if ($tpl->can('admin.user.read')):
				?>
				<div class="span2 action-nav-button">
					<a href="/admin/settings/users/" title="Users">
						<i class="icon-user"></i>
						<span>Users</span>
					</a>
				</div>
				<?php
				endif;
				$navButtons=$tpl->get('navButtons');
				foreach ($navButtons as $navButton)
				{
					print $navButton;
				}
				?>
			</div>
		</div>
	</div>
</div>