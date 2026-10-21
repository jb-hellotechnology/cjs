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
		$query = "SELECT perch3_organisations_t.contactEmail, perch3_organisations_t.contactName, perch3_organisations_t.doNotContact, perch3_long_courses.provider, perch3_long_courses.categoryOne, perch3_long_courses.longTitle, perch3_long_courses.organisationID, perch3_long_courses.longID, perch3_long_courses.displayUntil FROM perch3_long_courses, perch3_organisations_t WHERE perch3_long_courses.checkDate>='$From' AND perch3_long_courses.checkDate<='$To' AND perch3_long_courses.freeAd='1' AND perch3_long_courses.advertStatus='pub' AND perch3_long_courses.categoryOne='distance-learning' AND perch3_long_courses.organisationID=perch3_organisations_t.organisationID AND (perch3_long_courses.displayUntil>='$today' OR perch3_long_courses.displayUntil='0000-00-00') AND perch3_long_courses.freeAd='$_POST[free]'";
		
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
		$query = "SELECT perch3_organisations_t.contactEmail,perch3_organisations_t.contactName,perch3_long_courses.provider,perch3_long_courses.longTitle,perch3_long_courses.organisationID,perch3_long_courses.longID FROM perch3_long_courses, perch3_organisations_t WHERE perch3_long_courses.advertStatus='sus' AND perch3_long_courses.categoryOne='distance-learning' AND perch3_long_courses.organisationID=perch3_organisations_t.organisationID";
		
		$data = $Training->export_rows($query);
      
		foreach($data as $row){
			fputcsv($output, $row);
		}
		
		fclose($fp);
		
		exit();
		
		ob_end_flush;
			
	}
	
}