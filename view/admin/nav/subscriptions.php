<?php
if ($tpl->can('admin.subscription.package.read')
|| $tpl->can('admin.subscription.subscriber.read')):
?>
<li class="dropdown">
	<a class="dropdown-toggle" href="#" data-toggle="dropdown">
		<i class="icon-envelope icon-2x"></i>
		<span>
			Subscriptions
			<b class="caret"></b>
		</span>
	</a>
	<ul class="dropdown-menu">
		<?php if ($tpl->can('admin.subscription.package.read')): ?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='packages')print 'active'; ?>">
			<a href="/admin/subscriptions/packages">
				<i class="icon-gift"></i> Packages
			</a>
		</li>
		<?php
			endif;
			if ($tpl->can('admin.subscription.subscriber.read')):
		?>
		<li class="<?php if ($tpl->get('nav_active_sub')=='subscribers')print 'active'; ?>">
			<a href="/admin/subscriptions/subscribers">
				<i class="icon-th"></i> Subscribers
			</a>
		</li>
		<?php endif; ?>
	</ul>
</li>
<?php endif; ?>