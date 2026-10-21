<?php
	PerchUI::set_subnav([
		[
			'page'=>[
					'cjs_workdays',
				], 
			'label'=>'Home'
		],
    [
      'page'=>[
        'cjs_workdays/offices',
      ],
      'label'=>'Offices'
    ],
    [
      'page'=>[
        'cjs_workdays/voluntary_organisations',
      ],
      'label'=>'Voluntary Organisations'
    ],
/*
    [
      'page'=>[
        'cjs_workdays/work_days',
      ],
      'label'=>'Work Days'
    ],
*/
    [
      'page'=>[
        'cjs_workdays/surveys',
      ],
      'label'=>'Surveys'
    ],
    [
      'page'=>[
        'cjs_workdays/send_emails',
      ],
      'label'=>'Send Emails'
    ],

	], $CurrentUser);
