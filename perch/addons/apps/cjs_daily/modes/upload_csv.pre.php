<?php
/*
    ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 
*/
$HTML 		= $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

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

  $volunteers = new CJS_Daily_Volunteers($API);

  if ($Form->submitted()){
	  
	$volunteers->empty_temp();
    
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
            
            $data['employerDisplay'] = '';
            $data['employerID'] = 0;
            
            $data['title'] = trim(addslashes(strip_tags($csv_array[0]))); 
            $data['employerDisplay'] = $csv_array[1];
            $data['employerID'] = $csv_array[2];
            $data['closingDate'] = convert_date($csv_array[3]);
            $data['closingDateT'] = $csv_array[4];
            $data['intDate'] = $csv_array[5];
			$data['location'] = $csv_array[6];
			$data['payBenefits'] = $csv_array[7];
			$data['tcOccasional'] = $csv_array[8];
			$data['tcOnceorTwiceWeek'] = $csv_array[9];
			$data['tcPartTime'] = $csv_array[10];
			$data['tcFullTime'] = $csv_array[11];
			$data['tcSixMonths'] = $csv_array[12];
			$data['tcSixMonthYear'] = $csv_array[13];
			$data['tcOneYearMore'] = $csv_array[14];
			$data['vidEmbedC'] = $csv_array[15];
			$data['typeRole'] = $csv_array[16];
			$data['sAdminIT'] = $csv_array[17];
			$data['sArbo'] = $csv_array[18];
			$data['sComVol'] = $csv_array[19];
			$data['sCountry'] = $csv_array[20];
			$data['sEco'] = $csv_array[21];
			$data['sEnvOE'] = $csv_array[22];
			$data['sHorti'] = $csv_array[23];
			$data['sPubF'] = $csv_array[24];
			$data['sRoW'] = $csv_array[25];
			$data['sVisMan'] = $csv_array[26];
			$data['sWiWoAnC'] = $csv_array[27];
			$data['rNational'] = $csv_array[28];
			$data['rInternational'] = $csv_array[29];
			$data['rScotland'] = $csv_array[30];
			$data['rScotlandNorth'] = $csv_array[31];
			$data['rScotlandSouth'] = $csv_array[32];
			$data['rWales'] = $csv_array[33];
			$data['rNorthernIreland'] = $csv_array[34];
			$data['rEngland'] = $csv_array[35];
			$data['rAnglia'] = $csv_array[36];
			$data['rMidlands'] = $csv_array[37];
			$data['rNorthEast'] = $csv_array[38];
			$data['rNorthWest'] = $csv_array[39];
			$data['rSouth'] = $csv_array[40];
			$data['rSouthWest'] = $csv_array[41];
			$data['rCounty'] = $csv_array[42];
			$data['accommodation'] = $csv_array[43];
			$data['expenses'] = $csv_array[44];
			$data['fTraining'] = $csv_array[45];
			$data['imageLogo'] = $csv_array[46];
			$data['imageAlt'] = $csv_array[47];
			$data['longRun'] = $csv_array[48];
			$data['deleteDate'] = convert_date($csv_array[49]);
			$data['displayUntil'] = convert_date($csv_array[50]);
			$data['freeLin'] = $csv_array[51];
			$data['displayFrom'] = convert_date($csv_array[52]);
			$data['archive'] = $csv_array[53];
			$data['advertStatus'] = $csv_array[54];
			$data['volunteerDynamicFields'] = '{"description":{"_flang":"html","raw":"<p>'.$csv_array[55].'<\/p>"}}';

			if($data['title']<>'title' AND $data['title']<>''){
	            // INSERT QUERY HERE
	            $new_volunteer = $volunteers->create_temp($data);
			}

            $i++;

          }

          fclose($csvfile);
          PerchUtil::redirect($API->app_path().'/volunteers/upload_csv/temp');

        }
    }
  }