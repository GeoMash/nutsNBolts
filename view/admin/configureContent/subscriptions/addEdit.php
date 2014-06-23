<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal fill-up validatable" method="post">
					<input type="hidden" name="id" value="<?php $tpl->id; ?>">
					<div class="box-header">
						<span class="title">User Details</span>
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
						<?php
						$extras=$tpl->get('aboveForm');
						foreach ($extras as $extra)
						{
							print $extra;
						}
						?>
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
								<label class="control-label">Duration</label>
								<div class="controls">
									<input class="input-small" type="number" name="duration" data-prompt-position="topLeft" value="<?php $tpl->duration; ?>">
									<span class="help-inline">Months</span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Recurring</label>
								<div class="controls">
									<input type="checkbox" name="recurring" class="validate[required]" data-prompt-position="topLeft" value="1" <?php print (bool)$tpl->get('recurring')?'checked':''; ?>>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Amount</label>
								<div class="controls">
									<input type="number" name="amount" data-prompt-position="topLeft" value="<?php $tpl->amount; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Currency</label>
								<div class="controls">
									<select class="input-small" name="currency">
										<option value="USD">USD</option>
										<option value="CAD">CAD</option>
										<option value="EUR">EUR</option>
										<option value="GBP">GBP</option>
										<option value="AUD">AUD</option>
										<option value="NZD">NZD</option>
									</select>
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