<?php
    
	$Emails = new CJS_Daily_Emails($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');