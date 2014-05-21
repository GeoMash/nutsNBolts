$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts',
		$name:		'Application',
		$requires:
		[
			'nutsnbolts.FileManager'
		]
	}
)
(
	{},
	{
		actionRegistry:	{},
		fileManager:	null,
		spinner:		null,
		init: function()
		{
			this.fileManager=new nutsnbolts.FileManager();
			this.initAll();
			this.bindActions();
		},
		initAll: function()
		{
			$('.tip, [rel=tooltip]').tooltip({gravity: 'n', fade: true, html:true})
			$("form.validatable").validationEngine({promptPosition:'topLeft'});
			
			$('.tags').tagsInput({width: 'auto'});
			
			$('.icheck').iCheck
			(
				{
					checkboxClass:	'icheckbox_flat-aero',
					radioClass:		'iradio_flat-aero'
				}
			);
			$(".iButton-icons").iButton
			(
				{
					labelOn:		"<i class='icon-ok'></i>",
					labelOff:		"<i class='icon-remove'></i>",
					handleWidth:	30
				}
			);
			$(".iButton-icons-tab").each
			(
				function()
				{
					if ($(this).is(":visible"))
					{
						return $(this).iButton
						(
							{
								labelOn:        "<i class='icon-ok'></i>",
								labelOff:       "<i class='icon-remove'></i>",
								handleWidth:    30
							}
						);
					}
				}
			);
			$('[data-toggle="tab"]').on
			(
				'shown',
				function(e)
				{
					var id = $(e.target).attr("href");
					return $(id).find(".iButton-icons-tab").iButton
					(
						{
							labelOn:     "<i class='icon-ok'></i>",
							labelOff:       "<i class='icon-remove'></i>",
							handleWidth:    30
						}
					);
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
			$('.chzn-select:not(.select2-container),select').select2();
			
			$.extend
			(
			 	$.fn.dataTableExt.oStdClasses,
				{"sWrapper": "dataTables_wrapper form-inline"}
			);
			
			$('.dTable').dataTable
			(
				{
					bJQueryUI:			false,
					bAutoWidth:			false,
					sPaginationType:	'full_numbers',
					sDom:				'<"table-header"fl>t<"table-footer"ip>'
				}
			);

			$('.dTable-small').dataTable
			(
				{
					iDisplayLength:		5,
					bJQueryUI:			false,
					bAutoWidth:			false,
					sPaginationType:	'full_numbers',
					sDom:				'<"table-header"fl>t<"table-footer"ip>'
				}
			);

			$('.datepicker').datepicker
			(
				{
					todayBtn:   true,
					format:     'yyyy/mm/dd'
				}
			)
		},
		registerAction: function(key,callback)
		{
			this.actionRegistry[key]=callback;
			return this;
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
					event.preventDefault();
					var	btn		=$(event.currentTarget),
						action	=btn.data('action');

					for (var key in this.actionRegistry)
					{
						if (key==action && Object.isFunction(this.actionRegistry[key]))
						{
							this.actionRegistry[key](btn);
						}
					}
					if (Object.isFunction(this[action]))
					{
						this[action](btn);
					}
				}.bind(this)
			);
			$('body select').on
			(
				'change',
				'[data-action]',
				null,
				function onActionClick(event)
				{
					var	btn		=$(event.currentTarget),
						action	=btn.data('action');
					
					for (var key in this.actionRegistry)
					{
						if (key==action && Object.isFunction(this.actionRegistry[key]))
						{
							this.actionRegistry[key](btn);
						}
					}
					if (Object.isFunction(this[action]))
					{
						this[action](btn);
					}
				}.bind(this)
			);
			$('body').on
			(
				'click',
				'[data-toggle="bigmodal"]',
				null,
				this.initBigModal
			);
		},
		initBigModal: function()
		{
			$($(this).attr('href')).bigmodal();
		},
		addWidgetSelectionRow: function(btn)
		{
			var	index	=0,
				widgets	=btn.parent().find('[data-index]');
			if (widgets.length)
			{
				index=$(widgets.last()).data('index')+1;
			}
			$.get
			(
				'/admin/template/configureContent/addWidgetSelection',
				{
					index:index
				},
				function onGetAddContentTypeWidgetResponse(response)
				{
					btn.before(response);
					$('.chzn-select:not(.select2-container)').select2();
					this.initAll();
				}.bind(this)
			)
		},
		removeWidgetRow: function(btn)
		{
			btn.parents('.well.relative').remove();
		},
		getWidgetOptions: function(selectBox)
		{
			$.get
			(
				'/admin/configureContent/getConfigForWidget',
				{
					index:	selectBox.parents('[data-index]').data('index'),
					widget:	selectBox.val()
				},
				function onGetAddContentTypeWidgetResponse(response)
				{
					selectBox.parents('[data-index]').find('.container-fluid .well').html(response);
					this.initAll();
				}.bind(this)
			);
		},
		showSpinner: function(el,configOverride)
		{
			this.hideSpinner();
			if (Object.isUndefined(configOverride))
			{
				configOverride={};
			}
			var config=
			{
				lines:		11, // The number of lines to draw
				length:		21, // The length of each line
				width:		5, // The line thickness
				radius:		23, // The radius of the inner circle
				corners:	1, // Corner roundness (0..1)
				rotate:		0, // The rotation offset
				direction:	1, // 1: clockwise, -1: counterclockwise
				color:		'#000', // #rgb or #rrggbb
				speed:		1.8, // Rounds per second
				trail:		56, // Afterglow percentage
				shadow:		true, // Whether to render a shadow
				hwaccel:	true, // Whether to use hardware acceleration
				className:	'spinner', // The CSS class to assign to the spinner
				zIndex:		2e9, // The z-index (defaults to 2000000000)
				top:		'auto', // Top position relative to parent in px
				left:		'auto' // Left position relative to parent in px
			};
			config=Object.extend(config,configOverride);
			this.spinner=new Spinner(config).spin(el);
		},
		hideSpinner: function()
		{
			if (this.spinner)
			{
				$(this.spinner.el).remove();
			}
		},
		addURLToContent: function(btn)
		{
			btn.siblings('table').find('tbody').append
			(
				[
					'<tr>',
						'<td>',
							'<input type="text" class="input-medium" value="" data-prompt-position="topLeft" class="validate[required]" name="url[]">',
						'</td>',
						'<td>',
							'<button data-action="removeURLfromContent" class="btn btn-danger btn-mini" type="button">&times;</button>',
						'</td>',
					'</tr>'
				].join('')
			);
		},
		removeURLfromContent: function(btn)
		{
			var table=btn.parents('table');
			btn.parents('tr').remove();
			// this.reindex(table);
		}
	}
);