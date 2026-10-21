<?php
	  echo $HTML->title_panel([
    'heading' => $Lang->get('Home'),
    ], $CurrentUser);
    
    $HTML->main_panel_start();
    
    echo $HTML->para('Choose an option from the menu');
    
	  $HTML->main_panel_end();