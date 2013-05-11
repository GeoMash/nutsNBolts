$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts',
		$name:		'Application'
	}
)
(
	{},
	{
		init: function()
		{
			$('.tip, [rel=tooltip]').tooltip({gravity: 'n', fade: true, html:true})
			$("form.validatable").validationEngine({promptPosition:'topLeft'});
			
			$(".iButton-icons").iButton
			(
				{
					labelOn:		"<i class='icon-ok'></i>",
					labelOff:		"<i class='icon-remove'></i>",
					handleWidth:	30
				}
			);

			$(".iButton-enabled").iButton
			(
				{
					labelOn:		"ENABLED",
					labelOff:		"DISABLED",
					handleWidth:	30
				}
			);
 			$(".iButton").iButton();
 			$('.chzn-select:not(.select2-container)').select2();
 			
 			this.bindActions();
		},
		bindActions: function()
		{
			$('body').on
			(
			 	'click',
			 	'[data-action]',
			 	null,
				function onActionClick(event)
				{
					var	btn		=$(event.currentTarget),
						action	=btn.data('action');
					if (Object.isFunction(this[action]))
					{
						this[action](btn);
					}
				}.bind(this)
			);
		},
		addWidgetSelectionRow: function(btn)
		{
			$.get
			(
				'/admin/configurecontent/getAddContentTypeWidgetOptions',
				function onGetAddContentTypeWidgetResponse(response)
				{
					var options=$(response),
						HTML=$
						(
							[
							'<div class="well relative">',
								'<a href="javascript:{};" data-action="removeWidgetRow"><span class="triangle-button red"><i class="icon-remove"></i></span></a>',
								'<div class="control-group">',
									'<label class="control-label">Label</label>',
									'<div class="controls">',
										'<input type="text" name="widget_label[]" class="validate[required]" data-prompt-position="topLeft">',
									'</div>',
								'</div>',
								'<div class="control-group">',
									'<label class="control-label">Type</label>',
									'<div class="controls">',
										'<select class="chzn-select" name="widget_type[]">',
										'</select>',
									'</div>',
								'</div>',
							'</div>'
							].join('')
						);
					HTML.find('select').append(options);
					btn.parent().find('button').before(HTML);
					$('.chzn-select:not(.select2-container)').select2();
				}.bind(this)
			)
		},
		removeWidgetRow: function(btn)
		{console.debug('removeWidgetRow');
			btn.parents('.well.relative').remove();
		}
	}
);