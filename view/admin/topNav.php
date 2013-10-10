<?php
$user=$tpl->get('user');
?>
<div class="nav-collapse nav-collapse-top collapse">
	<ul class="nav full pull-right">
		<li class="dropdown user-avatar">

			<!-- the dropdown has a custom user-avatar class, this is the small avatar with the badge -->

			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<span>
					<!-- <img class="menu-avatar" src="/images/avatars/avatar1.jpg" />  -->
					<span><?php print $user['name_first'].' '.$user['name_last']; ?> <i class="icon-caret-down"></i></span>
				</span>
			</a>

			<ul class="dropdown-menu">

				<!-- the first element is the one with the big avatar, add a with-image class to it -->

				<li class="with-image">
					<div class="avatar">
						<img src="<?php echo $user['image']; ?>" />
					</div>
					<span><?php print $user['name_first'].' '.$user['name_last']; ?></span>
				</li>

				<li class="divider"></li>
				<li><a href="/admin/messages"><i class="icon-inbox"></i> <span>Messages - <span style="font-weight:bold;color:red">0</span></span></a></li>
				<li><a href="/admin/profile"><i class="icon-user"></i> <span>Profile</span></a></li>
				<li><a href="/admin/logout"><i class="icon-off"></i> <span>Logout</span></a></li>
			</ul>
		</li>
	</ul>
</div>
