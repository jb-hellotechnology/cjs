<?php
    
    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_workdays');

    # include your class files
    include('../../CJS_Workdays.Work_Day.class.php');
    include('../../CJS_Workdays.Work_Days.class.php');
    include('../../CJS_Workdays.Vol_Org.class.php');
    include('../../CJS_Workdays.Vol_Orgs.class.php');
    include('../../CJS_Workdays.Survey.class.php');
    include('../../CJS_Workdays.Surveys.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Send Emails - CJS Work Days');
    
    # Set the sub navigation
    include('../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../modes/send_email_surveys.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include('../../modes/send_email_surveys.post.php');    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');