<?php

$HTML 		= $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

$Training = new CJS_Training_Events($API);

if($_POST){

	$cat = $_POST['cat'];
		
	header("Content-Type: application/csv"); 
	header("Content-disposition: attachment; filename=emails.csv");
	
	$output = fopen('php://output', 'w');

	
	if($cat=='Providers'){
		
		// output the column headings
		fputcsv($output, array('OrganisationName', 'OrganisationID', 'OrganisationContactName', 'OrganisationContactEmail', 'Do Not Contact', 'ProviderID', 'FirstOnline', 'CheckDate', 'Cost'));
	
	
		// fetch the data
		$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.organisationID,perch3_organisations_t.contactName,perch3_organisations_t.contactEmail, perch3_organisations_t.doNotContact,perch3_providers.providerName,perch3_providers.providerID,perch3_providers.firstOnline,perch3_providers.checkDate,perch3_providers.advertCost FROM perch3_providers, perch3_organisations_t WHERE perch3_providers.advertCost>'0.00' AND perch3_providers.organisationID=perch3_organisations_t.organisationID";
		
	
	}
	
	
	if($cat=='Events'){
	
		// output the column headings
		fputcsv($output, array('OrganisationName', 'OrganisationID', 'OrganisationContactName', 'OrganisationContactEmail', 'Do Not Contact', 'EventTitle', 'EventID', 'FirstOnline', 'CheckDate', 'Cost'));
	
	
		// fetch the data
		$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.organisationID,perch3_organisations_t.contactName,perch3_organisations_t.contactEmail, perch3_organisations_t.doNotContact,perch3_events.eventTitle,perch3_events.eventID,perch3_events.firstOnline,perch3_events.checkDate,perch3_events.advertCost FROM perch3_events, perch3_organisations_t WHERE perch3_events.advertCost>'0.00' AND perch3_events.organisationID=perch3_organisations_t.organisationID";

	
	}
	
	
	if($cat=='Long Courses'){
		
		// output the column headings
		fputcsv($output, array('OrganisationName', 'OrganisationID', 'OrganisationContactName', 'OrganisationContactEmail', 'Do Not Contact', 'LongCourseTitle', 'LongCourseID', 'FirstOnline', 'CheckDate', 'Cost'));
	
	
		// fetch the data
		$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.organisationID,perch3_organisations_t.contactName,perch3_organisations_t.contactEmail, perch3_organisations_t.doNotContact,perch3_long_courses.longTitle,perch3_long_courses.longID,perch3_long_courses.firstOnline,perch3_long_courses.checkDate,perch3_long_courses.advertCost FROM perch3_long_courses, perch3_organisations_t WHERE perch3_long_courses.advertCost>'0.00' AND perch3_long_courses.organisationID=perch3_organisations_t.organisationID";
		
	
	}
		
		
	if($cat=='Short Courses'){
		
		// output the column headings
		fputcsv($output, array('OrganisationName', 'OrganisationID', 'OrganisationContactName', 'OrganisationContactEmail', 'Do Not Contact', 'ShortCourseTitle', 'ShortCourseID', 'FirstOnline', 'CheckDate', 'Cost'));
	
	
		// fetch the data
		$query = "SELECT DISTINCT perch3_organisations_t.name,perch3_organisations_t.organisationID,perch3_organisations_t.contactName,perch3_organisations_t.contactEmail, perch3_organisations_t.doNotContact,perch3_short_courses.shortTitle,perch3_short_courses.shortID,perch3_short_courses.firstOnline,perch3_short_courses.checkDate,perch3_short_courses.advertCost FROM perch3_short_courses, perch3_organisations_t WHERE perch3_short_courses.advertCost>'0.00' AND perch3_short_courses.organisationID=perch3_organisations_t.organisationID";
	
	}
		
	$data = $Training->export_rows($query);
  
	foreach($data as $row){
		fputcsv($output, $row);
	}
	
	fclose($fp);
	
	exit();
	
	ob_end_flush;
		
}