<?php

class CJS_Daily_Sectors extends PerchAPI_Factory
{
  protected $table     = 'sectors';
	protected $pk        = 'sectorID';
	protected $singular_classname = 'CJS_Daily_Sector';
	
	protected $default_sort_column = 'sectorID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('sectorID', 'name', 'abbreviation', 'sectorsDynamicFields');	      

}