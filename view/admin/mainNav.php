<ul class="nav nav-collapse collapse nav-collapse-primary">
	<li class="active">
		<span class="glow"></span>
		<a href="/admin">
			<i class="icon-dashboard icon-2x"></i>
			<span>Dashboard</span>
		</a>
	</li>
	<li class="dark-nav">
		<span class="glow"></span>
		<a class="accordion-toggle collapsed" data-toggle="collapse" href="#content-subs">
			<i class="icon-edit icon-2x"></i>
			<span>
				Content
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="content-subs" class="collapse">
			<?php $tpl->navContentTypes(); ?>
		</ul>
	</li>
	<li class="dark-nav">
		<span class="glow"></span>
		<a data-toggle="collapse" href="#pages-config-subs">
			<i class="icon-qrcode icon-2x"></i>
			<span>
				Configure Pages
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="pages-config-subs" class="collapse">
			<li class="">
				<a href="/admin/configurepages/types">
					<i class="icon-th"></i> Types
				</a>
			</li>
			<li class="">
				<a href="/admin/configurepages/pages">
					<i class="icon-copy"></i> Pages
				</a>
			</li>
			<li class="">
				<a href="/admin/configurepages/templates">
					<i class="icon-file-alt"></i> Templates
				</a>
			</li>
			<li class="">
				<a href="/admin/configurecontent/navigation">
					<i class="icon-tasks"></i> Navigation
				</a>
			</li>
		</ul>
	</li>
	<li class="dark-nav">
		<span class="glow"></span>
		<a data-toggle="collapse" href="#content-config-subs">
			<i class="icon-cogs icon-2x"></i>
			<span>
				Configure Content
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="content-config-subs" class="collapse">
			<li class="">
				<a href="/admin/configurecontent/types">
					<i class="icon-th"></i> Content Types
				</a>
			</li>
			<li class="">
				<a href="/admin/configurecontent/widgets">
					<i class="icon-beaker"></i> Widgets
				</a>
			</li>
		</ul>
	</li>
	<li class="dark-nav">
		<span class="glow"></span>
		<a data-toggle="collapse" href="#settings-subs">
			<i class="icon-wrench icon-2x"></i>
			<span>
				System Settings
				<i class="icon-caret-down"></i>
			</span>
		</a>
		<ul id="settings-subs" class="collapse">
			<li class="">
				<a href="/admin/settings/general">
					<i class="icon-circle-blank"></i> General
				</a>
			</li>
			<li class="">
				<a href="/admin/settings/users">
					<i class="icon-user"></i> Users
				</a>
			</li>
			<li class="">
				<a href="/admin/settings/files">
					<i class="icon-folder-open"></i> File Manager
				</a>
			</li>
			<li class="">
				<a href="/admin/settings/plugins">
					<i class="icon-bolt"></i> Plugins
				</a>
			</li>
		</ul>
	</li>
</ul>