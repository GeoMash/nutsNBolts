$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts.widget.markdown',
		$name:		'Main'
	}
)
(
	{},
	{
		converter:	null,
		editor:		null,
		init:		function(id,config)
		{
			this.converter	=Markdown.getSanitizingConverter();
			this.editor		=new Markdown.Editor(this.converter,id);
			this.editor.run();
			$('#'+id+'-input').autosize();
		}
	}
);