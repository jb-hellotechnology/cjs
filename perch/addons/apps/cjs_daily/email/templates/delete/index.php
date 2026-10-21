<?php


// ini_set('display_errors', 1); 
// ini_set('display_startup_errors', 1); 
// error_reporting(E_ALL);


    # include the API
    include('../../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_daily');

    # include your class files
    include('../../../CJS_Daily.Email.class.php');
    include('../../../CJS_Daily.Emails.class.php');
    include('../../../CJS_Daily.Email.Templates.class.php');
    include('../../../CJS_Daily.Emails.Templates.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    if (isset($_GET['id']) && $_GET['id']!=''){
      $title = 'Edit Email Template';      
    }else{
      $title = 'Add Email Template';      
    }
    $Perch->page_title = $Lang->get($title);
    
    # Set the sub navigation
    include('../../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../../modes/delete_email_template.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include('../../../modes/delete_email_template.post.php');    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
