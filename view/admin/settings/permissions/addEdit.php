<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal fill-up validatable" method="post">
					<input type="hidden" name="id" value="<?php $tpl->id; ?>">
					<div class="box-header">
						<span class="title">Permission</span>
					</div>
					<div class="box-content">
						<div class="padded">
							<div class="control-group">
								<label class="control-label">Key</label>
								<div class="controls">
									<input type="text" name="key" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->key; ?>" autocomplete="off">
								</div>
							</div>
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
								<label class="control-label">Category</label>
								<div class="controls">
									<input type="text" name="category" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->category; ?>" autocomplete="off">
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