<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 
*/
    # include the API
    include('../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_menu_manager');

    # include your class files
    include('CJS_MenuManager.Menu.class.php');
    include('CJS_MenuManager.Menus.class.php');
    include('CJS_MenuManager.Menu_Item.class.php');
    include('CJS_MenuManager.Menus_Items.class.php');
    
    $cjsMenus = new CJS_Menu_Manager_Menus($API);
	$cjsMenusItems = new CJS_Menu_Manager_Menus_Items($API);
	
	$itemID = $_POST['itemID'];
    $order = $_POST['order'];
    
    $set = $cjsMenusItems->set_order($itemID,$order);
