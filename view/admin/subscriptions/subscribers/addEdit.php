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
						$extras = $tpl->get('aboveForm');
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
										for ($i = 0; $i < count($subscriptions); $i++):
											?>
											<option <?php if ($tpl->get('subscription_id') == $subscriptions[$i]['id']) print "selected=\"true\""; ?>
												value="<?php print $subscriptions[$i]['id'] ?>"><?php print $subscriptions[$i]['name'] ?></option>
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
								<label class="control-label">Creation Date</label>

								<div class="controls">
									<input type="text" name="timestamp"
										   class="validate[required] datepicker-subscription-timestamp"
										   value="<?php $tpl->timestamp; ?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">Expiry Date</label>

								<div class="controls">
									<input type="text" name="expiry_timestamp" class="datepicker-subscription-timestamp"
										   data-prompt-position="topLeft" value="<?php $tpl->expiry_timestamp; ?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">Status</label>

								<div class="controls">
									<select class="input-small" name="status">
										<?php
										$statuses = $tpl->getStatues();
										for ($i = 0; $i < count($statuses); $i++):
											?>
											<option <?php if ($tpl->get('status') == $statuses[$i]) print "selected=\"true\""; ?>
												value="<?php print $statuses[$i] ?>"><?php print $tpl->formatStatus($statuses[$i]) ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>

							<?php if ($tpl->get('extraOptions')['isAdding']): ?>
								<div class="control-group">
									<label class="control-label">Is New</label>

									<div class="controls">
										<input type="checkbox" name="is_new">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Credit Card No.</label>

									<div class="controls">
										<input type="text" name="cc[number]" value=<?php $tpl->user_id; ?>>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Credit Card Code</label>

									<div class="controls">
										<input type="text" name="cc[ccv]" value=<?php $tpl->user_id; ?>>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Credit Card Expiry Month</label>

									<div class="controls">
										<select name="cc[expiry-month]" class="form-control">
											<option value="">Expiry Month</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Credit Card Expiry Year</label>

									<div class="controls">
										<select name="cc[expiry-year]" class="form-control">
											<option value="">Expiry Year</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>
										</select>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<?php
						$extras = $tpl->get('belowForm');
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