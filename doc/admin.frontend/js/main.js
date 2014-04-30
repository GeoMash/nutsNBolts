requirejs.config
(
	{
		waitSeconds:	20,
		baseUrl:		'/js/vendor/',
		paths:
		{
			jskk:				'jskk/jskk-1.1.0.min',
			'jskk-optional':	'jskk/jskk-1.1.0-optional.min',
			$JSKK:				'jskk',
			extension:			'extension',
			plupload:			'plupload',
			nutsnbolts:			'../nutsnbolts'
			
		}
	}
);

requirejs
(
	[
		'spin',
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
		'jquery/dataTables',
		'jquery/tags_input',
		'jquery/fancybox/source/jquery.fancybox',
		'jquery/fancybox/source/helpers/jquery.fancybox-buttons',
		// 'jquery/jstree/jstree',
		'bootstrap/bootstrap',
		'bootstrap_wysihtml5/wysihtml5-0.3.0',
		'bootstrap/bigmodel',
		'jskk',
		'plupload/plupload.full'
	],
	function(Spinner)
	{
		window.Spinner=Spinner;
		requirejs
		(
			[
				'jskk-optional',
				'extension/function',
				'extension/string',
				'bootstrap_wysihtml5/bootstrap-wysihtml5'
			],
			function()
			{
				$JSKK.require
				(
					'nutsnbolts.Application',
					function()
					{
						$application=new nutsnbolts.Application();
					}
				);
			}
		);
	}
);