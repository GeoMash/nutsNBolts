requirejs.config
(
	{
		waitSeconds:	20,
		baseUrl:		'/admin/js/vendor/',
		paths:
		{
			jskk:				'jskk/jskk-1.1.0.min',
			'jskk-optional':	'jskk/jskk-1.1.0-optional.min',
			$JSKK:				'jskk',
			extension:			'extension',
			plupload:			'plupload',
			moment:             'moment',
			nutsnbolts:			'../nutsnbolts',
			pagedown:			'pagedown'
		}
	}
);

requirejs
(
	[
		'spin',
		'moment',
		'jquery',
		'jquery/ui',
		'jquery/browser',
		'jquery/gritter',
		'jquery/icheck',
		'jquery/uniform',
		'jquery/validationEngine',
		'jquery/validationEngine-en',
		'jquery/ibutton',
		'jquery/select2',
		'jquery/bootstrap-touchspin',
		'jquery/dataTables',
		'jquery/tags_input',
		'jquery/autosize',
		'jquery/fancybox/source/jquery.fancybox',
		// 'jquery/jstree/jstree',
		'bootstrap/bootstrap',
		'bootstrap/bootstrap-datepicker',
		'bootstrap/bigmodel',
		'wizard/bootstrap-wizard',
		'ckeditor/ckeditor',
		'jskk',
		'plupload/plupload.full',
		'pagedown/Markdown.Converter'
	],
	function(Spinner,moment)
	{
		window.moment=moment;
		window.Spinner=Spinner;
		requirejs
		(
			[
				'jquery/fancybox/source/helpers/jquery.fancybox-buttons',
				'pagedown/Markdown.Sanitizer',
				'pagedown/Markdown.Editor',
				'jskk-optional',
				'extension/function',
				'extension/string',
			    'nutsnbolts/Application'
			],
			function()
			{
				$application=new nutsnbolts.Application();
			}
		);
	}
);