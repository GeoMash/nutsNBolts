<?php if ($tpl->challengeRole(array('SUPER','ADMIN','CONTENT_CREATOR','CONTENT_EDITOR','BLOGGER'))): ?>
<li class="dropdown <?php if ($tpl->get('nav_active_main')=='content')print 'active'; ?>">
	<a class="dropdown-toggle" href="#" data-toggle="dropdown">
		<i class="icon-edit icon-2x"></i>
		<span>
			Content
			<b class="caret"></b>
		</span>
	</a>
	<ul class="dropdown-menu">
		<?php if ($tpl->challengeRole(array('SUPER','ADMIN'))): ?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='types')print 'active'; ?>">
			<a href="/admin/configurecontent/types">
				<i class="icon-th"></i> Content Types
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='forms')print 'active'; ?>">
			<a href="/admin/configurecontent/forms">
				<i class="icon-list-alt"></i> Forms
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='widgets')print 'active'; ?>" style="display:none;">
			<a href="/admin/configurecontent/widgets">
				<i class="icon-beaker"></i> Widgets
			</a>
		</li>
		<?php endif; ?>
		<li class="divider"></li>
		<?php $tpl->navContentTypes(); ?>
	</ul>
</li>
<?php endif; ?>