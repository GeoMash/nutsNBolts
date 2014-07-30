<?php
if ($tpl->can('admin.page.read')
|| $tpl->can('admin.pageType.read')
|| $tpl->can('admin.template.read')):
?>
<li class="dropdown">
	<a class="dropdown-toggle" href="#" data-toggle="dropdown">
		<i class="icon-file icon-2x"></i>
		<span>
			Pages
			<b class="caret"></b>
		</span>
	</a>
	<ul class="dropdown-menu">
		<?php if ($tpl->can('admin.page.read')): ?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='types')print 'active'; ?>">
			<a href="/admin/configurepages/types">
				<i class="icon-th"></i> Types
			</a>
		</li>
		<?php
			endif;
			if ($tpl->can('admin.pageType.read')):
		?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='pages')print 'active'; ?>">
			<a href="/admin/configurepages/pages">
				<i class="icon-copy"></i> Pages
			</a>
		</li>
		<?php
			endif;
			if ($tpl->can('admin.template.read')):
		?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='templates')print 'active'; ?>" style="display:none;">
			<a href="/admin/configurepages/templates">
				<i class="icon-file-alt"></i> Templates
			</a>
		</li>
		<?php endif; ?>
	</ul>
</li>
<?php endif; ?>