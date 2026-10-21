<?php

$HTML = $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

$VOrganisations_wd = new CJS_Workdays_Vol_Orgs($API);
$vorganisations_wd = array();
$vorganisations_wd = $VOrganisations_wd->pending();

$Surveys = new CJS_Workdays_Surveys($API);
$surveys = array();
$surveys = $Surveys->pending();