<?php

    # include the API
    include('../../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_training');

    # include your class files
    include('../../CJS_Training.Organisation.class.php');
    include('../../CJS_Training.Organisations.class.php');

    # Do anything you want to do before output is started
    include('../../modes/get_organisation.pre.php');    