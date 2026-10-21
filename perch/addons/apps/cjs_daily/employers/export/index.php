<?php
    
    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_daily');

    # include your class files
    include('../../CJS_Daily.Employer.class.php');
    include('../../CJS_Daily.Employers.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Employers - CJS Daily');
    
    # Set the sub navigation
    include('../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../modes/export_emp.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include('../../modes/export_emp.post.php');    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
