<div class="control-group">
	<label class="control-label">Facet Name</label>
	<div class="controls">
		<div class="row-fluid">
			<div>
				<input type="text" class="input-small" name="widget[<?php $tpl->widgetIndex; ?>][config][facet]" value="<?php echo $tpl->get('facet');?>">
			</div>
		</div>
	</div>
</div>
<div class="control-group">
	<h5>Options:</h5>
	<br />
	<table class="table table-hover">
		<thead>
			<th>Label</th>
			<th>Value</th>
			<th>Delete</th>
		</thead>
		<tbody>
			<?php
			if ($tpl->get('options')):
				$options=$tpl->get('options');
				for ($i=0,$j=count($options); $i<$j; $i++):
			?>
					<tr>
						<td>
							<input type="text" class="input-medium" value="<?php print $options[$i]->label; ?>" data-prompt-position="topLeft" class="validate[required]" name="widget[<?php $tpl->widgetIndex; ?>][config][options][<?php print $i; ?>][label]">
						</td>
						<td>
							<input type="text" class="input-medium" value="<?php print $options[$i]->value; ?>" data-prompt-position="topLeft" class="validate[required]" name="widget[<?php $tpl->widgetIndex; ?>][config][options][<?php print $i; ?>][value]">
						</td>
						<td>
							<button data-action="widget.select.config.removeOption" class="btn btn-danger btn-mini" type="button">&times;</button>
						</td>
					</tr>
			<?php
				 endfor;
			 endif;
			?>
		</tbody>
	</table>
	<button data-action="widget.select.config.addOption" class="btn btn-green" type="button"><i class="icon-plus"></i> Add Option</button>
</div>