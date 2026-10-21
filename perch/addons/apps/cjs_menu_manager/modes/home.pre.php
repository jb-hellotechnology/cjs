<?php

$HTML 		= $API->get('HTML');
$Form 		= $API->get('Form');
$Template 	= $API->get('Template');

$cjsMenus = new CJS_Menu_Manager_Menus($API);

$cjsmenus = array();

$cjsmenus = $cjsMenus->all();