<?php

    # Main panel
    echo $HTML->main_panel_start(); 
//    include('_subnav.php');
    echo $HTML->heading1('Delete Work Day');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the work day %s?', $details['Title']);
        echo $Form->form_start();
        echo $Form->hidden('workdayID', $details['workdayID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/workdays/');


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();