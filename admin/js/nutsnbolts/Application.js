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
			
			$('input[type=checkbox]:not(.iButton-icons)')
				.addClass('iCheck')
				.iCheck
				(
					{
						checkboxClass:	'icheckbox_flat-aero',
						radioClass:		'iradio_flat-aero'
					}
				);
			this.initIButtons();
			this.initImpersonateUser();
			this.initUserSelect();
			this.initSelect2();
			
			$('[data-toggle="tooltip"]').tooltip()
			
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
			);
			
			
			
			$('[data-sortable="true"] tbody').sortable
			(
				{
					handle:	'.icon-reorder',
					stop:	function(event,ui)
					{
						$('[name="order[]"]',ui.item.parent().children()).each
						(
							function(i)
							{
								$(this).val(i);
							}
						);
					}
				}
			);
			
			$('input[type="number"]').TouchSpin
			(
				{
//					min:	0,
					max: 9999999999,
					step:			1,
					decimals:		0,
					boostat:		5,
					maxboostedstep:	10
				}
			);
			
			
			$('input[name="set_random"]').on
			(
				'ifChanged',
				function(event)
				{
					if ($(event.target).is(':checked'))
					{
						$('input[name="password"]').attr('disabled',true);
						$('input[name="password_confirm"]').attr('disabled',true);
					}
					else
					{
						$('input[name="password"]').attr('disabled',false);
						$('input[name="password_confirm"]').attr('disabled',false);
					}
				}
			);
		},
		initImpersonateUser: function()
		{
			$('#impersonateUser').select2
			(
				{
					placeholder:		'Impersonate User',
					minimumInputLength: 1,
					ajax:
					{
						cache:	true,
						url:	'/rest/user/search.json',
						data:	function(term,page)
						{
							return {
								query:	term
							};
						},
						results: this.handleUserResults.bind(this)
					},
//					formatResult: function(result)
//					{
//						
//					}
				}
			).on
			(
				'change',
				function(event)
				{
					var select=$('#impersonateUser');
					$.getJSON
					(
						'/rest/user/impersonate/start/'+select.val()+'.json',
						function()
						{
							//Refresh the window.
							alert('Switching to impersonate user mode.');
							window.location.reload();
						}
					);
				}.bind(this)
			);
			
			$('#stopImpersonatingUser').click
			(
				function()
				{
					$.getJSON
					(
						'/rest/user/impersonate/stop.json',
						function()
						{
							//Refresh the window.
							alert('Switching to non-impersonation mode.');
							window.location.reload();
						}
					);
				}.bind(this)
			);
		},
		initUserSelect: function()
		{
			$('[data-role=selectUser]').select2
			(
				{
					placeholder:		'Select User',
					minimumInputLength: 1,
					initSelection: function(element, callback)
					{
						$.getJSON
						(
							'/rest/user/search.json',
							{
								query: element.val()
							},
							function(response)
							{
								var results=this.handleUserResults(response);
								callback(results.results[0]);
							}.bind(this)
						);
					}.bind(this),
					ajax:
					{
						cache:	true,
						url:	'/rest/user/search.json',
						data:	function(term,page)
						{
							return {
								query:	term
							};
						},
						results: this.handleUserResults.bind(this)
					},
//					formatResult: function(result)
//					{
//						
//					}
				}
			)
			$('[data-role="selectUser"][name="userAccess"]').on
			(
				'change',
				function(event)
				{console.debug(event);
					var	select	=$(event.target),
						row		=
						[
							'<tr class="text-center">',
								'<td>',event.added.text,'</td>',
								'<td><input type="checkbox" name="userAccess[read][',select.val(),']" value="1"></td>',
								'<td><input type="checkbox" name="userAccess[update][',select.val(),']" value="1"></td>',
								'<td><input type="checkbox" name="userAccess[delete][',select.val(),']" value="1"></td>',
								'<td>',
									'<button data-action="removeRow" class="btn btn-danger btn-mini" type="button">&times;</button>',
								'</td>',
							'</tr>'
						].join('');
					$('#selectUserTable tbody').append(row);
				}.bind(this)
			);
		},
		handleUserResults: function(response,page)
		{
			var results=[];
			for (var i= 0,j=response.data.length; i<j; i++)
			{
				results.push
				(
					{
						id:		response.data[i].id,
						text:	response.data[i].name_first+' '
								+response.data[i].name_last+' ('
								+response.data[i].email+')'
					}
				);
			}
			return {results: results}
		},
		initSelect2: function()
		{
			$('.chzn-select:not(.select2-container),select').select2();
		},
		initIButtons: function()
		{
			$('.iButton-icons').iButton
			(
				{
					labelOn:		'<i class="icon-ok"></i>',
					labelOff:		'<i class="icon-remove"></i>',
					handleWidth:	30
				}
			);
			$('.iButton-icons-tab').each
			(
				function()
				{
					if ($(this).is(':visible'))
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
			$('.iButton-enabled').iButton
			(
				{
					labelOn:		'ENABLED',
					labelOff:		'DISABLED',
					handleWidth:	30
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
							labelOn:    	'<i class="icon-ok"></i>',
							labelOff:       '<i class="icon-remove"></i>',
							handleWidth:    30
						}
					);
				}
			);
			$('.iButton').iButton();
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
					var	el		=$(event.currentTarget),
						action	=el.data('action');
					
					for (var key in this.actionRegistry)
					{
						if (key==action && Object.isFunction(this.actionRegistry[key]))
						{
							this.actionRegistry[key](el);
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
			this.removeURLfromContent(btn);
		},
		removeRow: function(btn)
		{
			var table=btn.parents('table');
			btn.parents('tr').remove();
			// this.reindex(table);
		},
		addNewSetting: function(btn)
		{
			btn.siblings('table').find('tbody').append
			(
				[
					'<tr>',
						'<td>',
							'<input type="text" class="input-medium" value="" data-prompt-position="topLeft" class="validate[required]" name="key[]">',
						'</td>',
						'<td>',
							'<input type="text" class="input-medium" value="" data-prompt-position="topLeft" class="validate[required]" name="value[]">',
						'</td>',
						'<td>',
							'<button data-action="removeRow" class="btn btn-danger btn-mini" type="button">&times;</button>',
						'</td>',
					'</tr>'
				].join('')
			);
		},
		removeRow: function(btn)
		{
			var table=btn.parents('table');
			btn.parents('tr').remove();
		},
		makeSafeFieldName: function(string)
		{
			string=string.replace(/[^\w]/g,'_');
			while (string.indexOf('__')!==-1)
			{
				string=string.replace('__','_');
			}
			return string;
		},
		navChangeType: function(select)
		{
			var linkCol=select.parent().next();
			switch (select.val())
			{
				case 'select':
				{
					linkCol.html('<p>(Select a Type)</p>');
					break;
				}
				case 'page':
				{
					var select=$('<select name="page_id[]"></select>');
					select.append($('[data-template="pageOptions"]').children().clone());
					linkCol.html
					(
						[
							'<input type="hidden" class="input-medium" value="" name="node_id[]">',
							'<input type="hidden" class="input-medium" value="" name="url[]">'
						].join('')
					);
					linkCol.append(select);
					select.select2();
					break;
				}
				case 'node':
				{
					var select=$('<select name="node_id[]"></select>');
					select.append($('[data-template="nodeOptions"]').children().clone());
					linkCol.html
					(
						[
							'<input type="hidden" class="input-medium" value="" name="page_id[]">',
							'<input type="hidden" class="input-medium" value="" name="url[]">'
						].join('')
					);
					linkCol.append(select);
					select.select2();
					break;
				}
				case 'url':
				{
					linkCol.html
					(
						[
							'<input type="hidden" class="input-medium" value="" name="page_id[]">',
							'<input type="hidden" class="input-medium" value="" name="node_id[]">',
							'<input type="text" class="input-medium" value="" name="url[]">'
						].join('')
					);
					break;
				}
			}
		},
		addNavigationRow: function(btn)
		{
			var tbody=btn.siblings('table').find('tbody'),
				order=tbody.children().length;
			tbody.append
			(
				[
					'<tr>',
						'<td>',
							'<input type="hidden" name="order[]" value="'+order+'">',
							'<p><i class="icon-reorder cursor-move"></i></p>',
						'</td>',
						'<td>',
							'<input type="text" class="input-medium" value="" name="label[]">',
						'</td>',
						'<td>',
							'<select name="type" data-action="navChangeType">',
								'<option value="select">Select Type</option>',
								'<option value="page">Page</option>',
								'<option value="node">Content Item</option>',
								'<option value="url">URL</option>',
							'</select>',
						'</td>',
						'<td>',
							'<p>(Select a Type)</p>',
						'</td>',
						'<td>',
							'<button data-action="removeRow" class="btn btn-danger btn-mini" type="button">&times;</button>',
						'</td>',
					'</tr>'
				].join('')
			);
			this.initSelect2();
			this.initIButtons();
		}
	}
);