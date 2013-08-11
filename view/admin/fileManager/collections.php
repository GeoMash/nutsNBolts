<?php
$collections=$tpl->get('collections');
?>
<ul class="nav nav-tabs nav-stacked">
	<?php
	for ($i=0,$j=count($collections); $i<$j; $i++):
	?>
	<li>
		<a href="javascript:{}" data-action="openCollection" data-id="<?php print $collections[$i]['id']; ?>">
			<i class="icon-folder-close icon-2x"></i>
			<i class="icon-chevron-right"></i>
			<span><?php print $collections[$i]['name']; ?></span>
		</a>
	</li>
	<?php endfor; ?>
</ul>