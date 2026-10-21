<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Delete Employer');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the employer %s?', $details['title']);
        echo $Form->form_start();
        echo $Form->hidden('employerID', $details['employerID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/employers/');

        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
