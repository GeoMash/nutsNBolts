$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts.widget.select',
		$name:		'Config'
	}
)
(
	{},
	{
		init: function()
		{
			$application.registerAction('widget.select.config.addOption',	this.addOption.bind(this))
						.registerAction('widget.select.config.removeOption',this.removeOption.bind(this));
		},
		addOption: function(btn)
		{
			var	widgetIndex	=btn.parents('div[data-index]').data('index'),
				optionIndex	=btn.siblings('table').find('tbody tr').length;
			btn.siblings('table').find('tbody').append
			(
				[
					'<tr>',
						'<td>',
							'<input type="text" class="input-medium" value="" data-prompt-position="topLeft" class="validate[required]" name="widget['+widgetIndex+'][config][options]['+optionIndex+'][label]">',
						'</td>',
						'<td>',
							'<input type="text" class="input-medium" value="" data-prompt-position="topLeft" class="validate[required]" name="widget['+widgetIndex+'][config][options]['+optionIndex+'][value]">',
						'</td>',
						'<td>',
							'<button data-action="widget.select.config.removeOption" class="btn btn-danger btn-mini" type="button">&times;</button>',
						'</td>',
					'</tr>'
				].join('')
			);
		},
		removeOption: function(btn)
		{
			var table=btn.parents('table');
			btn.parents('tr').remove();
			this.reindex(table);
		},
		reindex: function(table)
		{
			var widgetIndex=table.parents('[data-index]').data('index');
			table.find('tr:not(:first)').each
			(
				function(optionIndex,tr)
				{
					$(tr).find('input').each
					(
						function(i,input)
						{
							if ($(input).attr('name').indexOf('label')!==-1)
							{
								$(input).attr('name','widget['+widgetIndex+'][config][options]['+optionIndex+'][label]');
							}
							else
							{
								$(input).attr('name','widget['+widgetIndex+'][config][options]['+optionIndex+'][value]');
							}
						}
					);
				}
			);
		}
	}
);