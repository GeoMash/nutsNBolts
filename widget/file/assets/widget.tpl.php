<?php
$hasValue	=(bool)$tpl->get('value');
if ($hasValue)
{
	$parts		=explode('/',$tpl->get('value'));
	$name		=array_pop($parts);
	$thumb		=implode('/',$parts).'/_thumbs/120x120/'.$name;
}
?>
<div id="file-<?php $tpl->dataId; ?>" data-id="<?php $tpl->dataId; ?>" class="box span2">
	<input type="hidden" name="<?php $tpl->name; ?>" value="<?php $tpl->value; ?>">
	<div class="box-header">
		<span class="title"><a href="javascript:{}" data-action="widget.file.main.browseFile" title="File Not Selected"><i class="icon-picture"></i> Select File</a></span>
	</div>
	<div class="box-content padded imageSelector <?php print $hasValue?'selected':''; ?>">
		<div class="thumbs">
			<?php
			if ($hasValue):
			?>
			<a href="javascript:{}" data-action="widget.file.main.browseFile" title="<?php print $name; ?>" style="background-image:url('<?php print $thumb; ?>')"></a>
			<?php else: ?>
			<a href="javascript:{}" data-action="widget.file.main.browseFile" title="File  Not Selected"><i class="icon-folder-open icon"></i></a>
			<?php
			endif;
			?>
		</div>
	</div>
</div>