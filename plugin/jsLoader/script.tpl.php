<script type="text/javascript">
requirejs.config
(
	{
		waitSeconds:	20,
		baseUrl:		'/admin/js/',
		paths:
		{
			jskk:				'vendor/jskk/jskk-1.1.0.min',
			'jskk-optional':	'vendor/jskk/jskk-1.1.0-optional.min',
			$JSKK:				'vendor/jskk'
		}
	}
);
requirejs
(
	[
		'jskk'
	],
	function()
	{
		requirejs
		(
			[
				'jskk-optional',
				'nutsnbolts/Application',
				<?php $tpl->scripts; ?>
			],
			function()
			{
				$JSKK.when
				(
					function()
					{
						return Object.isDefined(window.$application);
					}
				).isTrue
				(
					function()
					{
						<?php $tpl->exec; ?>
					}
				);
				
			}
		);
	}
);
</script>