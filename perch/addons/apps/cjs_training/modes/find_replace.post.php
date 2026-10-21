<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Find and Replace'),
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs



    # Main panel
  $HTML->main_panel_start();

  if($message){
    echo $message;
  }else{

    echo $Form->form_start();
    
    $tables = array(
                   array('label'=>'Please Select', 'value'=>''),
                   array('label'=>'Offices', 'value'=>'perch3_organisations_t'), 
                   array('label'=>'Providers', 'value'=>'perch3_providers'),
                   array('label'=>'Short Courses', 'value'=>'perch3_short_courses'),
                   array('label'=>'Events', 'value'=>'perch3_events'),
                   array('label'=>'Long Courses', 'value'=>'perch3_long_courses'),
                );
                
    $operators = array(
                   array('label'=>'=', 'value'=>'='),
                   array('label'=>'>', 'value'=>'>'), 
                   array('label'=>'>=', 'value'=>'>='),
                   array('label'=>'<', 'value'=>'<'),
                   array('label'=>'<=', 'value'=>'<='),
                   array('label'=>'LIKE', 'value'=>'LIKE'),
                );

    # Select Field for choosing either Short Courses or Events to tidy up
    echo $Form->select_field('table', 'Table', $tables, 'active');
    echo $Form->select_field('field', 'Field', '', 'active');
    echo $Form->text_field('toequal', 'To Equal', '', 'input-simple m');
    echo $Form->select_field('where', 'Where', '', 'active');
    echo $Form->select_field('selector', 'Selector', $operators, 'active');
	echo $Form->text_field('value', 'Value', '', 'input-simple m');
	
    // Delete Archives Button
    echo $Form->submit_field('btnDelete', 'Run Query', '');

    echo $Form->form_end();
    
    echo "<p><strong>This query will affect <span id='count'>0</span> rows</p>";

  }
	$HTML->main_panel_end();