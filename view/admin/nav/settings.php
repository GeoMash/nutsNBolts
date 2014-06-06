<?php if ($tpl->challengeRole(array('SUPER','ADMIN'))): ?>
<li class="dropdown">
	<a class="dropdown-toggle" href="#" data-toggle="dropdown">
		<i class="icon-wrench icon-2x"></i>
		<span>
			Settings
			<b class="caret"></b>
		</span>
	</a>
	<ul class="dropdown-menu">
		<li class="<?php if ($tpl->get('nav_active_sub')=='nnb')print 'active'; ?>">
			<a href="/admin/settings/nnb">
				<i class="icon-circle-blank"></i> Nuts n Bolts Settings
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='site')print 'active'; ?>">
			<a href="/admin/settings/site">
				<i class="icon-circle"></i> Site Settings
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='general')print 'active'; ?>">
			<a href="/admin/settings/dashboard">
				<i class="icon-dashboard"></i> Configure Dashboard
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='users')print 'active'; ?>">
			<a href="/admin/settings/users">
				<i class="icon-user"></i> Users
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='passwordPolicy')print 'active'; ?>">
			<a href="/admin/settings/passwordPolicy">
				<i class="icon-user"></i> Password Policy
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='plugins')print 'active'; ?>">
			<a href="/admin/settings/permissions">
				<i class="icon-bolt"></i> Permissions
			</a>
		</li>
		<li class="<?php if ($tpl->get('nav_active_sub')=='plugins')print 'active'; ?>" style="display:none;">
			<a href="/admin/settings/plugins">
				<i class="icon-bolt"></i> Plugins
			</a>
		</li>
	</ul>
</li>
<?php endif; ?>