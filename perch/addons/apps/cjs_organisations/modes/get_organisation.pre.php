<?php

	  $Organisations = new CJS_Organisations_Orgs($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');
   
    $Organisation = $Organisations->find($_POST['organisationID'], true);
    $details = $Organisation->to_array();

    $arr = array('organisationName' => $details['name'], 'contactName' => $details['contactName'], 'contactTelephone' => $details['telephone'], 'contactEmail' => $details['email']);

    echo json_encode($arr);