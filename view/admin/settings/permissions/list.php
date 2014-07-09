<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<span class="title">Roles</span>
					<ul class="box-toolbar">
						<li>
							<a href="/admin/settings/permissions/add/">
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
									<th><div>Key</div></th>
									<th><div>Name</div></th>
									<th><div>Description</div></th>
									<th><div>Remove</div></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$permissions=$tpl->getPermissions();
								for ($i=0,$j=count($permissions); $i<$j; $i++):
								?>
								<tr>
									<!--<td class="text-center"><input type="checkbox" name="compare[]" value="{$records[$i]['id']}"></td>-->
									<td class=""><a href="/admin/settings/permissions/edit/<?php print $permissions[$i]['id']; ?>"><?php print $permissions[$i]['key']; ?></a></td>
									<td class=""><?php print $permissions[$i]['name']; ?></td>
									<td class=""><?php print $permissions[$i]['description']; ?></td>
									<td class="center">
										<a href="/admin/settings/permissions/remove/<?php print $permissions[$i]['id']; ?>">
											<button title="Remove" data-toggle="tooltip" class="btn btn-mini btn-red">
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