<script type="text/javascript">
requirejs.config
(
	{
		waitSeconds:	3,
		baseUrl:		'/js',
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
		'jskk',
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
</script>