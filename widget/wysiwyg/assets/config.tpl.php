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
	if (isset($options->{$category}) && isset($options->{$category}->{$button}) && isset($options->{$category}->{$button}->{$type}))
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
				<!--<li class="active"><a href="#document" data-toggle="tab"><i class="icon-file"></i> <span>Document</span></a></li>-->
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
				<!--<li class=""><a href="#others_--><?php //$tpl->widgetIndex; ?><!--" data-toggle="tab"><i class="icon-screenshot"></i> <span>Others</span></a></li>-->
				<!--<li class=""><a href="#about_<?php $tpl->widgetIndex; ?>" data-toggle="tab"><i class="icon-question-sign"></i> <span>About</span></a></li>-->
			</ul>
		</div>

		<div class="box-content wysiwyg-config">
			<div class="tab-content">
				<!--
				<div class="tab-pane active" id="document_<?php $tpl->widgetIndex; ?>">
					<ul class="box-list">
						<li>
							<span class="pull-left">
								<input class="iButton-icons-tab" type="checkbox" checked name="widget[<?php $tpl->widgetIndex; ?>][config][options][document][Source]">
							</span>
							<span>Source</span>
						</li>
						<li>
							<span class="pull-left">
								<input class="iButton-icons-tab" type="checkbox" checked name="widget[<?php $tpl->widgetIndex; ?>][config][options][document][Save]">
							</span>
							<span>Save</span>
						</li>
						<li>
							<span class="pull-left">
								<input class="iButton-icons-tab" type="checkbox" checked name="widget[<?php $tpl->widgetIndex; ?>][config][options][document][Preview]">
							</span>
							<span>Preview</span>
						</li>
						<li>
							<span class="pull-left">
								<input class="iButton-icons-tab" type="checkbox" checked name="widget[<?php $tpl->widgetIndex; ?>][config][options][document][Print]">
							</span>
							<span>Print</span>
						</li>
						<li>
							<span class="pull-left">
								<input class="iButton-icons-tab" type="checkbox" checked name="widget[<?php $tpl->widgetIndex; ?>][config][options][document][Templates]">
							</span>
							<span>Templates</span>
						</li>
					</ul>
				</div>iButton-icons-tab
				-->
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Cut','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Cut][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Cut','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Cut][split]">
								</td>
							</tr>
							<tr>
								<td>Copy</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Copy','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Copy][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Copy','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Copy][split]">
								</td>
							</tr>
							<tr>
								<td>Paste</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Paste','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Paste][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Paste','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Paste][split]">
								</td>
							</tr>
							<tr>
								<td>Paste Text</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','PasteText','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][PasteText][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','PasteText','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][PasteText][split]">
								</td>
							</tr>
							<tr>
								<td>Paste From Word</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','PasteFromWord','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][PasteFromWord][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','PasteFromWord','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][PasteFromWord][split]">
								</td>
							</tr>
							<tr>
								<td>Undo</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Undo','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Undo][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Undo','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Undo][split]">
								</td>
							</tr>
							<tr>
								<td>Redo</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('clipboard','Redo','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Redo][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('clipboard','Redo','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][clipboard][Redo][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','Find','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][Find][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','Find','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][Find][split]">
								</td>
							</tr>
							<tr>
								<td>Replace</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','Replace','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][Replace][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','Replace','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][Replace][split]">
								</td>
							</tr>
							<tr>
								<td>Select All</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','SelectAll','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][SelectAll][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','SelectAll','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][SelectAll][split]">
								</td>
							</tr>
							<tr>
								<td>Spellcheck As You Type</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('editing','Scayt','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][Scayt][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('editing','Scayt','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][editing][Scayt][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Bold','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Bold][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Bold','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Bold][split]">
								</td>
							</tr>
							<tr>
								<td>Italic</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Italic','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Italic][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Italic','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Italic][split]">
								</td>
							</tr>
							<tr>
								<td>Underline</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Underline','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Underline][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Underline','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Underline][split]">
								</td>
							</tr>
							<tr>
								<td>Strike</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Strike','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Strike][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Strike','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Strike][split]">
								</td>
							</tr>
							<tr>
								<td>Subscript</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Subscript','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Subscript][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Subscript','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Subscript][split]">
								</td>
							</tr>
							<tr>
								<td>Superscript</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','Superscript','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Superscript][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','Superscript','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][Superscript][split]">
								</td>
							</tr>
							<tr>
								<td>Remove Formatting</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('basicstyles','RemoveFormat','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][RemoveFormat][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('basicstyles','RemoveFormat','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][basicstyles][RemoveFormat][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Styles','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][Styles][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Styles','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][Styles][split]">
								</td>
							</tr>
							<tr>
								<td>Format</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Format','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][Format][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Format','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][Format][split]">
								</td>
							</tr>
							<tr>
								<td>Font</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Font','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][Font][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Font','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][Font][split]">
								</td>
							</tr>
							<tr>
								<td>Font Size</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','FontSize','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][FontSize][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','FontSize','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][styles][FontSize][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','NumberedList','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][NumberedList][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','NumberedList','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][NumberedList][split]">
								</td>
							</tr>
							<tr>
								<td>Bulleted List</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','BulletedList','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][BulletedList][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','BulletedList','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][BulletedList][split]">
								</td>
							</tr>
							<tr>
								<td>Outdent</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Outdent','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Outdent][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Outdent','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Outdent][split]">
								</td>
							</tr>
							<tr>
								<td>Indent</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Indent','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Indent][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Indent','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Indent][split]">
								</td>
							</tr>
							<tr>
								<td>Superscript</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Superscript','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Superscript][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Superscript','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Superscript][split]">
								</td>
							</tr>
							<tr>
								<td>Blockquote</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','Blockquote','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Blockquote][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','Blockquote','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][Blockquote][split]">
								</td>
							</tr>
							<tr>
								<td>Create Div</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','CreateDiv','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][CreateDiv][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','CreateDiv','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][CreateDiv][split]">
								</td>
							</tr>
							<tr>
								<td>Justify Left</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyLeft','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyLeft][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyLeft','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyLeft][split]">
								</td>
							</tr>
							<tr>
								<td>Justify Center</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyCenter','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyCenter][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyCenter','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyCenter][split]">
								</td>
							</tr>
							<tr>
								<td>Justify Right</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyRight','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyRight][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyRight','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyRight][split]">
								</td>
							</tr>
							<tr>
								<td>Justify Block</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','JustifyBlock','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyBlock][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','JustifyBlock','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][JustifyBlock][split]">
								</td>
							</tr>
							<tr>
								<td>Bidirectional Left-to-Right</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','BidiLtr','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][BidiLtr][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','BidiLtr','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][BidiLtr][split]">
								</td>
							</tr>
							<tr>
								<td>Bidirectional Right-to-Left</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','BidiRtl','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][BidiRtl][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','BidiRtl','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][BidiRtl][split]">
								</td>
							</tr>
							<tr>
								<td>Language</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('paragraph','language','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][language][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('paragraph','language','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][paragraph][language][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('links','Link','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][links][Link][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('links','Link','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][links][Link][split]">
								</td>
							</tr>
							<tr>
								<td>Unlink</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('links','Unlink','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][links][Unlink][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('links','Unlink','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][links][Unlink][split]">
								</td>
							</tr>
							<tr>
								<td>Anchor</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('links','Anchor','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][links][Anchor][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('links','Anchor','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][links][Anchor][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Image','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Image][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Image','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Image][split]">
								</td>
							</tr>
							<tr>
								<td>Flash</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Flash','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Flash][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked(insert,'Flash','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Flash][split]">
								</td>
							</tr>
							<tr>
								<td>Table</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Table','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Table][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Table','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Table][split]">
								</td>
							</tr>
							<tr>
								<td>Horizontal Rule</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','HorizontalRule','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][HorizontalRule][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','HorizontalRule','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][HorizontalRule][split]">
								</td>
							</tr>
							<tr>
								<td>Smiley</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Smiley','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Smiley][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Smiley','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Smiley][split]">
								</td>
							</tr>
							<tr>
								<td>Special Character</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','SpecialChar','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][SpecialChar][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','SpecialChar','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][SpecialChar][split]">
								</td>
							</tr>
							<tr>
								<td>Page Break</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','PageBreak','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][PageBreak][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','PageBreak','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][PageBreak][split]">
								</td>
							</tr>
							<tr>
								<td>Iframe</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','Iframe','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Iframe][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','Iframe','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][Iframe][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('colors','TextColor','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][colors][options][colors][TextColor][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('colors','TextColor','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][colors][TextColor][split]">
								</td>
							</tr>
							<tr>
								<td>Background Color</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('insert','BGColor','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][BGColor][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('insert','BGColor','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][insert][BGColor][split]">
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
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('tools','Iframe','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][tools][Maximize][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('tools','Iframe','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][tools][Maximize][split]">
								</td>
							</tr>
							<tr>
								<td>Show Blocks</td>
								<td>
									<input class="iButton-icons-tab" type="checkbox" <?php $parseChecked('tools','Iframe','enabled'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][tools][ShowBlocks][enabled]">
								</td>
								<td>
									<input type="checkbox" class="icheck" <?php $parseChecked('tools','Iframe','split'); ?> name="widget[<?php $tpl->widgetIndex; ?>][config][options][tools][ShowBlocks][split]">
								</td>
							</tr>
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