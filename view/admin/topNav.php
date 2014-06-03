<?php
$user=$tpl->get('user');
$gravatarHash=md5(strtolower(trim($user['email'])));
?>
<div class="nav-collapse nav-collapse-top collapse">
	<ul class="nav nav-2x">
		<?php $tpl->loadView('/admin/nav/dashboard'); ?>
		<?php $tpl->loadView('/admin/nav/fileManager'); ?>
		<?php $tpl->loadView('/admin/nav/content'); ?>
		<?php $tpl->loadView('/admin/nav/pages'); ?>
		<?php $tpl->loadView('/admin/nav/settings'); ?>
	</ul>
	<ul class="nav full pull-right">
		<li class="dropdown user-avatar">

			<!-- the dropdown has a custom user-avatar class, this is the small avatar with the badge -->

			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<span>
					<?php if(isset($user['image']) && strlen($user['image'])>0): ?>
						<img src="<?php echo $user['image']; ?>" />
					<?php else: ?>
						<img src="http://www.gravatar.com/avatar/<?php print $gravatarHash; ?>" />
					<?php endif; ?>
					<span><?php print $user['name_first'].' '.$user['name_last']; ?> <i class="icon-caret-down"></i></span>
					<span class="badge badge-dark-red"><?php $tpl->getUnreadMessages(); ?></span>
				</span>
			</a>

			<ul class="dropdown-menu">

				<!-- the first element is the one with the big avatar, add a with-image class to it -->

				<li class="with-image">
					<div class="avatar">
					<?php if(isset($user['image']) && strlen($user['image'])>0): ?>
						<img src="<?php echo $user['image']; ?>" />
					<?php else: ?>
						<img src="http://www.gravatar.com/avatar/<?php print $gravatarHash; ?>" />
					<?php endif; ?>
					</div>
					<span><?php print $user['name_first'].' '.$user['name_last']; ?></span>
				</li>

				<li class="divider"></li>
				<li><a href="/admin/messages"><i class="icon-inbox"></i> <span>Messages - <span style="font-weight:bold;color:red"><?php $tpl->getUnreadMessages(); ?></span></span></a></li>
				<li><a href="/admin/profile"><i class="icon-user"></i> <span>Profile</span></a></li>
				<li><a href="/admin/logout"><i class="icon-off"></i> <span>Logout</span></a></li>
			</ul>
		</li>
	</ul>
	<ul class="nav pull-right">
		<li><a href="/" target="_blank" title="View Website"><i class="icon-globe"></i> View Website</a></li>
	</ul>
</div>
