<?php $userDetails=$tpl->get("userDetails")[0]; ?>
<div class="container-fluid padded">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<form class="form-horizontal fill-up validatable" method="post">
					<input type="hidden" value="<?php echo $userDetails['id']; ?>" name="id"/>
					<input type="hidden" value="<?php echo $userDetails['status']; ?>" name="status"/>
					<div class="box-header">
						<span class="title">Profile</span>
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
								<label class="control-label">First Name</label>
								<div class="controls">
									<input type="text" value="<?php echo (isset($userDetails['name_first'])) ? $userDetails['name_first']: ''; ?>" class="validate[required]" data-prompt-position="topLeft" name="name_first"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Last Name</label>
								<div class="controls">
									<input type="text" value="<?php echo (isset($userDetails['name_last'])) ? $userDetails['name_last']: ''; ?>" class="validate[required]" data-prompt-position="topLeft" name="name_last"/>
								</div>	
							</div>
							<div class="control-group">	
								<label class="control-label">Position</label>
								<div class="controls">
									<input type="text" value="<?php echo (isset($userDetails['position'])) ? $userDetails['position']: ''; ?>" class="" data-prompt-position="topLeft" name="position"/>
								</div>
							</div>												
							<div class="control-group">	
								<label class="control-label">Company</label>
								<div class="controls">
									<input type="text" value="<?php echo (isset($userDetails['company'])) ? $userDetails['company']: ''; ?>" class="" data-prompt-position="topLeft" name="company"/>
								</div>
							</div>	
							
							<div class="control-group">	
								<label class="control-label">About</label>
								<div class="controls">
									<textarea name="about"><?php echo (isset($userDetails['about'])) ? $userDetails['about']: ''; ?></textarea>
								</div>
							</div>	
							
							<div class="control-group">	
								<label class="control-label">Telephone Number</label>
								<div class="controls">
									<input type="text" value="<?php echo (isset($userDetails['phone'])) ? $userDetails['phone']: ''; ?>" data-prompt-position="topLeft" name="phone"/>
								</div>
							</div>
							<?php
							$hasValue	=($userDetails['image']);
							if ($hasValue)
							{
								$parts		=explode('/',$userDetails['image']);
								$name		=array_pop($parts);
								$thumb		=implode('/',$parts).'/_thumbs/120x120/'.$name;
							}
							?>								
							<div class="control-group" id="profilePictureSelect">	
								<label class="control-label">Profile Picture</label>
								<div class="controls">
									<div class="box span2 ">
										<input type="hidden" name="image" value="<?php echo $userDetails['image']; ?>">
										<div class="box-header">
											<span class="title"><a href="javascript:{}" data-action="image.browseImage" title="Image Not Selected"><i class="icon-picture"></i> Select Image</a></span>
										</div>
										<div class="box-content padded imageSelector <?php print $hasValue?'selected':''; ?>">
											<div class="thumbs">
												<?php
												if ($hasValue):
												?>
												<a href="javascript:{}" data-action="image.browseImage" title="<?php print $name; ?>" style="background-image:url('<?php print $thumb; ?>')"></a>
												<?php else: ?>
												<a href="javascript:{}" data-action="image.browseImage" title="Image Not Selected"><i class="icon-folder-open icon"></i></a>
												<?php
												endif;
												?>
											</div>
										</div>
									</div>										
								</div>
							</div>
						</div>
						
						<div class="container-fluid padded">
							<div class="box">
								<div class="box-header">
									<span class="title"><i class="icon-key"></i> Change Password</span>
								</div>
								<div class="content-box">
									<div class="padded">
										<?php if (!$tpl->getPolicy('password','force_random')): ?>
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
										<?php else: ?>
										<div class="control-group">
											<label class="control-label">New Random Password</label>
											<div class="controls">
												<input type="checkbox" name="set_random" value="1"><span class="help-inline">(Your new password will be emailed to you.)</span>
											</div>
										</div>
										<?php endif; ?>
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
								<button class="btn btn-blue">Save Changes</button>
								<button class="btn btn-red" type="button">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>