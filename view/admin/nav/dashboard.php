<?php if ($tpl->can('admin.dashboard.read')): ?>
<li class="dropdown <?php if ($tpl->get('nav_active_main')=='dashboard')print 'active'; ?>">
	<a class="dropdown-toggle" href="/admin">
		<i class="icon-home icon-2x"></i>
	</a>
</li>
<?php
endif;
?>