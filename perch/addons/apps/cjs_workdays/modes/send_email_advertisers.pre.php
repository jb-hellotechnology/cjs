<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Workdays = new CJS_Workdays_Work_Days($API);
  $VOrgs = new CJS_Workdays_Vol_Orgs($API);
  $Surveys = new CJS_Workdays_Surveys($API);

  if($_POST){

    header("Content-Type: application/csv"); 
    header("Content-disposition: attachment; filename=emails.csv");

    $output = fopen('php://output', 'w');

    // output the column headings
    fputcsv($output, array('Contact Name', 'Organisation Name', 'Contact Job Title', 'Contact Email', 'Do Not Contact', 'Org ID', 'Vol Orgs', 'Workdays', 'Surveys'));

    // fetch the data
    $query = "SELECT perch3_organisations_wd.contactName, perch3_organisations_wd.name, perch3_organisations_wd.contactJobTitle, perch3_organisations_wd.contactEmail, 
    perch3_organisations_wd.doNotContact,perch3_organisations_wd.organisationID FROM perch3_organisations_wd ORDER BY perch3_organisations_wd.name ASC";
    
    $data = $Workdays->export_rows($query);

    // loop over the rows, outputting them
    foreach($data as $row){ 
      $organisationID = $row['organisationID'];
      $rows = count($VOrgs->vorgs_by_organisation($organisationID));
      if($rows>0){
        $volorgs = 'YES';
      }else{
        $volorgs = 'NO';
      }
      $rows = count($Workdays->workdays_by_organisation($organisationID));
      if($rows>0){
        $workdays = 'YES';
      }else{
        $workdays = 'NO';
      }
      $rows = count($Surveys->surveys_by_organisation($organisationID));
      if($rows>0){
        $surveys = 'YES';
      }else{
        $surveys = 'NO';
      }
      array_push($row, $volorgs, $workdays, $surveys);
      fputcsv($output, $row);
    }

    fclose($fp);
    
  }