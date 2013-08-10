<?php
$config=$tpl->get('config');
?>
<div class="control-group">
	<label class="control-label">Upload Image</label>
	<div class="controls">
		<input type="input" class="span2 input-medium" value="" data-prompt-position="topLeft" class="validate[required]" name="widget[<?php $tpl->widgetIndex; ?>][config][src]">
		<button class="btn" type="button">Go!</button>
	</div>
</div>
<div class="progress progress-striped progress-blue active">
	<div class="bar tip" title="" data-percent="0" style="width: 0%;" data-original-title="0%"></div>
</div>