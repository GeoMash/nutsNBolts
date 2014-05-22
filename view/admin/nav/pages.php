<?php if ($tpl->challengeRole(array('SUPER','ADMIN'))): ?>
<li class="dropdown">
	<a class="dropdown-toggle" href="#" data-toggle="dropdown">
		<i class="icon-file icon-2x"></i>
		<span>
			Pages
			<b class="caret"></b>
		</span>
	</a>
	<ul class="dropdown-menu">
		<li class="<?php if ($tpl->get('nav_active_sub')=='types')print 'active'; ?>">
			<a href="/admin/configurepages/types">
				<i class="icon-th"></i> Types
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='pages')print 'active'; ?>">
			<a href="/admin/configurepages/pages">
				<i class="icon-copy"></i> Pages
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='templates')print 'active'; ?>" style="display:none;">
			<a href="/admin/configurepages/templates">
				<i class="icon-file-alt"></i> Templates
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='navigation')print 'active'; ?>" style="display:none;">
			<a href="/admin/configurecontent/navigation">
				<i class="icon-tasks"></i> Navigation
			</a>
		</li>
	</ul>
</li>
<?php endif; ?>