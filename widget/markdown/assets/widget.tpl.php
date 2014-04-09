<div class="row-fluid">
	<div class="span6">
		<div class="box">
			<div class="box-header">
				<span class="title"><i class="icon-pencil"></i> Markdown Editor</span>
			</div>
			<div class="box-content padded">
				<div id="<?php $tpl->dataId; ?>-button-bar"></div>
				<textarea id="<?php $tpl->dataId; ?>-input" class="wmd-input" data-id="<?php $tpl->dataId; ?>" name="<?php $tpl->name; ?>"><?php $tpl->value; ?></textarea>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="box">
			<div class="box-header">
				<span class="title"><i class="icon-search"></i> Preview</span>
			</div>
			<div id="<?php $tpl->dataId; ?>-preview" class="box-content padded"></div>
		</div>
	</div>
</div>