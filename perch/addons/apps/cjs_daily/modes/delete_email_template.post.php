<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Delete Email Template');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the email template %s?', $details['name']);
        echo $Form->form_start();
        echo $Form->hidden('templateID', $details['templateID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/email/templates/');

        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
