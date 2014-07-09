<?php
if ($tpl->can('admin.content.contentType.read')
|| $tpl->can('admin.content.navigation.read')
|| $tpl->can('admin.content.form.read')
//|| $tpl->can('admin.content.widget.read')
|| $tpl->can('admin.content.node.read')):
?>
<li class="dropdown <?php if ($tpl->get('nav_active_main')=='content')print 'active'; ?>">
	<a class="dropdown-toggle" href="#" data-toggle="dropdown">
		<i class="icon-edit icon-2x"></i>
		<span>
			Content
			<b class="caret"></b>
		</span>
	</a>
	<ul class="dropdown-menu">
		<?php if ($tpl->can('admin.content.contentType.read')): ?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='types')print 'active'; ?>">
			<a href="/admin/configurecontent/types">
				<i class="icon-th"></i> Content Types
			</a>
		</li>
		<?php
			endif;
			if ($tpl->can('admin.content.navigation.read')):
		?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='nav')print 'active'; ?>">
			<a href="/admin/configurecontent/nav">
				<i class="icon-list-ul"></i> Navigation
			</a>
		</li>
		<?php
			endif;
			if ($tpl->can('admin.content.form.read')):
		?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='forms')print 'active'; ?>">
			<a href="/admin/configurecontent/forms">
				<i class="icon-list-alt"></i> Forms
			</a>
		</li>
		<?php
			endif;
			if ($tpl->can('admin.content.widget.read')):
		?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='widgets')print 'active'; ?>" style="display:none;">
			<a href="/admin/configurecontent/widgets">
				<i class="icon-beaker"></i> Widgets
			</a>
		</li>
		<?php
			endif;
			if ($tpl->can('admin.content.node.read')):
		?>
		<li class="divider"></li>
		<?php
			$tpl->navContentTypes();
			endif;
		?>
	</ul>
</li>
<?php endif; ?>