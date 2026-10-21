<?php

  $HTML 		= $API->get('HTML');
  $Form 		= $API->get('Form');
  $Template = $API->get('Template');

  $Organisations = new CJS_Organisations_Orgs($API);
  
  $Daily = new CJS_Daily_Employers($API);
  $Training = new CJS_Training_Organisations($API);
  $Workdays = new CJS_Workdays_Organisations($API);

  $organisations = array();

  $organisations = $Organisations->all();

  $message = '';

  if($Form->submitted()){
	
	header('Content-Type: text/csv; charset=utf-8');  
	header('Content-Disposition: attachment; filename=data.csv');  
	
	$output = fopen("php://output", "w");  
	
	fputcsv($output, array('Contact Name', 'Contact Email', 'Contact Organisation', 'Organisation', 'Volunteers', 'Apprenticeships', 'Sectors'));
	  
	foreach($organisations as $Organisation) {
		
		$dynamic = json_decode($Organisation->organisationDynamicFields(), true);
		
		$sectors = '';
		if($dynamic['sAdmin']){
			$sectors = $sectors.'Administration, Support and IT, ';
		}
		if($dynamic['sArboriculture']){
			$sectors = $sectors.'Arboriculture, ';
		}
		if($dynamic['sCommunity']){
			$sectors = $sectors.'Community and Volunteers, ';
		}
		if($dynamic['sCountryside']){
			$sectors = $sectors.'Countryside, ';
		}
		if($dynamic['sEcology']){
			$sectors = $sectors.'Ecology, ';
		}
		if($dynamic['sEnvironmentEducation']){
			$sectors = $sectors.'Environmental and Outdoor Education, ';
		}
		if($dynamic['sFieldwork']){
			$sectors = $sectors.'Fieldwork and Research, ';
		}
		if($dynamic['sForestry']){
			$sectors = $sectors.'Forestry, ';
		}
		if($dynamic['sHorticulture']){
			$sectors = $sectors.'Horticulture, ';
		}
		if($dynamic['sPolicy']){
			$sectors = $sectors.'Policy and Advice, ';
		}
		if($dynamic['sPractical']){
			$sectors = $sectors.'Practical Land Management, ';
		}
		if($dynamic['sPublicity']){
			$sectors = $sectors.'Publicity and Fundraising, ';
		}
		if($dynamic['sRights']){
			$sectors = $sectors.'Rights of Way, ';
		}
		if($dynamic['sSustainability']){
			$sectors = $sectors.'Sustainability, ';
		}
		if($dynamic['sVisitors']){
			$sectors = $sectors.'Visitor Management, ';
		}
		if($dynamic['sWildlife']){
			$sectors = $sectors.'Wildlife, ';
		}
		
		$sectors = substr($sectors, 0, -2);
		
		$data = array();
		$data[] = $dynamic['contactName'];	
		$data[] = $dynamic['contactEmail'];	  
		$data[] = $dynamic['contactOrganisation'];
		$data[] = $Organisation->name();
		$data[] = $dynamic['volunteerOpportunities'];
		$data[] = $dynamic['apprenticeshipOpportunities'];
		$data[] = $sectors;
		if($dynamic['contactName']){
			fputcsv($output, $data);	
		}

	}
    
	fclose($output);  
	die($output);
		
	$message = $HTML->success_message('Organisations merged. Return to %sOrganisations%s', '<a href="'.$API->app_path() .'/organisations/">', '</a>'); 
    
  }