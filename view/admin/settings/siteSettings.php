<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal fill-up validatable" method="post">
					<div class="box-header">
						<span class="title">Site Settings</span>
					</div>
					<div class="box-content">
						<div class="padded">
							<table class="table table-hover">
								<thead>
									<th class="span4">Name</th>
									<th class="span4">Value</th>
									<th>Remove</th>
								</thead>
								<tbody>
									<?php
									$settings=$tpl->get('settings');
									for ($i=0,$j=count($settings); $i<$j; $i++):
									?>
									<tr>
										<td>
											<input type="text" class="input-medium" value="<?php print $settings[$i]['key']; ?>" data-prompt-position="topLeft" class="validate[required]" name="key[]">
										</td>
										<td>
											<input type="text" class="input-medium" value="<?php print $settings[$i]['value']; ?>" data-prompt-position="topLeft" class="validate[required]" name="value[]">
										</td>
										<td>
											<button data-action="removeRow" class="btn btn-danger btn-mini" type="button">&times;</button>
										</td>
									</tr>
									<?php endfor; ?>
								</tbody>
							</table>
							<button type="button" class="btn btn-green" data-action="addNewSetting"><i class="icon-plus"></i> Add New Setting</button>
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