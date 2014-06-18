<?php
$options=$tpl->get('options');
$parseChecked=function($category,$button,$type) use($options)
{
	if (is_null($options))
	{
		if ($type=='enabled')
		{
			print 'checked';
		}
		print '';
	}
	if (isset($options->{$category}) && in_array($button,$options->{$category}))
	{
		print 'checked';
	}
	print '';
};
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
												<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('password','length_minimum','enabled'); ?> name="length_minimum[enabled]" value="1">
											</td>
											<td>Minimum Length</td>
											<td><input class="input-medium-large" type="number" name="length_minimum[value]"></td>
										</tr>
										<tr>
											<td>
												<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('password','length_maximum','enabled'); ?> name="length_maximum[enabled]" value="1">
											</td>
											<td>Maximum Length</td>
											<td><input class="input-medium-large" type="number" name="length_maximum[value]"></td>
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
												<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('logging','','enabled'); ?> name="" value="1">
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