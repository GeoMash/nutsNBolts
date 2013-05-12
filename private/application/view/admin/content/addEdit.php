<div class="container-fluid padded">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<form class="form-horizontal fill-up validatable" method="post">
						<input type="hidden" name="id" value="<?php $tpl->id; ?>">
						<div class="box-header">
							<span class="title">Content Type</span>
							<ul class="box-toolbar">
								<li>
									<span>Enabled: </span>
								</li>
								<li>
									<input type="checkbox" class="iButton-icons" checked="checked" name="status" value="1" />
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
									<label class="control-label">Icon</label>
									<div class="controls">
										<input type="text" name="icon" value="<?php $tpl->icon; ?>">
									</div>
								</div>
							</div>
							<div class="container-fluid padded">
								<div class="box">
									<div class="box-header">
										<span class="title"><i class="icon-beaker"></i> Content Widgets</span>
									</div>
									<div class="content-box">
										<div class="padded">
											<?php $tpl->parts; ?>
											<button type="button" class="btn btn-green" data-action="addWidgetSelectionRow"><i class="icon-plus"></i> Add Widget</button>
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
</div>