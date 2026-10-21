<?php

  ini_set('display_errors', 1); 
  ini_set('display_startup_errors', 1); 
  error_reporting(E_ALL);

  function generateRandomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  $Workdays = new CJS_Workdays_Work_Days($API);

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  if ($Form->submitted()){
    
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
          mkdir("uploads/$rand", 0755);
          chmod("uploads/$rand", 0755);
          move_uploaded_file($_FILES['fileToUpload']['tmp_name'],"uploads/$rand/" . $_FILES["fileToUpload"]["name"]);
        
          $csv_file = "uploads/$rand/" . $_FILES["fileToUpload"]["name"];
          $csvfile = fopen($csv_file, 'r');

          $i = 0;  

          while (!feof($csvfile)) {

            // PREPARE FILE DATA
            $csv_data[] = fgets($csvfile);

            // GET CSV ROW
            $csv_array = str_getcsv($csv_data[$i],',','"');

            // CREATE DATA ARRAY
            $data = array();
            
            $data['Title'] = $csv_array[0];
            $data['startDate'] = $csv_array[1];
            $data['startText'] = $csv_array[2];
            $data['displayDate'] = $csv_array[3];
            $data['region'] = $csv_array[4];
            $data['location'] = $csv_array[5];
            $data['provider'] = $csv_array[6];
            $data['advertTelephone'] = $csv_array[7];
            $data['advertEmail'] = $csv_array[8];
            $data['advertEmailSub'] = $csv_array[9];
            $data['advertWebsite'] = $csv_array[10];
            $data['advertWebsiteShort'] = $csv_array[11];
            $data['advertDisplay'] = $csv_array[12];
            $data['logo'] = $csv_array[13];
            $data['logoAlt'] = $csv_array[14];
            $data['displayAd'] = $csv_array[15];
            $data['bannerAd'] = $csv_array[16];
            $data['bannerAdPos'] = $csv_array[17];
            $data['organisationID'] = $csv_array[18];
            $data['organisationName'] = $csv_array[19];
            $data['contactName'] = $csv_array[20];
            $data['contactTelephone'] = $csv_array[21];
            $data['contactEmail'] = $csv_array[22];
            $data['firstOnline'] = $csv_array[23];
            $data['checkDate'] = $csv_array[24];
            $data['dateUpdated'] = $csv_array[25];
            $data['advertType'] = $csv_array[26];
            $data['advertPaid'] = $csv_array[27];
            $data['advertCost'] = $csv_array[28];
            $data['freeAd'] = $csv_array[29];
            $data['advertStatus'] = $csv_array[30];
            $data['workdayDynamicFields'] = '';

            // INSERT QUERY HERE
            $new_workday = $Workdays->create($data);

            $i++;

          }

          fclose($csvfile);

        }
      
      $message = $HTML->success_message('Your file has been imported. Return to %sWork Days%s', '<a href="'.$API->app_path() .'/work_days/">', '</a>'); 
    }
  }