<?php
$files=$tpl->get('files');
?>
<div class="box">
	<div class="box-header">
		<span class="title"><i class="icon-th-list"></i> <?php $tpl->collectionName; ?> Files</span>
	</div>
	<div class="box-content padded">
		<div class="thumbs">
			<?php
			for ($i=0,$j=count($files); $i<$j; $i++)
			{
				switch ($files[$i]['extension'])
				{
					case 'png':
					case 'jpg':
					case 'gif':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-image:url(\''.$files[$i]['thumbPath'].'\')" title="'.$files[$i]['name'].'"></a>';
					}
				}
			}
			?>
			<!--
			<a href="#" title="Some Movie.mov"><i class="icon-film icon"></i></a>
			<a href="" style="background-image:url(http://farm8.staticflickr.com/7013/6754656011_3de2cc73a2_m.jpg)" title="Lion Rock"></a>
			-->
		</div>
	</div>
</div>