<?php
$options=$tpl->get('options');
$parseChecked=function($category,$button,$type) use($options)
{
	if (is_null($options))
	{
		if ($type=='enabled')
		{
			print 'checked';
		}
		print '';
	}
	if (isset($options->{$category}) && in_array($button,$options->{$category}))
	{
		print 'checked';
	}
	print '';
};
?>
<div class="control-group">
	<h5>Buttons:</h5>
	<br />
	<div class="box">
		<div class="box-header">
			<ul class="nav nav-tabs nav-tabs-left">
				<!--<li class=""><a href="#forms" data-toggle="tab"><i class="icon-check"></i> <span>Forms</span></a></li>-->
				<li class="active"><a href="#clipboard_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-copy"></i> <span>Clipboard</span></a></li>
				<li class=""><a href="#editing_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-edit"></i> <span>Editing</span></a></li>
				<li class=""><a href="#basicstyles_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-font"></i> <span>Basic Styles</span></a></li>
				<li class=""><a href="#styles_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-bold"></i> <span>Other Styles</span></a></li>
				<li class=""><a href="#paragraph_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-align-left"></i> <span>Paragraph</span></a></li>
				<li class=""><a href="#links_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-link"></i> <span>Links</span></a></li>
				<li class=""><a href="#insert_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-circle-arrow-down"></i> <span>Insert</span></a></li>
				<li class=""><a href="#colors_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-tint"></i> <span>Colors</span></a></li>
				<li class=""><a href="#tools_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-cog"></i> <span>Tools</span></a></li>
				<li class=""><a href="#document_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-file"></i> <span>Document</span></a></li>
				<!--<li class=""><a href="#others_--><?php //$tpl->widgetIndex; ?><!--" data-toggle="tab"><i class="icon-screenshot"></i> <span>Others</span></a></li>-->
				<!--<li class=""><a href="#about_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-question-sign"></i> <span>About</span></a></li>-->
			</ul>
		</div>

		<div class="box-content wysiwyg-config">
			<div class="tab-content">
				<div class="tab-pane active" id="clipboard_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Cut</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Cut','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="Cut">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Cut','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Copy</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Copy','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="Copy">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Copy','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Paste</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Paste','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="Paste">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Paste','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Paste Text</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','PasteText','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="PasteText">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','PasteText','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Paste From Word</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','PasteFromWord','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="PasteFromWord">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','PasteFromWord','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Undo</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Undo','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="Undo">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Undo','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Redo</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Redo','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="Redo">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Redo','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][clipboard][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="editing_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Find</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','Find','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][]" value="Find">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','Find','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][Find][split]" value="-">
								</td>
							</tr>
							<tr>
								<td>Replace</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','Replace','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][]" value="Replace">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','Replace','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Select All</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','SelectAll','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][]" value="SelectAll">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','SelectAll','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Spellcheck As You Type</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','Scayt','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][]" value="Scayt">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','Scayt','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][editing][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="basicstyles_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Bold</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Bold','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="Bold">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Bold','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Italic</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Italic','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="Italic">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Italic','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Underline</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Underline','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="Underline">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Underline','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Strike</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Strike','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="Strike">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Strike','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Subscript</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Subscript','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="Subscript">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Subscript','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Superscript</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Superscript','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="Superscript">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Superscript','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Remove Formatting</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','RemoveFormat','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="RemoveFormat">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','RemoveFormat','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][basicstyles][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="styles_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Styles</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Styles','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="Styles">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Styles','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Format</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Format','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="Format">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Format','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Font</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Font','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="Font">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Font','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Font Size</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','FontSize','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="FontSize">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','FontSize','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][styles][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="paragraph_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Numbered List</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','NumberedList','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="NumberedList">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','NumberedList','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Bulleted List</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','BulletedList','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="BulletedList">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','BulletedList','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Outdent</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Outdent','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="Outdent">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Outdent','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Indent</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Indent','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="Indent">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Indent','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Superscript</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Superscript','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="Superscript">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Superscript','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Blockquote</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Blockquote','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="Blockquote">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Blockquote','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Create Div</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','CreateDiv','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="CreateDiv">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','CreateDiv','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Justify Left</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyLeft','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="JustifyLeft">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyLeft','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Justify Center</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyCenter','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="JustifyCenter">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyCenter','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Justify Right</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyRight','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="JustifyRight">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyRight','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Justify Block</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyBlock','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="JustifyBlock">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyBlock','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Bidirectional Left-to-Right</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','BidiLtr','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="BidiLtr">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','BidiLtr','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Bidirectional Right-to-Left</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','BidiRtl','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="BidiRtl">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','BidiRtl','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Language</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','language','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="language">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','language','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][paragraph][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="links_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Link</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('links','Link','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][links][]" value="Link">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('links','Link','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][links][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Unlink</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('links','Unlink','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][links][]" value="Unlink">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('links','Unlink','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][links][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Anchor</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('links','Anchor','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][links][]" value="Anchor">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('links','Anchor','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][links][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="insert_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Image</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Image','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="Image">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Image','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Flash</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Flash','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="Flash">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked(insert,'Flash','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Table</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Table','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="Table">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Table','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert]" value="-">
								</td>
							</tr>
							<tr>
								<td>Horizontal Rule</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','HorizontalRule','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="HorizontalRule">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','HorizontalRule','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Smiley</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Smiley','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="Smiley">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Smiley','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Special Character</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','SpecialChar','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="SpecialChar">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','SpecialChar','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Page Break</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','PageBreak','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="PageBreak">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','PageBreak','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Iframe</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Iframe','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="Iframe">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Iframe','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][insert][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="colors_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Text Color</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('colors','TextColor','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][colors][options][colors][]" value="TextColor">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('colors','TextColor','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][colors][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Background Color</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('colors','BGColor','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][colors][]" value="BGColor">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('colors','BGColor','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][colors][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tools_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Maximize</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('tools','Maximize','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][tools][]" value="Maximize">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('tools','Maximize','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][tools][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Show Blocks</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('tools','ShowBlocks','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][tools][]" value="ShowBlocks">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('tools','ShowBlocks','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][tools][]" value="-">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="document_<?php $tpl->widgetIndex; ?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Source</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('document','Source','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="Source">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('document','Source','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="-">
								</td>
							</tr>
							<!--
							<tr>
								<td>Save</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('document','Save','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="Save">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('document','Save','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Preview</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('document','Preview','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="Preview">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('document','Preview','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Print</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('document','Print','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="Print">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('document','Print','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="-">
								</td>
							</tr>
							<tr>
								<td>Templates</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('document','Templates','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="Templates">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('document','Templates','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][document][]" value="-">
								</td>
							</tr>
							-->
						</tbody>
					</table>
				</div>
				<!--
				<div class="tab-pane" id="others_<?php /*$tpl->widgetIndex; */?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				-->
				<!--
				<div class="tab-pane" id="about_<?php /*$tpl->widgetIndex; */?>">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Button</td>
								<td>Enabled/Disabled</td>
								<td>Add Split</td>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				-->
			</div>
		</div>
    </div>
</div>