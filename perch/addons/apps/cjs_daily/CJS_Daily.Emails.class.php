<?php

class CJS_Daily_Emails extends PerchAPI_Factory
{
  protected $table     = 'emails';
  protected $pk        = 'emailID';
  protected $singular_classname = 'CJS_Daily_Email';
  
  protected $default_sort_column = 'scheduledDate';
  
  // THE STRUCTURE OF THE DATABASE TABLE
  public $static_fields = array('emailID', 'subject', 'scheduledDate', 'campaignID', 'status', 'emailDynamicFields');	   
  
  public function all($Paging=false)
  {
      if ($Paging && $Paging->enabled()) {
          $sql = $Paging->select_sql();
      }else{
          $sql = 'SELECT';
      }
  
      $sql .= ' *
              FROM ' . $this->table;
  
      if (isset($this->default_sort_column)) {
          $sql .= ' ORDER BY ' . $this->default_sort_column . ' DESC';
      }
  
      if ($Paging && $Paging->enabled()) {
          $sql .=  ' '.$Paging->limit_sql();
      }
  
      $results = $this->db->get_rows($sql);
  
      if ($Paging && $Paging->enabled()) {
          $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
      }
  
      return $this->return_instances($results);
  }
  
}