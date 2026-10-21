<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  if($_POST){

    ob_end_clean();
    
    $Employers = new CJS_Daily_Employers($API);
    $employers = array();

    // GET ALL EMPLOYER RECORDS
    $employers = $Employers->all();

    // CREATE CSV

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=employers.csv');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // output the column headings
    fputcsv($output, array('Employer ID', 'Name', 'Slug' ,'Logo', 'Contact Name', 'Contact Job Title', 'Contact Phone', 'Contact Email', 'Do Not Contact', 'Dynamic Fields'));

    // EXPORT DATA!
    foreach($employers AS $Employer){
      fputcsv($output, array($Employer->employerID(),$Employer->name(),$Employer->slug(),$Employer->logo(),$Employer->contactName(),$Employer->contactJobTitle(),$Employer->contactPhone(),$Employer->contactEmail(),
                            $Employer->doNotContact(),$Employer->employerDynamicFields()));
    }
    
    exit;
    
  }