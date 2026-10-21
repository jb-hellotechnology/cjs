<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Delete Volunteer');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the volunteer %s?', $details['title']);
        echo $Form->form_start();
        echo $Form->hidden('volunteerID', $details['volunteerID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/volunteers/');

        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
