<?php

    # Main panel
    echo $HTML->main_panel_start(); 
//    include('_subnav.php');
    echo $HTML->heading1('Delete Voluntary Organisation');
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the voluntary organisation %s?', $details['vorgName']);
        echo $Form->form_start();
        echo $Form->hidden('vorganisationID', $details['vorganisationID']);
		    echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/voluntary_organisations/');


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();