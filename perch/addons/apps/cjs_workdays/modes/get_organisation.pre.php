<?php

	  $Organisations = new CJS_Workdays_Organisations($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');
   
    $Organisation = $Organisations->find($_POST['organisationID'], true);
    $details = $Organisation->to_array();
    
    $json = json_decode($details['organisationDynamicFields'],true);
    $notes = strip_tags($json['notes']['raw']);

    $arr = array('organisationName' => $details['name'], 'contactName' => $details['contactName'], 'contactJobTitle' => $details['contactJobTitle'], 'contactTelephone' => $details['contactPhone'], 'contactEmail' => $details['contactEmail'], 'notes' => $notes);

    echo json_encode($arr);