<?php

    # include the API
    include('../../../../../core/inc/api.php');

    $API  = new PerchAPI(1.0, 'cjs_training');

    # include your class files
    include('../../CJS_Training.Long_Courses.class.php');
    include('../../CJS_Training.Long_Course.class.php');

    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Duplicate Long Course');

    # Set the sub navigation
    include('../../modes/_subnav.php');

    # Do anything you want to do before output is started
    include('../../modes/duplicate_long_course.pre.php');

    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include('../../modes/duplicate_long_course.post.php');

    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
