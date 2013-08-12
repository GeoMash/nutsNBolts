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
		init: function()
		{
			$('#wysiwygEditor').wysihtml5();
		}
	}
);