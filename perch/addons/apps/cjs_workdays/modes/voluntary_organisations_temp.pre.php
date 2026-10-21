<?php

  $HTML 		= $API->get('HTML');
  $Form 		= $API->get('Form');
  $Template = $API->get('Template');

  $VOrganisations_wd = new CJS_Workdays_Vol_Orgs($API);

  $vorganisations_wd = array();

  $vorganisations_wd = $VOrganisations_wd->get_temp();

  if($Form->submitted()){
      
    foreach($vorganisations_wd as $vorg){
	    
	    $vorg['vorganisationID'] = NULL;
	    
	    $VOrganisations_wd->import_temp_row($vorg);
	    
    }
    
    $VOrganisations_wd->empty_temp();
    PerchUtil::redirect($API->app_path().'/voluntary_organisations');
      
  }