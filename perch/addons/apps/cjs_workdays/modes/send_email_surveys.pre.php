<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Workdays = new CJS_Workdays_Work_Days($API);
  $VOrgs = new CJS_Workdays_Vol_Orgs($API);
  $Surveys = new CJS_Workdays_Surveys($API);

  if($_POST){
    
    if($_POST['FreeOrPaid']=='Free'){
      
      $From = $Form->get_date('checkDateGreater');
      $To = $Form->get_date('checkDateLess');

      header("Content-Type: application/csv"); 
      header("Content-disposition: attachment; filename=emails.csv");

      $output = fopen('php://output', 'w');

      // output the column headings
      fputcsv($output, array('AdvertID', 'Survey Title', 'Section', 'Description', 'Advert Email', 'Advert Website', 'Office Name', 'Contact Name', 'Contact Email', 'Do Not Contact', 'Advert Status', 'Display Until'));

      // fetch the data
      $query = "SELECT perch3_surveys.surveyID,perch3_surveys.Title,perch3_surveys.section,perch3_surveys.surveyDynamicFields AS Description, perch3_surveys.advertEmail,perch3_surveys.advertWebsite, perch3_organisations_wd.name, perch3_organisations_wd.contactName, perch3_organisations_wd.contactEmail, perch3_organisations_wd.doNotContact, perch3_surveys.advertStatus, perch3_surveys.displayUntil FROM perch3_surveys, perch3_organisations_wd WHERE perch3_surveys.checkDate>='$From' AND perch3_surveys.checkDate<='$To' AND perch3_surveys.organisationID=perch3_organisations_wd.OrganisationID";
      
      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
	    $json = json_decode($row['Description'], true);
	    $row['Description'] = strip_tags($json['description']['raw']);
        fputcsv($output, $row);
      }

      fclose($fp);
      
      exit();
      
      ob_end_flush;
      
    }elseif($_POST['FreeOrPaid']=='Paid'){
      
      $From = $Form->get_date('checkDateGreater');
      $To = $Form->get_date('checkDateLess');

      header("Content-Type: application/csv"); 
      header("Content-disposition: attachment; filename=emails.csv");

      $output = fopen('php://output', 'w');

      // output the column headings
      fputcsv($output, array('AdvertID', 'Survey Title', 'Section', 'Description', 'Advert Email', 'Advert Website', 'Office Name', 'Contact Name', 'Contact Email', 'Do Not Contact', 'Advert Status', 'Display Until'));

      // fetch the data
      $query = "SELECT perch3_surveys.surveyID,perch3_surveys.Title,perch3_surveys.section,perch3_surveys.surveyDynamicFields AS Description,perch3_surveys.advertEmail,perch3_surveys.advertWebsite, perch3_organisations_wd.name, perch3_organisations_wd.contactName, perch3_organisations_wd.contactEmail, perch3_organisations_wd.doNotContact, perch3_surveys.advertStatus, perch3_surveys.displayUntil FROM perch3_surveys, perch3_organisations_wd WHERE perch3_surveys.checkDate>='$From' AND perch3_surveys.checkDate<='$To' AND perch3_surveys.freeAd='1' AND perch3_surveys.organisationID=perch3_organisations_wd.OrganisationID";

      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
	    $json = json_decode($row['Description'], true);
	    $row['Description'] = strip_tags($json['description']['raw']);
	      
        fputcsv($output, $row);
      }

      fclose($fp);
      
      exit();
      
      ob_end_flush;
      
    }else{
      
      header("Content-Type: application/csv"); 
      header("Content-disposition: attachment; filename=emails.csv");

      $output = fopen('php://output', 'w');

      // output the column headings
      fputcsv($output, array('AdvertID', 'Survey Title', 'Section', 'Description', 'Advert Email', 'Advert Website', 'Office Name', 'Contact Name', 'Contact Email', 'Do Not Contact', 'Advert Status', 'Display Until'));

      // fetch the data
      $query = "SELECT perch3_surveys.surveyID,perch3_surveys.Title,perch3_surveys.section,perch3_surveys.surveyDynamicFields AS Description,perch3_surveys.advertEmail,perch3_surveys.advertWebsite, perch3_organisations_wd.name, perch3_organisations_wd.contactName, perch3_organisations_wd.contactEmail, perch3_organisations_wd.doNotContact, perch3_surveys.advertStatus, perch3_surveys.displayUntil FROM perch3_surveys, perch3_organisations_wd  WHERE perch3_surveys.organisationID=perch3_organisations_wd.OrganisationID";
      
      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
	    $json = json_decode($row['Description'], true);
	    $row['Description'] = strip_tags($json['description']['raw']);
	      
        fputcsv($output, $row);
      }

      fclose($fp);
      
      exit();
      
      ob_end_flush;
      
    }
    
    
  }