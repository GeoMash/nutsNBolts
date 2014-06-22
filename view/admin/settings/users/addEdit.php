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
								<label class="control-label">Email Address</label>
								<div class="controls">
									<input type="text" name="email" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->email; ?>" autocomplete="off">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">First Name</label>
								<div class="controls">
									<input type="text" name="name_first" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->name_first; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Last Name</label>
								<div class="controls">
									<input type="text" name="name_last" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->name_last; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Gender</label>
								<div class="controls">
									<select class="chzn-select" name="gender">
										<?php
										$gender=(bool)$tpl->get('gender');
										?>
										<option value="0" <?php print (!$gender)?'selected':''; ?>>Male</option>
										<option value="1" <?php print ($gender)?'selected':''; ?>>Female</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Company</label>
								<div class="controls">
									<input type="text" name="company" data-prompt-position="topLeft" value="<?php $tpl->company; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Position</label>
								<div class="controls">
									<input type="text" name="position" data-prompt-position="topLeft" value="<?php $tpl->position; ?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">Phone Number</label>
								<div class="controls">
									<input type="text" name="phone" data-prompt-position="topLeft" value="<?php $tpl->phone; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Date of Birth</label>
								<div class="controls">
									<input type="text" name="dob" class="datepicker" data-prompt-position="topLeft" value="<?php $tpl->dob; ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Income Range</label>
								<div class="controls">
									<input type="text" name="income_range" data-prompt-position="topLeft" value="<?php $tpl->income_range; ?>">
								</div>
							</div>
							
							
							
							
							
						</div>
						<div class="container-fluid padded">
							<div class="box">
								<div class="box-header">
									<span class="title"><i class="icon-key"></i> Password</span>
								</div>
								<div class="content-box">
									<div class="padded">
										<?php if (!$tpl->getPolicy('password','force_random')): ?>
										<div class="control-group">
											<label class="control-label">Force Change</label>
											<div class="controls">
												<input type="checkbox" name="force_change" value="1">
												(on next login)
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Set Random</label>
											<div class="controls">
												<input type="checkbox" name="set_random" value="1">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">New Password</label>
											<div class="controls">
												<input type="password" name="password" autocomplete="off">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Confirm New Password</label>
											<div class="controls">
												<input type="password" name="password_confirm" autocomplete="off">
											</div>
										</div>
										<?php
										else:
											if (!$tpl->get('id')):
										?>
										<input type="hidden" name="set_random" value="1">
										<span>Current password policy is set to randomly generate user passwords. The user will be emailed their new password.</span>
										<?php
											
											else:
										?>
										<div class="control-group">
											<label class="control-label">New Random Password</label>
											<div class="controls">
												<input type="checkbox" name="set_random" value="1"><span class="help-inline">(The user will be emailed their new password.)</span>
											</div>
										</div>
										<?php
											endif;
										?>
										
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="container-fluid padded">
							<div class="box">
								<div class="box-header">
									<span class="title"><i class="icon-beaker"></i> Roles</span>
								</div>
								<div class="content-box">
									<div class="padded">
										<table class="table table-hover">
											<thead>
												<tr>
													<th><div>Grant</div></th>
													<th><div>Name</div></th>
													<th><div>Description</div></th>
												</tr>
											</thead>
											<tbody>
												<?php $tpl->getUserRoles(); ?>
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