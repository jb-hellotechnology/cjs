<?php

class CJS_Menu_Manager_Menus extends PerchAPI_Factory
{
  protected $table     = 'menus';
  protected $pk        = 'menuID';
  protected $singular_classname = 'CJS_Menu_Manager_Menu';	
  protected $default_sort_column = 'name';
  protected $default_sort_direction = 'ASC';
	
  // THE STRUCTURE OF THE DATABASE TABLE
  public $static_fields = array('menuID', 'name', 'slug');
  
  public function get_menu($slug){
	  
	  $data = $this->db->get_row('SELECT * FROM perch3_menus WHERE slug="'.$slug.'"');
	  return $data;
	  
  }
  
}