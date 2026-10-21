<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Send Emails'),

    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Long Courses',
      'link'  => '/addons/apps/cjs_training/emails/',
  ]);

  $Smartbar->add_item([
        'active' => true,
        'title' => 'Short Courses',
        'link'  => '/addons/apps/cjs_training/emails/short_courses/',
    ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Distance Courses',
        'link'  => '/addons/apps/cjs_training/emails/distance_courses/',
    ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Providers',
        'link'  => '/addons/apps/cjs_training/emails/providers/',
    ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Paid Adverts',
        'link'  => '/addons/apps/cjs_training/emails/paid_adverts/',
    ]);

  echo $Smartbar->render();

    # Main panel
	$HTML->main_panel_start();
	
	echo $Form->form_start();
	
		echo $HTML->heading2('Short Course Paid Advert Check');
		
		echo $Form->date_field('from', 'Check Date Greater Than', false, true);
		
		echo $Form->date_field('to', 'Check Date Less Than', false, true);
		
		echo $Form->submit_field('getListPaid', 'Get List');
		
		echo $Form->hidden('check','1');
		
	echo $Form->form_end();
	
	echo $Form->form_start();
	
		echo $HTML->heading2('Short Course Specific Categories');
	
		$Categories = [['label'=>'Choose Your Category','value'=>'-'],
                     ['label'=>'Access and Rights of Way','value'=>'row'],
                     ['label'=>'Administrative and Office Skills','value'=>'ad'],
                     ['label'=>'Community Engagement and Environmental Education','value'=>'ed'],
                     ['label'=>'Country Craft Courses','value'=>'craft'],
                     ['label'=>'Countryside Management Techniques','value'=>'cons'],
                     ['label'=>'First Aid, Risk Assessment and other Health & Safety Related Courses','value'=>'1aid'],
                     ['label'=>'Horticulture and Small Holding','value'=>'hort'],
                     ['label'=>'Identification and Field Survey Skills - Herpetology, Fish and Invertebrates','value'=>'invert'],
                     ['label'=>'Identification and Field Survey Skills - Mammals','value'=>'mammals'],
                     ['label'=>'Identification and Field Survey Skills - Ornithology','value'=>'birds'],
                     ['label'=>'Identification and Field Survey Skills - Plants and Habitats','value'=>'plants'],
                     ['label'=>'Photography','value'=>'photo'],
                     ['label'=>'Practical Countryside Skills','value'=>'skills'],
                     ['label'=>'Practical Countryside Skills - Machinery','value'=>'mach']];	
		echo $Form->select_field('category', 'Short Courses Specific Categories', $Categories, 'active');
		
		echo $Form->submit_field('getListCategories', 'Get List');
		
		echo $Form->hidden('categories','1');
	
	echo $Form->form_end();
	
	echo $Form->form_start();
	
		echo $HTML->heading2('Short Course Bi-Annual Reminder');
		
		echo $Form->select_field('filter', 'Filter', array(
		array('label'=>'All', 'value'=>'All'), array('label'=>'Published', 'value'=>'pub'), array('label'=>'Pending', 'value'=>'pen'), array('label'=>'Suspended', 'value'=>'sus')
		), 'active');
		
		echo $Form->submit_field('getListBiReminder', 'Get List');
		
		echo $Form->hidden('reminder','1');
	
	echo $Form->form_end();
	
	echo $Form->form_start();
	
		echo $HTML->heading2('Undated Short Courses');
		
		echo $Form->submit_field('getNotDatedList','Get List');
		
		echo $Form->hidden('undated','1');
	
	echo $Form->form_end(); 
	
	$HTML->main_panel_end();