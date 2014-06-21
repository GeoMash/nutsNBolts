<?php
$record=$tpl->get('record');
?>
<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal validatable" method="post">
					<div class="box-header">
						<ul class="nav nav-tabs nav-tabs-left">
							<li class="active"><a href="#passwords" data-toggle="tab"><i class="icon-key"></i> <span>Passwords</span></a></li>
							<li class=""><a href="#logging" data-toggle="tab"><i class="icon-book"></i> <span>Logging</span></a></li>
						</ul>
					</div>
					<div class="box-content wysiwyg-config">
						<div class="tab-content">
							<div class="tab-pane active" id="passwords">
								<table class="table table-normal">
									<thead>
										<tr>
											<td>Enabled/Disabled</td>
											<td>Name</td>
											<td>Setting</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_force_random'); ?> name="password_force_random[enabled]" value="1">
											</td>
											<td>Enable User Passwords</td>
											<td></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_length_minimum'); ?> name="password_length_minimum[enabled]" value="1">
											</td>
											<td>Minimum Length</td>
											<td><input class="input-small" type="number" name="password_length_minimum[value]" value="<?php print $record['password_length_minimum'] ?>"></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_length_maximum'); ?> name="password_length_maximum[enabled]" value="1">
											</td>
											<td>Maximum Length</td>
											<td><input class="input-small" type="number" name="password_length_maximum[value]" value="<?php print $record['password_length_maximum'] ?>"></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_special_characters'); ?> name="password_special_characters[enabled]" value="1">
											</td>
											<td>Must Contain Special Characters</td>
											<td></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_numeric_digits'); ?> name="password_numeric_digits[enabled]" value="1">
											</td>
											<td>Must Contain Numeric Digits</td>
											<td></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_upper_lower_characters'); ?> name="password_upper_lower_characters[enabled]" value="1">
											</td>
											<td>Must Contain Upper and Lower Case Characters</td>
											<td></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_no_user_details'); ?> name="password_no_user_details[enabled]" value="1">
											</td>
											<td>Must Not Contain Any User Details</td>
											<td></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_expiry'); ?> name="password_expiry[enabled]" value="1">
											</td>
											<td>Password Expiry</td>
											<td><input class="input-small" type="number" name="password_expiry[value]" value="<?php print $record['password_expiry'] ?>"><span class="help-inline">Days</span></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_past_passwords'); ?> name="password_past_passwords[enabled]" value="1">
											</td>
											<td>Prevent Past Passwords</td>
											<td><input class="input-small" type="number" name="password_past_passwords[value]" value="<?php print $record['password_past_passwords'] ?>"><span class="help-inline">Past Passwords</span></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="tab-pane" id="logging">
								<table class="table table-normal">
									<thead>
										<tr>
											<td>Enabled/Disabled</td>
											<td>Name</td>
											<td>Setting</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox"  name="" value="1">
											</td>
											<td>?????</td>
											<td><input class="input-medium" type="text"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="box-content">
						
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