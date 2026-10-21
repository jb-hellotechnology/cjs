<?php

/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
*/

    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_workdays');

    # include your class files
    include('../../CJS_Workdays.Organisation.class.php');
    include('../../CJS_Workdays.Organisations.class.php');
    include('../../CJS_Workdays.Vol_Org.class.php');
    include('../../CJS_Workdays.Vol_Orgs.class.php');
    include('../../CJS_Workdays.Work_Day.class.php');
    include('../../CJS_Workdays.Work_Days.class.php');
    include('../../CJS_Workdays.Survey.class.php');
    include('../../CJS_Workdays.Surveys.class.php');
    include('../../../cjs_organisations/CJS_Organisations.Org.class.php');
    include('../../../cjs_organisations/CJS_Organisations.Orgs.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    if (isset($_GET['id']) && $_GET['id']!=''){
      $title = 'Edit Organisation';      
    }else{
      $title = 'Add Organisation';      
    }
    $Perch->page_title = $Lang->get($title);
    
    # Set the sub navigation
    include('../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../modes/add_new_organisation.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include('../../modes/add_new_organisation.post.php');    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
