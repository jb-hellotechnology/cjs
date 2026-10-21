<?php

    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_organisations');

    # include your class files
    include('../../CJS_Organisations.Org.class.php');
    include('../../CJS_Organisations.Orgs.class.php');

    # Do anything you want to do before output is started
    include('../../modes/get_organisation.pre.php');    