<ul name="<?php $tpl->name; ?>">
	<?php
	$options=$tpl->get('options');
	for ($i=0,$j=count($options); $i<$j; $i++)
	{
		print '<li><a href="'.$options[$i]->href.'">'.$options[$i]->li.'</a></li>';
	}
	?>
</ul>