<select name="<?php $tpl->name; ?>">
	<?php
	$options=$tpl->get('config')->options;
	for ($i=0,$j=count($options); $i<$j; $i++)
	{
		$selectd=($options[$i]->value!=$tpl->get('value'))?'':'selected';
		print '<option value="'.$options[$i]->value.'" '.$selected.'>'.$options[$i]->label.'</option>';
	}
	?>
</select>