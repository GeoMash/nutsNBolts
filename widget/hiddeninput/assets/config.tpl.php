<?php
$options=$tpl->get('options');
?>
<div class="control-group">
	<h5>Default Value:</h5>
	<br />
	<table class="table table-hover">
		<thead>
			<th>Value</th>
		</thead>
		<tbody>
			<td>
				<input type="text" class="input-medium" value="<?php print $options[0]->value; ?>" data-prompt-position="topLeft" class="validate[required]" name="widget[<?php $tpl->widgetIndex; ?>][config][options][0][value]">
			</td>
		</tbody>
	</table>
</div>