<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  if($_POST){
    
    ob_end_clean();

    $postvars = array('checkDateGreater', 'checkDateLess');	 
    $data = $Form->receive($postvars);

    $data['checkDateGreater'] = $Form->get_date('checkDateGreater');
    $data['checkDateLess'] = $Form->get_date('checkDateLess');
    
    $Djobs = new CJS_Daily_Jobs($API);
    $djobs = array();
    $djobs = $Djobs->all();

    // CREATE CSV

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=daily.csv');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // output the column headings
    fputcsv($output, array(
    	'daily_jobID', 
    	'title', 
    	'employerDisplay', 
    	'employerID', 
    	'imageLogo', 
    	'imageAlt', 
    	'postedDate', 
    	'closingDate', 
    	'jobOfDay', 
    	'cVolunteer', 
    	'cApprentice', 
    	'cIntern', 
    	'cClosingDateEu',
        'cDisplayDNotCDate', 
        'displayAd', 
        'advertAmendedDate', 
        'advertURL', 
        'sAdminIT', 
        'sArbo', 
        'sComVol', 
        'sCountry', 
        'sEco', 
        'sEnvOE', 
        'sSustainability', 
        'sPolicy', 
        'sFieldwork', 
        'sPractical', 
        'sForestry', 
        'rNational', 
        'rInternational', 
        'rScotland', 
        'rScotlandNorth', 
        'rScotlandSouth', 
        'rWales',
		'rNorthernIreland', 
		'rEngland', 
		'rAnglia', 
		'rMidlands', 
		'rNorthEast', 
		'rNorthWest', 
		'rSouth', 
		'rSouthWest', 
		'sHorti', 
		'sPubF', 
		'sRoW', 
		'sVisMan', 
		'sWiWoAnC', 
		'location', 
		'grade',
        'cfullTime', 
        'cpartTime', 
        'cpermanent', 
        'cTempFixed', 
        'cseasonal', 
        'advertStatus', 
        'daily_jobDynamicFields', 
        'advertText'));

    // EXPORT DATA!
    foreach($djobs AS $Job){

        if($Job->postedDate()>=$data['checkDateGreater'] AND $Job->postedDate()<=$data['checkDateLess']){
	        
	      $advertData = json_decode($Job->daily_jobDynamicFields(), true);  
	      //print_r($advertData);
	      
	      $text = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $snip);
	        
          fputcsv($output, array(
          $Job->daily_jobID(), 
          $Job->title(), 
          $Job->employerDisplay(), 
          $Job->employerID(), 
          $Job->imageLogo(), 
          $Job->imageAlt(), 
          $Job->postedDate(), 
          $Job->closingDate(), 
          $Job->jobOfDay(), 
          $Job->cVolunteer(), 
          $Job->cApprentice(), 
          $Job->cIntern(), 
          $Job->cClosingDateEu(), 
          $Job->cDisplayDNotCDate(), 
          $Job->displayAd(), 
          $Job->advertAmendedDate(), 
          $Job->advertURL(), 
          $Job->sAdminIT(), 
          $Job->sArbo(), 
          $Job->sComVol(), 
          $Job->sCountry(), 
          $Job->sEco(), 
          $Job->sEnvOE(), 
          $Job->sSustainability(), 
          $Job->sPolicy(), 
          $Job->sFieldwork(), 
          $Job->sPractical(), 
          $Job->sForestry(), 
          $Job->rNational(), 
          $Job->rInternational(), 
          $Job->rScotland(), 
          $Job->rScotlandNorth(), 
          $Job->rScotlandSouth(), 
          $Job->rWales(), 
          $Job->rNorthernIreland(), 
          $Job->rEngland(), 
          $Job->rAnglia(), 
          $Job->rMidlands(), 
          $Job->rNorthEast(), 
          $Job->rNorthWest(), 
          $Job->rSouth(), 
          $Job->rSouthWest(), 
          $Job->sHorti(),
          $Job->sPubF(), 
          $Job->sRoW(), 
          $Job->sVisMan(), 
          $Job->sWiWoAnC(),
          $Job->location(), 
          $Job->grade(), 
          $Job->cfullTime(), 
          $Job->cpartTime(), 
          $Job->cpermanent(), 
          $Job->cTempFixed(), 
          $Job->cseasonal(), 
          $Job->advertStatus(), 
          $Job->daily_jobDynamicFields(), 
          $text));  
        }
      
    }
    
    exit;
    
  }