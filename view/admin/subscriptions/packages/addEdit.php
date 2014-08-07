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
								<label class="control-label">Amount</label>
								<div class="controls">
									<input type="number" name="amount" data-prompt-position="topLeft" value="<?php $tpl->amount; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Currency</label>
								<div class="controls">
									<select class="input-small" name="currency">
										<option "<?php print $tpl->get('currency') == 'USD'? 'selected' : ''; ?>" value="USD">USD</option>
										<option "<?php print $tpl->get('currency') == 'CAD'? 'selected' : ''; ?>" value="USD">CAD</option>
										<option "<?php print $tpl->get('currency') == 'EUR'? 'selected' : ''; ?>" value="USD">EUR</option>
										<option "<?php print $tpl->get('currency') == 'GBP'? 'selected' : ''; ?>" value="USD">GBP</option>
										<option "<?php print $tpl->get('currency') == 'AUD'? 'selected' : ''; ?>" value="USD">AUD</option>
										<option "<?php print $tpl->get('currency') == 'NZD'? 'selected' : ''; ?>" value="USD">NZD</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Duration</label>
								<div class="controls">
									<input type="number" min="0" name="duration" data-prompt-position="topLeft" value="<?php $tpl->duration; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Total Bills</label>
								<div class="controls">
									<input type="number" min="0" name="total_bills" data-prompt-position="topLeft" value="<?php $tpl->total_bills; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Trial Period</label>
								<div class="controls">
									<input type="number" min="0" name="trial_period" data-prompt-position="topLeft" value="<?php $tpl->trial_period; ?>">
									<span class="help-inline">Days</span>
								</div>
							</div>							
							<div class="control-group">
								<label class="control-label">Billing Interval</label>
								<div class="controls">
									<input class="input-small" type="number" min=0 name="billing_interval" data-prompt-position="topLeft" value="<?php $tpl->billing_interval; ?>">
									<span class="help-inline">Months</span>
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