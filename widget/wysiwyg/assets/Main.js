$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts.widget.wysiwyg',
		$name:		'Main'
	}
)
(
	{},
	{
		init: function(id,config)
		{
			var toolbarOrder=
				[
					'basicstyles',
					'paragraph',
					'/',
					'styles',
					'colors',
					'links',
					'insert',
					'/',
					'editing',
					'clipboard',
					'tools',
				    'document'
				],
				toolbar=
				[

				];
			for (var i=0,j=toolbarOrder.length; i<j; i++)
			{
				if (toolbarOrder[i]!='/')
				{
					toolbar[i]=
					{
						name:   toolbarOrder[i],
						items:  config[toolbarOrder[i]]
					}
				}
				else
				{
					toolbar[i]='/';
				}
			}
			CKEDITOR.replace
			(
				'wysiwyg-'+id,
				{
					toolbar:toolbar
//					[
//						{ name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
//						{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
//						{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
//						'/',
//						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
//						{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'language' ] },
//						{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
//						{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
//						'/',
//						{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
//						{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
//						{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
//						{ name: 'others', items: [ '-' ] },
//					]
				}
			);
		}
	}
);