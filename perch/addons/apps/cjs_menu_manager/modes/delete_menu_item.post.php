<?php

    # Main panel
    echo $HTML->main_panel_start(); 
//    include('_subnav.php');
    echo $HTML->heading1('Delete Menu Item');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the Menu Item %s?', $details['pageTitle']);
        echo $Form->form_start();
        echo $Form->hidden('menuItemID', $details['menuItemID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/');


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();