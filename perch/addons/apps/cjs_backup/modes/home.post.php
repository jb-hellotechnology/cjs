<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);  

	echo $HTML->title_panel([
    'heading' => $Lang->get('Backup'),
    ], $CurrentUser);
    

    # Main panel
  $HTML->main_panel_start();

	echo $Form->form_start();
	
	// Submit Button
	echo $Form->submit_field('btnSubmit', 'Run Backup', '');
	
	echo $Form->form_end();

  $HTML->main_panel_end();