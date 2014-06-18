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
											<td><input class="input-medium-large" type="number" name="password_length_minimum[value]" value="<?php print $record['password_length_minimum'] ?>"></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $tpl->isChecked('password_length_maximum'); ?> name="password_length_maximum[enabled]" value="1">
											</td>
											<td>Maximum Length</td>
											<td><input class="input-medium-large" type="number" name="password_length_maximum[value]" value="<?php print $record['password_length_maximum'] ?>"></td>
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