<?php

class CJS_Daily_Advertiser_Messages extends PerchAPI_Factory
{
  protected $table     = 'advertiser_messages';
	protected $pk        = 'advertiser_messageID';
	protected $singular_classname = 'CJS_Daily_Advertiser_Message';
	
	protected $default_sort_column = 'advertiser_messageID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('advertiser_messageID', 'displayf', 'date', 'displayj', 'displayc', 'displayv', 'displayw', 'advertiser_messageDynamicFields');	      
  
  public function get_message_id(){
    $data = $this->db->get_rows('SELECT * FROM perch3_advertiser_messages ORDER BY advertiser_messageID DESC LIMIT 1 ');
    return $data[0]['advertiser_messageID'];
  }
  
  public function get_advertiser_messages(){
    $data = $this->db->get_rows('SELECT * FROM perch3_advertiser_messages ORDER BY advertiser_messageID DESC LIMIT 1 ');
    return $data;
  }
}