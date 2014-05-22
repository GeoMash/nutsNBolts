<ul class="nav nav-collapse collapse nav-collapse-primary">
	<?php
	$tpl->loadView('/admin/mainNav/dashboard');
	if ($tpl->challengeRole(array('SUPER','ADMIN'))):
	?>
	<li class="dark-nav <?php if ($tpl->get('nav_active_main')=='settings')print 'active'; ?>">
		<span class="glow"></span>
		<a data-toggle="collapse" href="#settings-subs">
			<i class="icon-wrench icon-2x"></i>
			<span>
				Settings
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="settings-subs" class="collapse <?php if ($tpl->get('nav_active_main')=='settings')print 'in'; ?>">
			<li class="<?php if ($tpl->get('nav_active_sub')=='general')print 'active'; ?>">
				<a href="/admin/settings/nnb">
					<i class="icon-circle-blank"></i> Nuts n Bolts Settings
				</a>
			</li>
			<li class="<?php if ($tpl->get('nav_active_sub')=='general')print 'active'; ?>">
				<a href="/admin/settings/site">
					<i class="icon-circle"></i> Site Settings
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
			<li class="<?php if ($tpl->get('nav_active_sub')=='files')print 'active'; ?>">
				<a href="#fileManagerWindow" role="button" data-toggle="bigmodal">
					<i class="icon-folder-open"></i> File Manager
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
	<?php
	if ($tpl->challengeRole(array('SUPER','ADMIN','CONTENT_CREATOR','CONTENT_EDITOR','BLOGGER'))):
	?>
	<li class="dark-nav <?php if ($tpl->get('nav_active_main')=='content')print 'active'; ?>">
		<span class="glow"></span>
		<a class="accordion-toggle collapsed" data-toggle="collapse" href="#content-subs">
			<i class="icon-edit icon-2x"></i>
			<span>
				Content
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="content-subs" class="collapse <?php if ($tpl->get('nav_active_main')=='content')print 'in'; ?>">
			<?php $tpl->navContentTypes(); ?>
		</ul>
	</li>
	<?php
	endif;
	if ($tpl->challengeRole(array('SUPER','ADMIN'))):
	?>
	<li class="dark-nav <?php if ($tpl->get('nav_active_main')=='configurepages')print 'active'; ?>">
		<span class="glow"></span>
		<a data-toggle="collapse" href="#pages-config-subs">
			<i class="icon-qrcode icon-2x"></i>
			<span>
				Configure Pages
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="pages-config-subs" class="collapse <?php if ($tpl->get('nav_active_main')=='configurepages')print 'in'; ?>">
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
	<?php
	endif;
	if ($tpl->challengeRole(array('SUPER','ADMIN'))):
	?>
	<li class="dark-nav <?php if ($tpl->get('nav_active_main')=='configurecontent')print 'active'; ?>">
		<span class="glow"></span>
		<a data-toggle="collapse" href="#content-config-subs">
			<i class="icon-cogs icon-2x"></i>
			<span>
				Configure Content
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="content-config-subs" class="collapse <?php if ($tpl->get('nav_active_main')=='configurecontent')print 'in'; ?>">
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
		</ul>
	</li>
	<?php
	endif;
	?>
</ul>