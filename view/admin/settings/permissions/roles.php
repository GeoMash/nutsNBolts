<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<span class="title">Roles</span>
					<ul class="box-toolbar">
						<li>
							<a href="/admin/settings/permissions/addRole/">
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
									<!--<th><div>Compare</div></th>-->
									<th><div>Name</div></th>
									<th><div>Description</div></th>
									<th><div>Reference</div></th>
									<th><div>Status</div></th>
									<th><div>Remove</div></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$roles=$tpl->getRoles();
								for ($i=0,$j=count($roles); $i<$j; $i++):
								?>
								<tr>
									<!--<td class="text-center"><input type="checkbox" name="compare[]" value="{$records[$i]['id']}"></td>-->
									<td class=""><a href="/admin/settings/permissions/editRole/<?php print $roles[$i]['id']; ?>"><?php print $roles[$i]['name']; ?></a></td>
									<td class=""><?php print $roles[$i]['description']; ?></td>
									<td class=""><?php print $roles[$i]['ref']; ?></td>
									<td class=""><?php print $roles[$i]['status']; ?></td>
									<td class="center">
										<a href="/admin/settings/users/remove/<?php print $roles[$i]['id']; ?>">
											<button title="Archive" class="btn btn-mini btn-red">
												<i class="icon-remove"></i>
											</button>
										</a>
									</td>
								</tr>
								<?php
								endfor;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>