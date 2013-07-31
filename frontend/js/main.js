requirejs.config
(
	{
		waitSeconds:	3,
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
		'bootstrap/bootstrap',
		'jskk',
		'plupload/plupload.full'
	],
	function()
	{
		requirejs
		(
			[
				'jskk-optional',
				'extension/function',
				'extension/string'
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