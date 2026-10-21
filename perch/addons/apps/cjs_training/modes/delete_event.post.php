<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Delete Event');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the event %s?', $details['eventTitle']);
        echo $Form->form_start();
        echo $Form->hidden('eventID', $details['eventID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/events/');


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
