<?php
  ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);  
    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_organisations');

    # include your class files
    include('../../CJS_Organisations.Org.class.php');
    include('../../CJS_Organisations.Orgs.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Organisations - CJS Organisations');
    
    # Set the sub navigation
    include('../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../modes/organisations_merge.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');
    
    # Display your page
    include('../../modes/organisations_merge.post.php');
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
