<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal fill-up validatable" method="post">
					<input type="hidden" name="id" value="<?php $tpl->id; ?>">
					<div class="box-header">
						<span class="title">Role</span>
						<ul class="box-toolbar">
							<li>
								<span>Enabled: </span>
							</li>
							<li>
								<input type="checkbox" class="iButton-icons" <?php print (bool)$tpl->get('status')?'checked':''; ?> name="status" value="1" />
							</li>
						</ul>
					</div>
					<div class="box-content">
						<div class="padded">
							<div class="control-group">
								<label class="control-label">Name</label>
								<div class="controls">
									<input type="text" name="name" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->name; ?>" autocomplete="off">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Description</label>
								<div class="controls">
									<textarea name="description" rows="6"><?php $tpl->description; ?></textarea>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Reference</label>
								<div class="controls">
									<input type="text" name="ref" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->ref; ?>" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="container-fluid padded">
							<div class="box">
								<div class="box-header">
									<span class="title"><i class="icon-bolt"></i> Permissions</span>
								</div>
								<div class="content-box">
									<div class="padded">
										<table class="table table-hover">
											<thead>
												<tr>
													<th class="span2"><div>Permit</div></th>
													<th class="span3"><div>Key</div></th>
													<th class="span3"><div>Name</div></th>
													<th><div>Description</div></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$permissions		=$tpl->getPermissionTable();
												$groupedPermissions	=[];
												for ($i=0,$j=count($permissions); $i<$j; $i++)
												{
													if (is_array(!$groupedPermissions[$permissions[$i]['category']]))
													{
														$groupedPermissions[$permissions[$i]['category']]=[];
													}
													$groupedPermissions[$permissions[$i]['category']][]=$permissions[$i];
												}
												foreach ($groupedPermissions as $category=>$permissionGroup):
												?>
												<tr>
													<td colspan="5"><h3><?php print $category; ?></h3></td>
												</tr>
													<?php
													for ($i=0,$j=count($permissionGroup); $i<$j; $i++):
														$checked=$permissionGroup[$i]['permit']?'checked':'';
													?>
													<tr>
														<td class=""><input type="checkbox" name="permit[]" value="<?php print $permissionGroup[$i]['id']; ?>" <?php print $checked; ?>></td>
														<td class=""><?php print $permissionGroup[$i]['key']; ?></td>
														<td class=""><?php print $permissionGroup[$i]['name']; ?></td>
														<td class=""><?php print $permissionGroup[$i]['description']; ?></td>
													</tr>
													<?php
													endfor;
												endforeach;
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="form-actions">
							<div class="pull-right">
								<button type="submit" class="btn btn-blue">Save Changes</button>
								<button type="button" class="btn btn-red">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>