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
	
	public function handle_login($data){
		
		$username = $data->data['username'];
		$password = $data->data['password'];
		$result = $this->db->get_rows("SELECT * FROM perch3_subscribers WHERE username='".$username."' AND (password='".$password."' OR password2='".$password."')");
		if(count($result)==1){
			return 'success';
			
		}else{
			return 'error';
		}
		
	}
  
}