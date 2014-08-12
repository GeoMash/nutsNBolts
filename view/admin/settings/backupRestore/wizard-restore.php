<div class="wizard" id="wizard-restore" data-title="Restore Data">
    <div class="wizard-card" data-cardname="type">
        <h3>Restoration Type</h3>
        <p>Select what data you are restoring.</p>
		<select name="dataType" data-placeholder="Data Type" style="width:350px;" class="chzn-select create-server-service-list form-control">
			<option selected value="full">Full Restoration</option>
			<option value="users">Users</option>
			<option value="contentTypes">Content Types</option>
			<option value="nodes">Nodes</option>
		</select>
    </div>
    <div class="wizard-card" data-cardname="file">
        <h3>Select File</h3>
        <p>Select a file to restore data from.</p>
		<div class="uploader"><input type="file" name="file" id="restoreUploadButton"><span class="filename">No file selected</span><span class="action">+</span></div>
    </div>
    <div class="wizard-success">
		<div class="alert alert-success">
			<span class="create-server-name"></span>Server Created <strong>Successfully.</strong>
		</div>
		<a class="btn btn-default create-another-server">Restore more data</a>
		<span style="padding:0 10px">or</span>
		<a class="btn btn-success im-done">Done</a>
	</div>
</div>