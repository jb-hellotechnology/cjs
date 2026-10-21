<?php

$HTML = $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

$Scourses = new CJS_Training_Short_Courses($API);
$scourses = array();
$scourses = $Scourses->pending();

$Lcourses = new CJS_Training_Long_Courses($API);
$lcourses = array();
$lcourses = $Lcourses->pending();
$dlcourses = $Lcourses->dl_pending();

$Events = new CJS_Training_Events($API);
$events = array();
$events = $Events->pending();