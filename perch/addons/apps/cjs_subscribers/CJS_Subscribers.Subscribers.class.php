<?php

class CJS_Subscribers_Subscribers extends PerchAPI_Factory
{
 	protected $table     = 'subscribers';
	protected $pk        = 'subscriberID';
	protected $singular_classname = 'CJS_Subscribers_Subscriber';
	
	protected $default_sort_column = 'username';

	public $static_fields = array('subscriberID','username', 'password', 'password2', 'subscriberDynamicFields');	
	
	public function delete_all(){
		
		$this->db->execute("TRUNCATE perch3_subscribers");
		
	}
	
	public function logins($id,$period){
		if($period=='week'){
			$timestamp = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d')-7, date('Y')));
		}elseif($period='month'){
			$timestamp = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m')-1, date('d'), date('Y')));
		}else{
			$timestamp = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d'), date('Y')-1));	
		}
		
		$result = $this->db->get_rows("SELECT * FROM perch3_subscribers_login WHERE subscriberID='$id' AND dateTime>='$timestamp'");
		return(count($result));
	}
	
	public function handle_login($data){
		
		$username = $data->data['username'];
		$password = $data->data['password'];
		$result = $this->db->get_row("SELECT * FROM perch3_subscribers WHERE username='".$username."' AND (password='".$password."' OR password2='".$password."') ORDER BY subscriberID DESC LIMIT 1");
		if($result){
			$data = $this->db->execute("INSERT INTO perch3_subscribers_login (subscriberID) VALUES ('$result[subscriberID]')");	
		}else{
			return 'error';
		}
		
	}
  
}