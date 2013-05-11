<div class="well relative">
	<input type="hidden" name="part_id[]" value="<?php $tpl->id ?>">
	<a href="javascript:{};" data-action="removeWidgetRow"><span class="triangle-button red"><i class="icon-remove"></i></span></a>
	<div class="control-group">
		<label class="control-label">Label</label>
		<div class="controls">
			<input type="text" name="label[]" class="validate[required]" data-prompt-position="topLeft"value="<?php $tpl->label ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Type</label>
		<div class="controls">
			<select class="chzn-select" name="content_widget_id[]">
				<?php $tpl->widgetTypes; ?>
			</select>
		</div>
	</div>
</div>