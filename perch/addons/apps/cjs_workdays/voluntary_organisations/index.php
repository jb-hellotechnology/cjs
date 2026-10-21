<?php
  
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
*/

    # include the API
    include('../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_workdays');

    # include your class files
    include('../CJS_Workdays.Vol_Org.class.php');
    include('../CJS_Workdays.Vol_Orgs.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Voluntary Organisations - CJS Workdays');
    
    # Set the sub navigation
    include('../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../modes/voluntary_organisations.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');
    
    # Display your page
    include('../modes/voluntary_organisations.post.php');
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
