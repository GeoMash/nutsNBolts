<?php
$user=$tpl->get('user');
?>
<div class="nav-collapse nav-collapse-top collapse">
	<ul class="nav full pull-right">
		<li class="dropdown user-avatar">

			<!-- the dropdown has a custom user-avatar class, this is the small avatar with the badge -->

			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<span>
					<img class="menu-avatar" src="<?php echo $user['image']; ?>" />
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
						<img src="/images/avatars/silhouette.png" />
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
</div>
