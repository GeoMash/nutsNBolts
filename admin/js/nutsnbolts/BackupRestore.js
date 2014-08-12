$JSKK.Class.create
(
	{
		$namespace:	'nutsnbolts',
		$name:		'BackupRestore'
	}
)
(
	{},
	{
		uploader:	null,
		wizard:
		{
			backup:		null,
			restore:	null
		},
		init: function()
		{
			this.initUploader();
			
//			$.fn.wizard.logging=true;
			
			this.wizard.backup	=$('#wizard-backup').wizard(),
			this.wizard.restore	=$('#wizard-restore').wizard
			(
				{
					backdrop:	'static',
					keyboard:	false,
					buttons:
					{
						nextText:		'Next',
						submitText:		'Restore',
						submittingText:	'Restoring data...'
					}
				}
			);
			
			this.wizard.backup.setTitle('Backup Data Wizard');
			this.wizard.restore.setTitle('Restore Data Wizard');
			
//			$('#open-wizard-restore [type="file"]')
			
			
			this.bindEvents();
		},
		bindEvents: function()
		{
			$('#open-wizard-restore').click
			(
				function()
				{
					this.wizard.restore.show();
				}.bind(this)
			);
			this.wizard.restore.on
			(
				'submit',
				function(event)
				{
					var type=$('#wizard-restore [name=dataType]').val();
					this.uploader.settings.url='/admin/settings/backupRestore/restore/?'+this.wizard.restore.serialize();
					this.uploader.start();
				}.bind(this)
			);
//			this.wizard.restore.on
//			(
//				'incrementCard',
//				function(event)
//				{
//					console.debug('incrementCard',arguments);
//					
//				}.bind(this)
//			);
		},
		show: function(config)
		{
			this.wizard.restore.show();
		},
		hide: function()
		{
			this.wizard.restore.hide();
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
							browse_button:		'restoreUploadButton',
							multi_selection:	false,
							// container:			'container',
							max_file_size:		'500mb',
							url:				'',
							flash_swf_url:		'/js/vendor/plupload/plupload.flash.swf',
							silverlight_xap_url:'/js/vendor/plupload/plupload.silverlight.xap',
							filters:
							{
								mime_types:
								[
									{title: 'CSV files', extensions: 'csv'},
									{title: 'SQL files', extensions: 'sql'}
								]
							}
						}
					);
					this.uploader.bind('Init',				this.onUploaderInit.bind(this));
					this.uploader.bind('FilesAdded',		this.onUploaderAddFiles.bind(this));
					this.uploader.bind('UploadProgress',	this.onUploaderProgess.bind(this));
					this.uploader.bind('UploadComplete',	this.onUploaderComplete.bind(this));
					
//					$('[data-action="uploadFiles"]').click(this.onUploaderClickStartUpload.bind(this));
					
					this.uploader.init();
				}.bind(this)
			);
		},
		onUploaderInit: function()
		{
			
		},
		onUploaderAddFiles: function(uploader,file)
		{
			$('#restoreUploadButton').parent().find('span.filename').text(file[0].name);
		},
		onUploaderRemoveFile: function(btn)
		{
			
		},
		onUploaderClickStartUpload: function()
		{
			this.uploader.start();
		},
		onUploaderProgess: function(uploader,file)
		{
			console.debug('file.percent');
		},
		onUploaderComplete: function(uploader,files)
		{
			console.debug('onUploaderComplete');
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