<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<span class="title">Users</span>
					<ul class="box-toolbar">
						<li>
							<a href="/admin/settings/users/add/">
								<span class="triangle-button green"><i class="icon-plus"></i></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="box-content">
					<div id="dataTables">
						<table cellpadding="0" cellspacing="0" border="0" class="dTable responsive">
							<thead>
								<tr>
									<th><div>Email Address</div></th>
									<th><div>Name</div></th>
									<th><div>Last Login</div></th>
									<th><div>Status</div></th>
									<th><div>Actions</div></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$users=$tpl->getUsers();
								$canImpersonate	=$tpl->can('admin.user.impersonate');
								$canDelete		=$tpl->can('admin.user.delete');
								for ($i=0,$j=count($users); $i<$j; $i++):
									$disabled=((int)$users[$i]['id']==-100);
								?>
								<tr>
									<td class=""><a href="/admin/settings/users/edit/<?php print $users[$i]['id']; ?>"><?php print $users[$i]['email']; ?></a></td>
									<td class=""><?php print $users[$i]['name_first']; ?> <?php print $users[$i]['name_last']; ?></td>
									<td class=""><?php print $users[$i]['date_lastlogin']; ?></td>
									<td class=""><?php print (bool)$users[$i]['status']?'Active':'Inactive'; ?></td>
									<td class="center">
										<?php
										if ($canImpersonate):
											if (!$disabled):
										?>
										<a href="/admin/settings/users/impersonate/<?php print $users[$i]['id']; ?>">
											<button title="Impersonate User" class="btn btn-mini btn-gray <?php print $disabled; ?>" data-toggle="tooltip">
												<i class="icon-eye-open"></i>
											</button>
										</a>
										<?php else: ?>
										<a href="javascript:{}">
											<button title="Impersonate User" class="btn btn-mini btn-gray disabled" data-toggle="tooltip">
												<i class="icon-eye-open"></i>
											</button>
										</a>
										<?php
											endif;
										endif;
										if ($canDelete):
											if (!$disabled):
										?>
										<a href="/admin/settings/users/remove/<?php print $users[$i]['id']; ?>">
											<button title="Remove User" class="btn btn-mini btn-red <?php print $disabled; ?>" data-toggle="tooltip">
												<i class="icon-remove"></i>
											</button>
										</a>
										<?php else: ?>
										<a href="javascript:{}">
											<button title="Remove User" class="btn btn-mini btn-red disabled" data-toggle="tooltip">
												<i class="icon-remove"></i>
											</button>
										</a>
										<?php
											endif;
										endif;
										?>
									</td>
								</tr>
								<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>