<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="action-nav-normal">
			<div class="row-fluid">
				<?php if ($tpl->can('admin.backupRestore.backup')): ?>
				<div class="span2 action-nav-button">
					<a id="open-wizard-backup" href="#" title="Backup">
						<i class="icon-download-alt"></i>
						<span>Backup</span>
					</a>
				</div>
				<?php
				endif;
				if ($tpl->can('admin.backupRestore.restore')):
				?>
				<div class="span2 action-nav-button">
					<a id="open-wizard-restore" href="#" title="Restore">
						<i class="icon-upload-alt"></i>
						<span>Restore</span>
					</a>
				</div>
				<?php
				endif;
				?>
			</div>
		</div>
	</div>
</div>
<?php
$tpl->loadView('admin/settings/backupRestore/wizard-restore');
?>