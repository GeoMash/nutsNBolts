$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts',
		$name:		'Image'
	}
)
(
	{},
	{
		widget:	null,
		mainApplication:null,
		init: function(mainApplication)
		{
			this.mainApplication=mainApplication;
			this.widget=$('#profilePictureSelect');
			this.mainApplication.registerAction('image.browseImage',this.browseImage.bind(this));
		},
		browseImage: function()
		{
			$application.fileManager.show
			(
				{
					buttons:
					[
						{
							label:		'Select Image',
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
			this.widget.find('.imageSelector').addClass('selected');
			this.widget.find('input').val(selected.src);
			this.widget.find('.thumbs a')	.attr('title',selected.title)
											.css('background-image',selected.thumb)
											.html('');
		}
	}
);