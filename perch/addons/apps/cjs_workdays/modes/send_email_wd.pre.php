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
      fputcsv($output, array('Organisation Name', 'Org ID', 'Contact Name', 'Contact Job Title', 'Contact Email', 'Do Not Contact', 'Title', 'Quick Link', 'Advert Type', 'Advert Cost', 'Check Date', 'Status'));

      // fetch the data
      $query = "SELECT perch3_organisations_wd.name, perch3_organisations_wd.organisationID, perch3_organisations_wd.contactName, perch3_organisations_wd.contactJobTitle, perch3_organisations_wd.contactEmail, perch3_organisations_wd.doNotContact, perch3_workdays.Title, perch3_workdays.workdayID, perch3_workdays.advertType, perch3_workdays.advertCost, perch3_workdays.advertStatus FROM perch3_workdays, perch3_organisations_wd WHERE (perch3_workdays.checkDate>='$From' AND perch3_workdays.checkDate<='$To' AND perch3_workdays.freeAd<>'1') AND perch3_workdays.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_workdays.organisationID";

      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
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
      fputcsv($output, array('Organisation Name', 'Org ID', 'Contact Name', 'Contact Job Title', 'Contact Email', 'Do Not Contact', 'Title', 'Quick Link', 'Advert Type', 'Advert Cost', 'Check Date', 'Status'));

      // fetch the data
      $query = "SELECT perch3_organisations_wd.name, perch3_organisations_wd.organisationID, perch3_organisations_wd.contactName, perch3_organisations_wd.contactJobTitle, perch3_organisations_wd.contactEmail, perch3_organisations_wd.doNotContact, perch3_workdays.Title, perch3_workdays.workdayID, perch3_workdays.advertType, perch3_workdays.advertCost, perch3_workdays.advertStatus FROM perch3_workdays, perch3_organisations_wd WHERE (perch3_workdays.checkDate>='$From' AND perch3_workdays.checkDate<='$To' AND perch3_workdays.freeAd='1') AND perch3_workdays.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_workdays.organisationID";

      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
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
      fputcsv($output, array('Organisation Name', 'Org ID', 'Contact Name', 'Contact Job Title', 'Contact Email', 'Do Not Contact', 'Title', 'Quick Link', 'Advert Type', 'Advert Cost', 'Check Date', 'Status'));
		
      // fetch the data
      $query = "SELECT perch3_organisations_wd.name,perch3_organisations_wd.organisationID,perch3_organisations_wd.contactName,perch3_organisations_wd.contactJobTitle,
    perch3_organisations_wd.contactEmail,perch3_organisations_wd.doNotContact,perch3_workdays.Title,perch3_workdays.workdayID,perch3_workdays.advertType,perch3_workdays.advertCost,perch3_workdays.checkDate,perch3_workdays.advertStatus 
    FROM perch3_workdays, perch3_organisations_wd WHERE perch3_workdays.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_workdays.organisationID";
      
      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
        fputcsv($output, $row);
      }

      fclose($fp);
      
      exit();
      
      ob_end_flush;
      
    }
    
    
  }