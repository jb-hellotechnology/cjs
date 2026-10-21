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
  
  function convert_date($date){
	
	$dates = explode("/",$date);
	$date = "$dates[2]-$dates[1]-$dates[0]";
	return $date;
	
  }

  $Surveys = new CJS_Workdays_Surveys($API);

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  if ($Form->submitted()){
	  
	  $Surveys->empty_temp();
    
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
            $data['region'] = $csv_array[1];
            $data['section'] = $csv_array[2];
            $data['provider'] = $csv_array[3];
            $data['advertEmail'] = $csv_array[4];
            $data['advertWebsite'] = $csv_array[5];
            $data['advertWebsiteShort'] = $csv_array[6];
            $data['advertDisplay'] = $csv_array[7];
            $data['logo'] = $csv_array[8];
            $data['logoAlt'] = $csv_array[9];
            $data['displayAd'] = $csv_array[10];
            $data['bannerAd'] = $csv_array[11];
            $data['bannerAdPos'] = $csv_array[12];
            $data['organisationID'] = $csv_array[13];
            $data['organisationName'] = $csv_array[14];
            $data['contactName'] = $csv_array[15];
            $data['contactEmail'] = $csv_array[16];
            $data['firstOnline'] = convert_date($csv_array[17]);
            $data['checkDate'] = convert_date($csv_array[18]);
            $data['displayUntil'] = convert_date($csv_array[19]);
            $data['advertType'] = $csv_array[20];
            $data['advertPaid'] = $csv_array[21];
            $data['advertCost'] = $csv_array[22];
            $data['freeAd'] = $csv_array[23];
            $data['advertStatus'] = $csv_array[24];
            $data['dateUpdated'] = convert_date($csv_array[25]);
            $data['surveyDynamicFields'] = '{"description":{"_flang":"html","raw":"<p>'.addslashes($csv_array[26]).'<\/p>","processed":"<p>'.addslashes($csv_array[26]).'<\/p>"}}';

            // INSERT QUERY HERE
            $new_survey = $Surveys->create_temp($data);

            $i++;

          }

          fclose($csvfile);
          PerchUtil::redirect($API->app_path().'/surveys/upload_csv/temp');

        }
      
      $message = $HTML->success_message('Your file has been imported. Return to %sSurveys%s', '<a href="'.$API->app_path() .'/surveys/">', '</a>'); 
    }
  }