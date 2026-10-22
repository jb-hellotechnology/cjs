<?php

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

  $LCourses = new CJS_Training_Long_Courses($API);

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $message = false;

  if ($Form->submitted()){

	  $LCourses->empty_temp();

    if(isset($_FILES['fileToUpload'])){

      $errors= array();
      $file_name = $_FILES['fileToUpload']['name'];
      $file_size =$_FILES['fileToUpload']['size'];
      $file_tmp =$_FILES['fileToUpload']['tmp_name'];
      $file_type=$_FILES['fileToUpload']['type'];
      $file_parts=explode('.',$_FILES['fileToUpload']['name']);
      $file_ext=strtolower(end($file_parts));

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

            // The template offered on the upload screen comes with a header row, so skip it
            // rather than importing it as a course. Files without one are unaffected.
            if ($i==0 && strtolower(trim((string)$csv_array[0]))=='longtitle') {
              $i++;
              continue;
            }

            // A row with trailing columns left off would otherwise map missing cells and fail to
            // insert, so pad it out to the full width first. Column 12 is skipped: the short URL
            // is generated below rather than read from the file.
            $csv_array = array_pad((array)$csv_array, 44, '');

            // CREATE DATA ARRAY
            $data = array();

            $data['longTitle'] = $csv_array[0];
            $data['startDate'] = convert_date($csv_array[1]);
            $data['categoryOne'] = $csv_array[2];
            $data['categoryTwo'] = $csv_array[3];
            $data['categoryThree'] = $csv_array[4];
            $data['location'] = $csv_array[5];
            $data['webinar'] = $csv_array[6];
            $data['county'] = $csv_array[7];
            $data['provider'] = $csv_array[8];
            $data['advertTelephone'] = $csv_array[9];
            $data['advertEmail'] = $csv_array[10];
            $data['advertWebsite'] = $csv_array[11];

            # GENERATE BIT.LY URL
            $apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
            $genericAccessToken = (defined('CJS_BITLY_TOKEN') ? CJS_BITLY_TOKEN : '');

            $bitlyPayload = array('long_url' => $data['advertWebsite']); // renamed
            $payload = json_encode($bitlyPayload);

            $header = array(
                'Authorization: Bearer ' . $genericAccessToken,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            );

            $ch = curl_init($apiv4);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);

            $result = json_decode($result,true);
            $data['advertWebsiteShort'] = $result['link'] ?? $data['advertWebsite']; // fall back to original URL if Bitly fails

            //$data['advertWebsiteShort'] = $csv_array[12];
            $data['advertLink'] = $csv_array[13];
            $data['logo'] = $csv_array[14];
            $data['logoAlt'] = $csv_array[15];
            $data['displayAd'] = $csv_array[16];
            $data['cShort'] = $csv_array[17];
            $data['cHnd'] = $csv_array[18];
            $data['cDiploma'] = $csv_array[19];
            $data['cNvq'] = $csv_array[20];
            $data['cHnc'] = $csv_array[21];
            $data['cMsc'] = $csv_array[22];
            $data['cDegree'] = $csv_array[23];
            $data['cBtec'] = $csv_array[24];
            $data['cFoundation'] = $csv_array[25];
            $data['cPhd'] = $csv_array[26];
            $data['cApprenticeship'] = $csv_array[27];
            $data['organisationID'] = $csv_array[28];
            $data['organisationName'] = $csv_array[29];
            $data['contactName'] = $csv_array[30];
            $data['contactTelephone'] = $csv_array[31];
            $data['contactEmail'] = $csv_array[32];
            $data['firstOnline'] = convert_date($csv_array[33]);
            $data['checkDate'] = convert_date($csv_array[34]);
            $data['dateUpdated'] = convert_date($csv_array[35]);
            $data['displayUntil'] = convert_date($csv_array[36]);
            $data['advertType'] = $csv_array[37];
            $data['advertPaid'] = $csv_array[38];
            $data['advertCost'] = $csv_array[39];
            $data['notes'] = $csv_array[40];
            $data['freeAd'] = $csv_array[41];
            $data['advertStatus'] = $csv_array[42];
            $data['longDynamicFields'] = '{"notes":{"_flang":"html","raw":"","processed":""},"description":{"_flang":"html","raw":"<p>'.$csv_array[43].'<\/p>","processed":"<p>'.$csv_array[43].'<\/p>"}}';

			if($data['longTitle']<>''){
	            // INSERT QUERY HERE
	            $new_lcourse = $LCourses->create_temp($data);
			}

            $i++;

          }

          fclose($csvfile);
          PerchUtil::redirect($API->app_path().'/long_courses/upload_csv/temp');

        }else{
	        $message = $HTML->failure_message(implode(' ', $errors));
        }
    }
  }
