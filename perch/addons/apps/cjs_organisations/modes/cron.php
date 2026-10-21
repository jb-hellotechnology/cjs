<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include('../../../../core/inc/api.php');
    
$API  = new PerchAPI(1.0, 'cjs_organisations');

include('../CJS_Organisations.Org.class.php');
include('../CJS_Organisations.Orgs.class.php');
    
$Organisations = new CJS_Organisations_Orgs($API);

$Organisations->org_list();