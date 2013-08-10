<?php
$config		=$tpl->get('config');
?>
<div class="control-group">
	<h5>Options:</h5>
	<br />
	<table class="table table-hover">
		<thead>
			<th>Href</th>
			<th>List Item</th>
			<th>Remove</th>
		</thead>
		<tbody>
			<?php
			if ($tpl->get('options')):
				$options=$tpl->get('options');
				for ($i=0,$j=count($options); $i<$j; $i++):
			?>
					<tr>
						<td>
							<input type="text" class="input-medium" value="<?php print $options[$i]->href; ?>" data-prompt-position="topLeft" class="validate[required]" name="widget[<?php $tpl->widgetIndex; ?>][config][options][<?php print $i; ?>][href]">
						</td>
						<td>
							<input type="text" class="input-medium" value="<?php print $options[$i]->li; ?>" data-prompt-position="topLeft" class="validate[required]" name="widget[<?php $tpl->widgetIndex; ?>][config][options][<?php print $i; ?>][li]">
						</td>
						<td>
							<button data-action="widget.listitem.config.removeOption" class="btn btn-danger btn-mini" type="button">&times;</button>
						</td>
					</tr>
			<?php
				 endfor;
			 endif;
			?>
		</tbody>
	</table>
	<button data-action="widget.listitem.config.addOption" class="btn btn-green" type="button"><i class="icon-plus"></i> Add Option</button>
</div>