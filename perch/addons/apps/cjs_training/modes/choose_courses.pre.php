<?php

  $Events = new CJS_Training_Events($API);
  $ShortCourses = new CJS_Training_Short_Courses($API);

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');
