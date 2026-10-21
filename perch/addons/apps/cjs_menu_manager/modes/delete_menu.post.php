<?php

    # Main panel
    echo $HTML->main_panel_start(); 
//    include('_subnav.php');
    echo $HTML->heading1('Delete Menu');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the Menu %s?', $details['name']);
        echo $Form->form_start();
        echo $Form->hidden('menuID', $details['menuID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/');


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();