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
      fputcsv($output, array('Office Name', 'Contact Name', 'Contact Email', 'Do Not Contact', 'Title', 'Quick Link', 'Advert Type', 'Advert Cost'));

      // fetch the data
      $query = "SELECT perch3_organisations_wd.name,perch3_organisations_wd.contactName,
      perch3_organisations_wd.contactEmail,perch3_organisations_wd.doNotContact,perch3_vorganisations_wd.vorgName AS 'Title',perch3_vorganisations_wd.vorganisationID,perch3_vorganisations_wd.advertType,perch3_vorganisations_wd.advertCost 
      FROM perch3_vorganisations_wd, perch3_organisations_wd WHERE (perch3_vorganisations_wd.checkDate>='$From' AND perch3_vorganisations_wd.CheckDate<='$To' AND perch3_vorganisations_wd.freeAd='1') AND perch3_vorganisations_wd.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_vorganisations_wd.organisationID";

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
      fputcsv($output, array('Office Name', 'Contact Name', 'Contact Email', 'Do Not Contact', 'Title', 'Quick Link', 'Advert Type', 'Advert Cost'));

      // fetch the data
      $query = "SELECT perch3_organisations_wd.name,perch3_organisations_wd.contactName,
      perch3_organisations_wd.contactEmail,perch3_organisations_wd.doNotContact,perch3_vorganisations_wd.vorgName AS 'Title',perch3_vorganisations_wd.vorganisationID,perch3_vorganisations_wd.advertType,perch3_vorganisations_wd.advertCost 
      FROM perch3_vorganisations_wd, perch3_organisations_wd WHERE (perch3_vorganisations_wd.checkDate>='$From' AND perch3_vorganisations_wd.CheckDate<='$To' AND perch3_vorganisations_wd.freeAd='0') AND perch3_vorganisations_wd.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_vorganisations_wd.organisationID";

      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
        fputcsv($output, $row);
      }

      fclose($fp);
      
      exit();
      
      ob_end_flush;
      
    }else if($_POST['type']){
      
      $type = $_POST['type'];
      
      header("Content-Type: application/csv"); 
      header("Content-disposition: attachment; filename=emails.csv");

      $output = fopen('php://output', 'w');

      // output the column headings
      fputcsv($output, array('Office Name', 'Contact Name', 'Contact Email', 'Do Not Contact', 'Title', 'Quick Link', 'Advert Type', 'Advert Cost'));
		
      if($type=='all'){
        $query = "SELECT perch3_organisations_wd.name,perch3_organisations_wd.contactName,
        perch3_organisations_wd.contactEmail,perch3_organisations_wd.doNotContact,perch3_vorganisations_wd.vorgName AS 'Title',perch3_vorganisations_wd.vorganisationID,perch3_vorganisations_wd.advertType,perch3_vorganisations_wd.advertCost 
        FROM perch3_vorganisations_wd, perch3_organisations_wd WHERE perch3_vorganisations_wd.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_vorganisations_wd.organisationID";
      }elseif($type=='current'){
        $query = "SELECT perch3_organisations_wd.name,perch3_organisations_wd.contactName,
        perch3_organisations_wd.contactEmail,perch3_organisations_wd.doNotContact,perch3_vorganisations_wd.vorgName AS 'Title',perch3_vorganisations_wd.vorganisationID,perch3_vorganisations_wd.advertType,perch3_vorganisations_wd.advertCost 
        FROM perch3_vorganisations_wd, perch3_organisations_wd WHERE perch3_vorganisations_wd.advertStatus='pub' AND perch3_vorganisations_wd.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_vorganisations_wd.organisationID";
      }else{
        $query = "SELECT perch3_organisations_wd.name,perch3_organisations_wd.contactName,
        perch3_organisations_wd.contactEmail,perch3_organisations_wd.doNotContact,perch3_vorganisations_wd.vorgName AS 'Title',perch3_vorganisations_wd.vorganisationID,perch3_vorganisations_wd.advertType,perch3_vorganisations_wd.advertCost 
        FROM perch3_vorganisations_wd, perch3_organisations_wd WHERE perch3_vorganisations_wd.advertStatus='sus' AND perch3_vorganisations_wd.organisationID=perch3_organisations_wd.organisationID GROUP BY perch3_vorganisations_wd.organisationID";
      }
      
      //echo $query;

      $data = $Workdays->export_rows($query);
      
      foreach($data as $row){
        fputcsv($output, $row);
      }

      fclose($fp);
      
      exit();
      
      ob_end_flush;
      
    }
    
    
  }