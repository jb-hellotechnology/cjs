<?php
	PerchUI::set_subnav(
	[
		[
			'page'=>[
					'cjs_subscribers',
			], 
			'label'=>'Subscribers'
		],[
			'page'=>[
				'cjs_subscribers/import',
			],
			'label'=>'Import'
		]
	], $CurrentUser);
