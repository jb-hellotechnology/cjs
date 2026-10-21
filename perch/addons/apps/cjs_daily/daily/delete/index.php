<?php

    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_daily');

    # include your class files
    include('../../CJS_Daily.Job.class.php');
    include('../../CJS_Daily.Jobs.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Delete Daily Advert');
    
    # Set the sub navigation
    include('../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../modes/delete_daily_advert.pre.php');    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include('../../modes/delete_daily_advert.post.php');    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
