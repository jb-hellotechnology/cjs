<?php

$HTML 		= $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

$Training = new CJS_Training_Events($API);

if($_POST){
	
	header("Content-Type: application/csv"); 
	header("Content-disposition: attachment; filename=emails.csv");
	
	$output = fopen('php://output', 'w');
	    
    if($_POST['check']=='1'){
		
		$From = $Form->get_date('from');
		$To = $Form->get_date('to');
		
		$today = date('Y-m-d');
		
		// output the column headings
		fputcsv($output, array('Provider', 'Contact Name', 'Do Not Contact', 'Office ID', 'Contact Email', 'Title', 'Category', 'AdvertID', 'Start Text', 'First Online', 'Advert Status'));
		
		// fetch the data
		$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.contactName, perch3_organisations_t.doNotContact,perch3_organisations_t.organisationID,perch3_organisations_t.contactEmail,perch3_short_courses.shortTitle,perch3_short_courses.categoryOne, perch3_short_courses.shortID,perch3_short_courses.startText,perch3_short_courses.firstOnline,perch3_short_courses.advertStatus FROM perch3_short_courses, perch3_organisations_t WHERE perch3_short_courses.checkDate>='$From' AND perch3_short_courses.checkDate<='$To' AND perch3_short_courses.freeAd='1' AND perch3_short_courses.advertStatus='pub' AND perch3_short_courses.organisationID=perch3_organisations_t.organisationID";
		
		$data = $Training->export_rows($query);
			
	}
	
	if($_POST['categories']=='1'){
		
		$category = $_POST['category'];
		
		// output the column headings
		fputcsv($output, array('Provider', 'Contact Name', 'Do Not Contact', 'Office ID', 'Contact Email', 'Title', 'Category', 'AdvertID', 'Start Text', 'First Online', 'Advert Status'));
		
		// fetch the data
		$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.contactName, perch3_organisations_t.doNotContact,perch3_organisations_t.organisationID,perch3_organisations_t.contactEmail,perch3_short_courses.shortTitle,perch3_short_courses.categoryOne, perch3_short_courses.shortID,perch3_short_courses.startText,perch3_short_courses.firstOnline,perch3_short_courses.advertStatus FROM perch3_short_courses, perch3_organisations_t WHERE perch3_short_courses.categoryOne='$category' AND perch3_short_courses.organisationID=perch3_organisations_t.organisationID";
		
		$data = $Training->export_rows($query);
			
	}
	
	if($_POST['reminder']=='1'){
		
		// output the column headings
		fputcsv($output, array('Provider', 'Contact Name', 'Do Not Contact', 'Office ID', 'Contact Email', 'Title', 'Category', 'AdvertID', 'Start Text', 'First Online', 'Advert Status'));
		
		// fetch the data
		if($_POST['filter']=='All'){
			$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.contactName, perch3_organisations_t.doNotContact,perch3_organisations_t.organisationID,perch3_organisations_t.contactEmail,perch3_short_courses.shortTitle,perch3_short_courses.categoryOne, perch3_short_courses.shortID,perch3_short_courses.startText,perch3_short_courses.firstOnline,perch3_short_courses.advertStatus FROM perch3_short_courses, perch3_organisations_t WHERE perch3_short_courses.organisationID=perch3_organisations_t.organisationID GROUP BY perch3_short_courses.provider";
		}else{
			$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.contactName, perch3_organisations_t.doNotContact,perch3_organisations_t.organisationID,perch3_organisations_t.contactEmail,perch3_short_courses.shortTitle,perch3_short_courses.categoryOne, perch3_short_courses.shortID,perch3_short_courses.startText,perch3_short_courses.firstOnline,perch3_short_courses.advertStatus FROM perch3_short_courses, perch3_organisations_t WHERE perch3_short_courses.organisationID=perch3_organisations_t.organisationID AND perch3_short_courses.advertStatus='$_POST[filter]' GROUP BY perch3_short_courses.provider";
		}

		$data = $Training->export_rows($query);
			
	}
	
	if($_POST['undated']=='1'){
		
		$Category = $_POST['category'];
		
		// output the column headings
		fputcsv($output, array('Provider', 'Contact Name', 'Do Not Contact', 'Office ID', 'Contact Email', 'Title', 'Category', 'AdvertID', 'Start Text', 'First Online', 'Advert Status'));
		
		// fetch the data
		$query = "SELECT DISTINCT perch3_organisations_t.name, perch3_organisations_t.contactName, perch3_organisations_t.doNotContact, perch3_organisations_t.organisationID, perch3_organisations_t.contactEmail, perch3_short_courses.shortTitle, perch3_short_courses.categoryOne, perch3_short_courses.shortID, perch3_short_courses.startText, perch3_short_courses.firstOnline, perch3_short_courses.advertStatus 
		FROM perch3_short_courses, perch3_organisations_t 
		WHERE perch3_short_courses.startDate='0000-00-00' 
		AND perch3_short_courses.organisationID=perch3_organisations_t.organisationID";
		
		$data = $Training->export_rows($query);
			
	}
	
	foreach($data as $row){
		fputcsv($output, $row);
	}
	
	fclose($fp);
	
	exit();
	
	ob_end_flush;
	
}