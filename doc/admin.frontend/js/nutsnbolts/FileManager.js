$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts',
		$name:		'FileManager'
	}
)
(
	{},
	{
		FMWindow:			null,
		uploader:			null,
		currentCollection:	null,
		selectedFile:		null,
		selectedFilesField:	null,
		init: function()
		{
			this.initUploader();
			this.FMWindow=$('#fileManagerWindow');
			this.selectedFilesField=this.FMWindow.find('[name="selectedFiles"]');
			this.bindEvents();
			
			
			
		},
		bindEvents: function()
		{
			this.FMWindow.on
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
			).on('show',this.onShow.bind(this));
		},
		onShow: function()
		{
			(function()
			{
				$application.showSpinner(this.FMWindow.find('#collections')[0],{top:60});
				this.loadCollections();
			}.bind(this)).defer(1000);
		},
		show: function(config)
		{
			config=Object.extend
			(
				{
					buttons:{}
				},
				config
			);
			this.clearButtons();
			this.addButtons(config.buttons);
			$('#fileManagerWindow').bigmodal();
		},
		hide: function()
		{
			this.FMWindow.modal('hide');
		},
		clearButtons: function()
		{
			this.FMWindow.find('.extraButtons').html('');
			return this;
		},
		addButtons: function(buttons)
		{
			var	container	=this.FMWindow.find('.extraButtons'),
				thisButton	=null;
			for (var i=0,j=buttons.length; i<j; i++)
			{
				thisButton=$('<button class="btn btn-primary"><i class="'+buttons[i]['icon']+'"></i>&nbsp;'+buttons[i]['label']+'</button>');
				thisButton.click
				(
					function(button)
					{
						button['handler'](this);
					}.bind(this,buttons[i])
				);
				container.append(thisButton);
			}
			return this;
		},
		loadCollections: function()
		{
			$.get
			(
				'/admin/template/fileManager/collections',
				function onloadCollectionsResponse(response)
				{
					$application.hideSpinner();
					this.FMWindow.find('#collections').html(response);
				}.bind(this)
			);
		},
		openCollection: function(el)
		{
			el=$(el);
			var items=el.parents('ul').find('li');
			for (var i=0,j=items.length; i<j; i++)
			{
				$(items[i]).find('i:first').removeClass('icon-folder-open')
											.addClass('icon-folder-close');
			}
			el.find('i:first').addClass('icon-folder-open');
			this.loadCollectionFiles(el.data('id'));
			
			this.currentCollection=
			{
				id:		el.data('id'),
				name:	el.find('span').text()
			};
			
			this.uploader.settings.url='/admin/fileManager/upload/'+el.data('id');
		},
		loadCollectionFiles: function(id)
		{
			var container=this.FMWindow.find('#filesWrapper');
			container.find('#files').html('');
			$application.showSpinner(container[0],{top:60});
			$.get
			(
				'/admin/template/fileManager/files',
				{id:id},
				function onloadCollectionFilesResponse(response)
				{
					$application.hideSpinner();
					this.FMWindow.find('#files').html(response);
					// this.FMWindow.find('#files a').fancybox
					// (
					// 	{
					// 		padding:		0,
					// 		openEffect:		'elastic',
					// 		openSpeed:		150,
					// 		closeEffect:	'elastic',
					// 		closeSpeed:		150,
					// 		closeClick:		true,
					// 		helpers:
					// 		{
					// 			// overlay : null,
					// 			buttons	: {}
					// 		}
					// 	}
					// );
					this.FMWindow.find('#files a').click(this.onFileClick.bind(this));
				}.bind(this)
			);
		},
		onFileClick: function(event)
		{
			event.preventDefault();
			this.selectedFile=
			{
				src:	$(event.target).attr('href'),
				title:	$(event.target).attr('title'),
				thumb:	$(event.target).css('background-image')
			};
			this.selectedFilesField.data('href',$(event.target).attr('href'));
			this.selectedFilesField.val(this.currentCollection.name+'://'+this.selectedFile.title);
		},
		getSelected: function()
		{
			return this.selectedFile;
		},
		showUploadFiles: function(btn)
		{
			var carousel=this.FMWindow.find('.carousel');
			carousel.carousel('next');
			btn.hide().siblings('[data-action="showFileList"]').show();
			this.FMWindow.find('.modal-body .span10 >.row-fluid:nth-child(2)').hide()
			carousel.carousel('pause');
		},
		showFileList: function(btn)
		{
			var carousel=this.FMWindow.find('.carousel');
			carousel.carousel('prev');
			btn.hide().siblings('[data-action="showUploadFiles"]').show();
			this.FMWindow.find('.modal-body .span10 >.row-fluid:nth-child(2)').show()
			carousel.carousel('pause');
		},
		
		/***********
		 * UPLOADER
		 ***********/
		
		initUploader: function()
		{
			$JSKK.when
			(
				function()
				{
					return Boolean($('#addFiles').length);
				}
			).isTrue
			(
				function()
				{
					this.uploader=new plupload.Uploader
					(
						{
							runtimes:			'html5,flash',
							browse_button:		'addFiles',
							// container:			'container',
							max_file_size:		'500mb',
							url:				'/admin/fileManager/upload',
							flash_swf_url:		'/js/vendor/plupload/plupload.flash.swf',
							silverlight_xap_url:'/js/vendor/plupload/plupload.silverlight.xap'
						}
					);
					this.uploader.bind('Init',				this.onUploaderInit.bind(this));
					this.uploader.bind('FilesAdded',		this.onUploaderAddFiles.bind(this));
					this.uploader.bind('UploadProgress',	this.onUploaderProgess.bind(this));
					this.uploader.bind('UploadComplete',	this.onUploaderComplete.bind(this));
					
					$('[data-action="uploadFiles"]').click(this.onUploaderClickStartUpload.bind(this));
					
					this.uploader.init();
				}.bind(this)
			);
		},
		onUploaderInit: function()
		{
			
		},
		onUploaderAddFiles: function(uploader,files)
		{
			var HTML=[];
			for (var i=0,j=files.length; i<j; i++)
			{
				HTML.push
				(
					[
						'<tr data-id="',files[i].id,'">',
							'<td>',files[i].name,'</td>',
							'<td>',
								'<div class="progress progress-striped progress-blue active">',
									'<div class="bar tip" title="" data-percent="0" style="width: 0%;" data-original-title="0%"></div>',
								'</div>',
							'</td>',
							'<td>',plupload.formatSize(files[i].size),'</td>',
							'<td><button class="btn btn-mini btn-red" data-action="onUploaderRemoveFile"><i class="icon-remove"></i></button></td>',
						'</tr>'
					].join('')
				);
			}
			this.FMWindow.find('#uploader tbody').append(HTML.join(''));
		},
		onUploaderRemoveFile: function(btn)
		{
			var tr=btn.parents('tr');
			this.uploader.removeFile(this.uploader.getFile(tr.data('id')));
			tr.remove();
		},
		onUploaderClickStartUpload: function()
		{
			this.uploader.start();
		},
		onUploaderProgess: function(uploader,file)
		{
			var fileRow=this.FMWindow.find('[data-id="'+file.id+'"]');
			fileRow.find('.progress .bar')
						.width(file.percent+'%')
						.data('percent',file.percent);
			if (file.percent==100)
			{
				fileRow.find('td button').remove();
			}
		},
		onUploaderComplete: function(uploader,files)
		{
			// var fileRow=null;
			// for (var i=0,j=files.length; i<j; i++)
			// {
			// 	fileRow=this.FMWindow.find('[data-id="'+files[i].id+'"]');
			// 	fileRow.find('td button').remove();
			// 	fileRow.find('.progress .bar')
			// 				.width('100%')
			// 				.data('percent',100);
			// }
		}
	}
);