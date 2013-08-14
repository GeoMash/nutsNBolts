<div id="fileManagerWindow" class="modal bigmodal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">File Manager</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid fill">
			<div id="collections" class="span2">
				<?php $tpl->loadView('admin/fileManager/collections'); ?>
			</div>
			<div class="span10 fill">
				<div class="row-fluid">
					<div id="filesWrapper" class="carousel slide fill" data-interval="0">
						<div class="carousel-inner fill">
							<div id="files" class="active item fill">
								
							</div>
							<div id="uploader" class="item">
								<?php $tpl->loadView('admin/fileManager/uploader'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<form class="form-horizontal fill-up" method="post">
						<div class="control-group">
							<label class="control-label">Selected File(s)</label>
							<div class="controls">
								<input type="text" name="selectedFiles" readonly>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-red pull-left" data-dismiss="modal">Close</button>
		<button class="btn btn-success pull-left" data-action="showUploadFiles"><i class="icon-plus"></i>&nbsp;Add Files</button>
		<button class="btn btn-success pull-left" data-action="showFileList" style="display:none;"><i class="icon-arrow-left"></i>&nbsp;Back to File List</button>
		<div class="extraButtons"></div>
	</div>
</div>