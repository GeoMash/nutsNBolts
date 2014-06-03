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
				switch (strtolower($files[$i]['extension']))
				{
					case 'ico':
					case 'jpeg':
					case 'png':
					case 'jpg':
					case 'gif':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-image:url(\''.$files[$i]['thumbPath'].'\')" title="'.$files[$i]['name'].'"></a>';
					}
					break;
					
					case 'pdf':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-layout.png\')" title="'.$files[$i]['name'].'"></a>';
					}
					break;	
					
					case 'txt':
					case 'odf':
					case 'doc':
					case 'docx':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-text.png\')" title="'.$files[$i]['name'].'"></a>';
					}
					break;
					
					case 'xlsx':
					case 'xls':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-spreadsheet.png\')" title="'.$files[$i]['name'].'"></a>';
					}
					break;
					
					case 'ppt':
					case 'pps':
					case 'pptx':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-powerpoint.png\')" title="'.$files[$i]['name'].'"></a>';
					}
					break;
					
					case 'mp3':
					case 'ogg':
					case 'wav':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-recording.png\')" title="'.$files[$i]['name'].'"></a>'; 
					}
					break;
						
					case 'avi':
					case 'mp4':
					case 'mkv':
					case 'mov':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-movie-1.png\')" title="'.$files[$i]['name'].'"></a>'; 
					}
					break;
					
					case 'html':
					case 'xhtml':
					case 'htm':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-html.png\')" title="'.$files[$i]['name'].'"></a>'; 
					}
					break;
					
					case 'rar':
					case 'zip':
					case 'tar':
					{	
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-archive.png\')" title="'.$files[$i]['name'].'"></a>';
					}
					break;
					
					case 'css':
					case 'less':
					case 'js':
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-css.png\')" title="'.$files[$i]['name'].'"></a>';
					}
					break;	
					
					default:
					{
						print '<a href="'.$files[$i]['publicPath'].'" style="background-size:initial;background-image:url(\'/admin/images/filemanager/filetype-blank.png\')" title="'.$files[$i]['name'].'"></a>';
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