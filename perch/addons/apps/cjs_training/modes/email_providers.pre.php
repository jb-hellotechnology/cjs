<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Training = new CJS_Training_Events($API);
  
if($_POST){
    
    if($_POST['checkreminder']=='1'){
		
		$From = $Form->get_date('from');
		$To = $Form->get_date('to');
		
		$today = date('Y-m-d');
		
		header("Content-Type: application/csv"); 
		header("Content-disposition: attachment; filename=emails.csv");
		
		$output = fopen('php://output', 'w');
		
		// output the column headings
		fputcsv($output, array('ContactEmail', 'ContactName', 'Do Not Contact', 'Provider', 'Category', 'Title', 'OrganisationID', 'AdvertID', 'Display Until'));
		
		// fetch the data
		$query = "SELECT perch3_organisations_t.contactEmail,perch3_organisations_t.contactName, perch3_organisations_t.doNotContact,perch3_providers.providerName,perch3_providers.advertType,perch3_providers.advertCost,perch3_providers.organisationID, perch3_providers.providerID, perch3_providers.displayUntil
    FROM perch3_providers, perch3_organisations_t WHERE perch3_providers.checkDate>='$From' AND perch3_providers.checkDate<='$To' AND perch3_providers.advertStatus='pub' AND perch3_organisations_t.organisationID=perch3_providers.organisationID AND perch3_providers.freeAd='$_POST[free]'";
		
		$data = $Training->export_rows($query);
      
		foreach($data as $row){
			fputcsv($output, $row);
		}
		
		fclose($fp);
		
		exit();
		
		ob_end_flush;
			
	}
	
	if($_POST['readvertise']=='1'){
		
		$CheckDateG = $_POST['CheckDateG'];
		$CheckDateL = $_POST['CheckDateL'];
		
		$CG = explode("/",$CheckDateG);
		$C1 = "$CG[2]-$CG[1]-$CG[0]";
		
		$CL = explode("/",$CheckDateL);
		$C2 = "$CL[2]-$CL[1]-$CL[0]";
		
		header("Content-Type: application/csv"); 
		header("Content-disposition: attachment; filename=emails.csv");
		
		$output = fopen('php://output', 'w');
		
		// output the column headings
		fputcsv($output, array('ContactEmail', 'ContactName', 'Provider', 'Title', 'OrganisationID', 'AdvertID'));
		
		// fetch the data
		$query = "SELECT perch3_organisations_t.contactEmail,perch3_organisations_t.contactName,perch3_providers.providerName,perch3_providers.organisationID,perch3_providers.providerID 
    FROM perch3_providers, perch3_organisations_t WHERE perch3_providers.advertStatus='sus' AND perch3_providers.organisationID=perch3_organisations_t.organisationID GROUP BY perch3_providers.organisationID";
		
		$data = $Training->export_rows($query);
      
		foreach($data as $row){
			fputcsv($output, $row);
		}
		
		fclose($fp);
		
		exit();
		
		ob_end_flush;
			
	}
	
}