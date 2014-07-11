<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal fill-up validatable" method="post">
					<input type="hidden" name="id" value="<?php $tpl->id; ?>">
					<div class="box-header">
						<span class="title">User Subscription Details</span>
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
								<label class="control-label">Package Name</label>
								<div class="controls">
									<select class="input-small" name="subscription_id">
										<?php
										$subscriptions = $tpl->getSubscriptions();
										for($i = 0; $i < count($subscriptions); $i++):
										?>
										<option <?php if($tpl->get('subscription_id') == $subscriptions[$i]['id']) print "selected=\"true\""; ?> value="<?php print $subscriptions[$i]['id'] ?>"><?php print $subscriptions[$i]['name'] ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">Subscriber Name</label>
								<div class="controls">
									<input data-role="selectUser" name="user_id" value=<?php $tpl->user_id; ?>>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">Subscriber Email</label>
								<div class="controls">
									<input readonly="true" disabled="true" type="text" name="subscriber_email" class="validate[required]" data-prompt-position="topLeft" value="<?php print $tpl->getSubscriberEmail($tpl->get('user_id')); ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">Creation Date</label>
								<div class="controls">
									<input type="text" name="timestamp" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->timestamp; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Expiry Date</label>
								<div class="controls">
									<input type="text" name="expiry_timestamp" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->expiry_timestamp; ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">Status</label>
								<div class="controls">
									<select class="input-small" name="status">
										<?php
										$statuses = $tpl->getStatues();
										for($i = 0; $i < count($statuses); $i++):
										?>
										<option <?php if($tpl->get('status') == $statuses[$i]) print "selected=\"true\""; ?> value="<?php print $statuses[$i] ?>"><?php print $tpl->formatStatus($statuses[$i]) ?></option>
										<?php endfor; ?>
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