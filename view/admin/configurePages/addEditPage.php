<div class="container-fluid padded">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<form class="form-horizontal fill-up validatable" method="post">
						<input type="hidden" name="id" value="<?php $tpl->id; ?>">
						<div class="box-header">
							<span class="title">Page</span>
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
									<label class="control-label">Type</label>
									<div class="controls">
										<select class="chzn-select" name="page_type_id">
											<?php $tpl->getPageTypeOptions($tpl->get('page_type_id')); ?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">URL</label>
									<div class="controls">
										<input type="text" name="url" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->url; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Title</label>
									<div class="controls">
										<input type="text" name="title" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->title; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Description</label>
									<div class="controls">
										<textarea name="description" rows="6"><?php $tpl->description; ?></textarea>
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
</div>