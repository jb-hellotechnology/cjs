<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 
*/

  function generateRandomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  $Subscribers = new CJS_Subscribers_Subscribers($API);

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  if ($Form->submitted()){
    
    $Subscribers->delete_all();
    
    if(isset($_FILES['fileToUpload'])){

      $errors= array();
      $file_name = $_FILES['fileToUpload']['name'];
      $file_size =$_FILES['fileToUpload']['size'];
      $file_tmp =$_FILES['fileToUpload']['tmp_name'];
      $file_type=$_FILES['fileToUpload']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));
      
      $extensions= array("csv","CSV");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a CSV file.";
      }
      
      if(empty($errors)==true){
          $rand = generateRandomString(10);
          mkdir("files/$rand", 0755);
          chmod("files/$rand", 0755);
          move_uploaded_file($_FILES['fileToUpload']['tmp_name'],"files/$rand/" . $_FILES["fileToUpload"]["name"]);
        
          $csv_file = "files/$rand/" . $_FILES["fileToUpload"]["name"];
          $csvfile = fopen($csv_file, 'r');

          $i = 0;  

          while (!feof($csvfile)) {

            // PREPARE FILE DATA
            $csv_data[] = fgets($csvfile);

            // GET CSV ROW
            $csv_array = str_getcsv($csv_data[$i],',','"');

            // CREATE DATA ARRAY
            $data = array();
            
            $data['username'] = $csv_array[0]; 
            $data['password'] = $csv_array[1]; 
            $data['password2'] = $csv_array[2]; 

            // INSERT QUERY HERE
            if($csv_array[0]<>'Username' AND $csv_array[0]<>''){
            	$new_subscriber = $Subscribers->create($data);
            }


            $i++;

          }

          fclose($csvfile);

        }
        
        $message = $HTML->success_message('Subscribers have been imported'); 
    }
  }