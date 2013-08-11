<div id="fileManagerWindow" class="modal bigmodal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">File Manager</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<div id="collections" class="span2">
				<?php $tpl->loadView('admin/fileManager/collections'); ?>
			</div>
			<div class="span10">
				<div id="filesWrapper" class="carousel slide" data-interval="0">
					<div class="carousel-inner">
						<div id="files" class="active item">
							<div></div>
						</div>
						<div id="uploader" class="item">
							<?php $tpl->loadView('admin/fileManager/uploader'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-success pull-left" data-action="showUploadFiles"><i class="icon-plus"></i>&nbsp;Add Files</button>
		<button class="btn btn-success pull-left" data-action="showFileList" style="display:none;"><i class="icon-arrow-left"></i>&nbsp;Back to File List</button>
		<button class="btn btn-primary" data-dismiss="modal">Close</button>
	</div>
</div>