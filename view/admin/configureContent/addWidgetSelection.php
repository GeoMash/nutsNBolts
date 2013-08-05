<div data-index="<?php $tpl->widgetIndex; ?>" class="well relative">
	<input type="hidden" name="part_id[]" value="<?php $tpl->id ?>">
	<a href="javascript:{};" data-action="removeWidgetRow"><span class="triangle-button red"><i class="icon-remove"></i></span></a>
	<div class="control-group">
		<label class="control-label">Label</label>
		<div class="controls">
			<input type="text" name="label[]" class="validate[required]" data-prompt-position="topLeft" value="<?php $tpl->label ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Type</label>
		<div class="controls">
			<select data-action="getWidgetOptions" class="chzn-select" name="widget[<?php $tpl->widgetIndex; ?>][namespace]">
				<?php $tpl->widgetTypes; ?>
			</select>
		</div>
	</div>
	<div class="container-fluid padded">
		<div class="box">
			<div class="box-header">
				<span class="title"><i class="icon-beaker"></i> Config</span>
			</div>
			<div class="content-box">
				<div class="padded">
					<div class="well relative">
						<?php $tpl->options; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>