<div class="container-fluid padded">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<form id="addEditContentForm" class="form-horizontal fill-up validatable" method="post">
						<input type="hidden" name="id" value="<?php $tpl->id; ?>">
						<input type="hidden" name="transition_id" value="">
						<div class="box-header">
							<span class="title"><i class="<?php $tpl->contentTypeIcon; ?>"></i> <?php $tpl->contentType; ?></span>
							<?php if (!$tpl->get('hasWorkflow')): ?>
							<ul class="box-toolbar">
								<li>
									<span>Published: </span>
								</li>
								<li>
									<input type="checkbox" class="iButton-icons" <?php print (bool)$tpl->get('status')?'checked':''; ?> name="status" value="2" />
								</li>
							</ul>
							<?php endif; ?>
						</div>
						<div class="box-content">
							<?php
							$extras=$tpl->get('aboveForm');
							foreach ($extras as $extra)
							{
								print $extra;
							}
							?>
							<div class="padded">
								<div class="control-group">
									<label class="control-label">Title</label>
									<div class="controls">
										<input type="text" name="title" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->title; ?>">
									</div>
								</div>
								<?php $tpl->parts; ?>
								<div class="control-group">
									<label class="control-label">Tags</label>
									<div class="controls">
										<textarea class="tags" name="tags" placeholder="add a tag"><?php $tpl->nodeTags; ?></textarea>
									</div>
								</div>
								<?php if ($tpl->can('admin.content.node.ownership')): ?>
								<div class="control-group">
									<label class="control-label">Ownership</label>
									<div class="controls">
										<input data-role="selectUser" name="owner_user_id" value="<?php $tpl->owner_user_id; ?>">
									</div>
								</div>
								<?php endif; ?>
							</div>
							<div class="container-fluid padded">
								<div class="box">
									<div class="box-header">
										<span class="title"><i class="icon-globe"></i> URL Paths</span>
									</div>
									<div class="content-box">
										<div class="padded">
											<div class="well relative">
												<table class="table table-hover">
													<thead>
														<th class="span4">Path</th>
														<th>Remove</th>
													</thead>
													<tbody>
														<?php
														$urls=$tpl->get('nodeURLs');
														for ($i=0,$j=count($urls); $i<$j; $i++):
														?>
														<tr>
															<td>
																<input type="text" class="input-medium" value="<?php print $urls[$i]['url']; ?>" data-prompt-position="topLeft" class="validate[required]" name="url[]">
															</td>
															<td>
																<button data-action="removeURLfromContent" class="btn btn-danger btn-mini" type="button">&times;</button>
															</td>
														</tr>
														<?php endfor; ?>
													</tbody>
												</table>
												<button type="button" class="btn btn-green" data-action="addURLToContent"><i class="icon-plus"></i> Add URL Path</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="container-fluid padded">
								<div class="box">
									<div class="box-header">
										<span class="title"><i class="icon-bolt"></i> User Access</span>
									</div>
									<div class="content-box">
										<div class="padded">
											<div class="well relative">
												<input data-role="selectUser" name="userAccess">
											</div>
											<table id="selectUserTable" class="table table-hover">
												<thead>
													<tr class="text-center">
														<th class="span3">User</th>
														<th class="span3">Create</th>
														<th class="span3">Read</th>
														<th class="span3">Update</th>
														<th class="span3">Delete</th>
														<th class="span2">Remove</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$userAccess=$tpl->get('userAccess');
													for ($i=0,$j=count($userAccess); $i<$j; $i++):
													?>
													<tr class="text-center">
														<td><?php print $userAccess[$i]['user']['name_first'].' '.$userAccess[$i]['user']['name_last'].' ('.$userAccess[$i]['user']['email'].')'; ?></td>
														<td><input type="checkbox" name="userAccess[create][<?php print $userAccess[$i]['user']['id']; ?>]" value="1" <?php print (bool)(int)$userAccess[$i]['create']?'checked':''; ?>></td>
														<td><input type="checkbox" name="userAccess[read][<?php print $userAccess[$i]['user']['id']; ?>]" value="1" <?php print (bool)(int)$userAccess[$i]['read']?'checked':''; ?>></td>
														<td><input type="checkbox" name="userAccess[update][<?php print $userAccess[$i]['user']['id']; ?>]" value="1" <?php print (bool)(int)$userAccess[$i]['update']?'checked':''; ?>></td>
														<td><input type="checkbox" name="userAccess[delete][<?php print $userAccess[$i]['user']['id']; ?>]" value="1" <?php print (bool)(int)$userAccess[$i]['delete']?'checked':''; ?>></td>
														<td><button data-action="removeRow" class="btn btn-danger btn-mini" type="button">&times;</button></td>
													</tr>
													<?php endfor; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<?php
							$extras=$tpl->get('belowForm');
							foreach ($extras as $extra)
							{
								print $extra;
							}
							?>
							<div class="container-fluid padded">
								<div class="box">
									<div class="box-header">
										<span class="title"><i class="icon-bolt"></i> Other Actions</span>
									</div>
									<div class="content-box">
										<div class="padded">
											<ul>
												<li><a href="/admin/configurecontent/types/edit/<?php $tpl->contentTypeId; ?>?returnToAction=/admin/content/edit/<?php $tpl->id; ?>">Edit associated <b>Content Type</b></a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="pull-right">
									<?php if (!$tpl->get('hasWorkflow')): ?>
									<button class="btn btn-blue">Save Changes</button>
									<?php
									else:
										$tpl->getWorkflowTransitions();
									endif;
									?>
<button type="button" class="btn btn-red">Cancel</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>