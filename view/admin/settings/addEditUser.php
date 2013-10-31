<div class="container-fluid padded">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<form class="form-horizontal fill-up validatable" method="post">
						<input type="hidden" name="id" value="<?php $tpl->id; ?>">
						<div class="box-header">
							<span class="title">User</span>
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
							<div class="padded">
								<div class="control-group">
									<label class="control-label">Email Address</label>
									<div class="controls">
										<input type="text" name="email" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->email; ?>" autocomplete="off">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Password</label>
									<div class="controls">
										<input type="password" name="password" autocomplete="off">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Confirm Password</label>
									<div class="controls">
										<input type="password" name="password_confirm" autocomplete="off">
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
							
							<div class="container-fluid padded">
								<div class="box">
									<div class="box-header">
										<span class="title"><i class="icon-beaker"></i> Bars</span>
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
													<?php $tpl->generateBarList(); ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>							
							
				<?php
				$navButtons=$tpl->get('extraOptions');
				foreach ($navButtons as $navButton)
				{
					print $navButton;
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
</div>