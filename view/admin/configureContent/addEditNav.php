<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal fill-up validatable" method="post">
					<input type="hidden" name="id" value="<?php $tpl->id; ?>">
					<div class="box-header">
						<span class="title">Navigation</span>
						<ul class="box-toolbar">
							<li>
								<span>Enabled: </span>
							</li>
							<li>
								<input type="checkbox" class="iButton-icons" <?php print (bool)$tpl->get('status')?'checked':''; ?> name="status" value="1">
							</li>
						</ul>
					</div>
					<div class="box-content">
						<div class="padded">
							<div class="control-group">
								<label class="control-label">Name</label>
								<div class="controls">
									<input type="text" name="name" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->name; ?>">
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
									<input type="text" name="ref" value="<?php $tpl->ref; ?>">
								</div>
							</div>
							<div class="container-fluid padded">
								<div class="box">
									<div class="box-header">
										<span class="title"><i class="icon-globe"></i> Navigation Items</span>
									</div>
									<div style="display:none;">
										<div data-template="pageOptions">
											<option value="select">Select One</option>
											<?php $tpl->printPageOptions(); ?>
										</div>
										<div data-template="nodeOptions">
											<option value="select">Select One</option>
											<?php $tpl->printNodeOptions(); ?>
										</div>
									</div>
									<div class="content-box">
										<div class="padded">
											<div class="well relative">
												<table data-sortable="true" class="table table-hover">
													<thead>
														<tr>
															<th class="span1"></th>
															<th class="span4">Label</th>
															<th class="span4">Type</th>
															<th class="span4">Link To</th>
															<th class="span2">Status</th>
															<th>Remove</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$navItems=$tpl->get('navItems');
														for ($i=0,$j=count($navItems); $i<$j; $i++):
														?>
														<tr>
															<td>
																<input type="hidden" name="order[]" value="0">
																<p><i class="icon-reorder cursor-move"></i></p>
															</td>
															<td>
																<input type="text" class="input-medium" value="<?php print $navItems[$i]['label']; ?>" name="label[]">
															</td>
															<td>
																<select name="type" data-action="navChangeType">
																	<option value="select">Select Type</option>
																	<option <?php print (!empty($navItems[$i]['page_id']))?'selected':''; ?> value="page">Page</option>
																	<option <?php print (!empty($navItems[$i]['node_id']))?'selected':''; ?> value="node">Content Item</option>
																	<option <?php print (!empty($navItems[$i]['url']))?'selected':''; ?> value="url">URL</option>
																</select>
															</td>
															<td>
																<?php if (!empty($navItems[$i]['page_id'])): ?>
																<input type="hidden" class="input-medium" value="" name="node_id[]">
																<input type="hidden" class="input-medium" value="" name="url[]">
																<select name="page_id[]">
																	<option value="select">Select One</option>
																	<?php $tpl->printPageOptions($navItems[$i]['page_id']); ?>
																</select>
																<?php elseif (!empty($navItems[$i]['node_id'])): ?>
																<input type="hidden" class="input-medium" value="" name="page_id[]">
																<input type="hidden" class="input-medium" value="" name="url[]">
																<select name="node_id[]">
																	<option value="select">Select One</option>
																	<?php $tpl->printNodeOptions($navItems[$i]['node_id']); ?>
																</select>
																<?php else: ?>
																<input type="hidden" class="input-medium" value="" name="page_id[]">
																<input type="hidden" class="input-medium" value="" name="node_id[]">
																<input type="text" class="input-medium" value="<?php print $navItems[$i]['url']; ?>" name="url[]">
																<?php endif; ?>
															</td>
															<td>
																<button data-action="removeRow" class="btn btn-danger btn-mini" type="button">&times;</button>
															</td>
														</tr>
														<?php endfor; ?>
													</tbody>
												</table>
												<button type="button" class="btn btn-green" data-action="addNavigationRow"><i class="icon-plus"></i> Add Navigation Item</button>
											</div>
										</div>
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