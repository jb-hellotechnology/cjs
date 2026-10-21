<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Delete Long Course');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the long course %s?', $details['longTitle']);
        echo $Form->form_start();
        echo $Form->hidden('longID', $details['longID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/long_courses/');


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
