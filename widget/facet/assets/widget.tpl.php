<select data-id="<?php $tpl->dataId; ?>" name="<?php $tpl->name; ?>">
	<?php
	$options=$tpl->get('options');
	for ($i=0,$j=count($options); $i<$j; $i++)
	{
		$selected=($options[$i]->value!=$tpl->get('value'))?'':'selected';
		print '<option value="'.$options[$i]->value.'" '.$selected.'>'.$options[$i]->label.'</option>';
	}
	?>
</select>