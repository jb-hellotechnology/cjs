<?php

  $HTML 		= $API->get('HTML');
  $Form 		= $API->get('Form');
  $Template = $API->get('Template');

  $Emails = new CJS_Daily_Emails($API);
  $Templates = new CJS_Daily_Emails_Templates($API);

  $templates = array();

  $templates = $Templates->all();