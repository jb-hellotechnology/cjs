<?php

class CJS_Menu_Manager_Menus_Items extends PerchAPI_Factory
{
  protected $table     = 'menus_items';
  protected $pk        = 'menuItemID';
  protected $singular_classname = 'CJS_Menu_Manager_Menu_Item';	
  protected $default_sort_column = 'order';
  protected $default_sort_direction = 'ASC';
	
  // THE STRUCTURE OF THE DATABASE TABLE
  public $static_fields = array('menuItemID', 'menuID', 'parentItem', 'pagePath', 'altURL', 'pageTitle', 'linkActive', 'openNewTab', 'alignLeft', 'alignRight', 'bold', 'italic', 'lineBreak', 'menuitemDynamicFields');
  
  public function get_by_menu($menuID){
	  
	  $data = $this->db->get_rows('SELECT * FROM perch3_menus_items WHERE menuID="'.$menuID.'" AND parentItem="0" ORDER BY itemOrder ASC');
	  return $data;
	  
  }
  
  public function get_children($menuItemID){
	  
	  $data = $this->db->get_rows('SELECT * FROM perch3_menus_items WHERE parentItem="'.$menuItemID.'" ORDER BY itemOrder ASC');
	  return $data;
	  
  }
  
  public function set_order($itemID,$order){
	  
	  $data = $this->db->get_rows('UPDATE perch3_menus_items SET itemOrder="'.$order.'" WHERE menuItemID="'.$itemID.'"');
	  echo 'UPDATE perch3_menus_items SET itemOrder="'.$order.'" WHERE menuItemID="'.$itemID.'"';
	  
  }
  
}