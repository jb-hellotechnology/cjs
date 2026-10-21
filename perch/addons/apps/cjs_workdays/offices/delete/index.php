<?php

    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_workdays');

    # include your class files
    include('../../CJS_Workdays.Organisation.class.php');
    include('../../CJS_Workdays.Organisations.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Delete Organisation');
    
    # Set the sub navigation
    include('../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../modes/delete_organisation.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include('../../modes/delete_organisation.post.php');    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
