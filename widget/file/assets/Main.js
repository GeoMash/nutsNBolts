$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts.widget.file',
		$name:		'Main'
	}
)
(
	{},
	{
		widget:	null,
		init: function(id)
		{
			this.widget=$('#file-'+id);
			$application.registerAction('widget.file.main.browseFile',this.browseFile.bind(this))
		},
		browseFile: function()
		{
			$application.fileManager.show
			(
				{
					buttons:
					[
						{
							label:		'Select File',
							icon:		'icon-ok',
							handler:	this.onSelect.bind(this)
						}
					]
				}
			);
		},
		onSelect: function(fileManager)
		{
			fileManager.hide();
			var selected=fileManager.getSelected();
			console.log(selected);
			this.widget.find('.imageSelector').addClass('selected');
			console.log(this.widget.find('.imageSelector'));
			this.widget.find('input').val(selected.src);
			this.widget.find('.thumbs a')	.attr('title',selected.title)
											.css('background-image',selected.thumb)
											.html('');
		}
	}
);