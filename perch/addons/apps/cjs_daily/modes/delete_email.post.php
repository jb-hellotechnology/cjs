<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Delete Email');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the email %s?', $details['subject']);
        echo $Form->form_start();
        echo $Form->hidden('emailID', $details['emailID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/email/');

        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
