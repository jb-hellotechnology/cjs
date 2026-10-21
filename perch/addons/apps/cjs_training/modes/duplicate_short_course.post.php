<?php

    # Main panel
    echo $HTML->main_panel_start(); 
//    include('_subnav.php');
    echo $HTML->heading1('Duplicate Short Course');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to duplicate the short course %s?', $details['shortTitle']);
        echo $Form->form_start();
        echo $Form->hidden('shortID', $details['shortID']);
		    echo $Form->submit_field('btnSubmit', 'Duplicate', $API->app_path().'/short_courses/');


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
