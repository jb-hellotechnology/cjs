<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
    
include('CJS_MenuManager.Menu.class.php');
include('CJS_MenuManager.Menus.class.php');
include('CJS_MenuManager.Menu_Item.class.php');
include('CJS_MenuManager.Menus_Items.class.php');

function cjs_menu_manager_menu($slug){

  $API  = new PerchAPI(1.0, 'menu_manager');
  $menuClass = new CJS_Menu_Manager_Menus($API);
  $menuItemsClass = new CJS_Menu_Manager_Menus_Items($API);
  
  $menu = $menuClass->get_menu($slug);
  $menuItems = $menuItemsClass->get_by_menu($menu['menuID']);
  
  $Template = $API->get('Template');
  $Template->set('cjs_menu_manager/item.html', 'cjs');

  
  $i = 0;
  foreach($menuItems as $item){

	  $menuSubItems = $menuItemsClass->get_children($item['menuItemID']);
	  if(count($menuSubItems)>0){
		  
		  $subItems = $Template->render_group($menuSubItems, true);
		  $menuItems[$i]['subitems'] = $subItems;
		  
	  }
	  
	  $i++;
	  
  }

  $html = $Template->render_group($menuItems, true);
  $html = $Template->apply_runtime_post_processing($html, $menuItems);  

  echo $html;

}

function cjs_menu_manager_sub_menu($slug){

  $API  = new PerchAPI(1.0, 'menu_manager');
  $menuClass = new CJS_Menu_Manager_Menus($API);
  $menuItemsClass = new CJS_Menu_Manager_Menus_Items($API);
  
  $menu = $menuClass->get_menu($slug);
  $menuItems = $menuItemsClass->get_by_menu($menu['menuID']);
  
  $Template = $API->get('Template');
  $Template->set('cjs_menu_manager/sub-item.html', 'cjs');

  $html = '';
  
  $i = 0;
  foreach($menuItems as $item){
	  $menuSubItems = $menuItemsClass->get_children($item['menuItemID']);
	  if(count($menuSubItems)>0){
		  
		  $html = $Template->render_group($menuSubItems, true);
		  $html = $Template->apply_runtime_post_processing($html, $menuItems);
		  break;
	  }
	  
	  $i++;
	  
  }

  echo $html;

}
