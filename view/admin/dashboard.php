<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="action-nav-normal">
			<div class="row-fluid">
				<?php
				if ($tpl->challangeRole(array('SUPER','ADMIN','CONTENT_CREATOR','CONTENT_EDITOR'))):
				?>
				<div class="span2 action-nav-button">
					<a data-toggle="bigmodal" role="button" href="#fileManagerWindow" title="File Manager">
						<i class="icon-folder-open-alt"></i>
						<span>Files</span>
					</a>
				</div>
				<?php
				endif;
				if ($tpl->challangeRole(array('SUPER','ADMIN'))):
				?>
				<div class="span2 action-nav-button">
					<a href="/admin/settings/users/" title="Users">
						<i class="icon-user"></i>
						<span>Users</span>
					</a>
				</div>
				<?php
				endif;
				?>
			</div>
		</div>
	</div>
</div>