<?php

class CJS_Workdays_Work_Days extends PerchAPI_Factory
{

  /*
   * Query helpers. Anything that came from a visitor (a URL, a search box, a form)
   * has to go through one of these before it is put into SQL.
   *
   * cjs_esc()   escapes a value for use inside quotes that are already in the query,
   *             e.g. WHERE title='$title'. Prefer $this->db->pdb() where you can, which
   *             adds the quotes as well.
   * cjs_ident() lets a column name through only if it is a plain identifier. Search
   *             forms post column names (rScotland, sEco...), so without this a posted
   *             value could carry extra SQL. Anything else becomes NULL, which matches
   *             nothing.
   */
  protected function cjs_esc($value)
  {
    if (is_array($value)) $value = '';
    $quoted = $this->db->pdb((string)$value);
    return ($quoted === false) ? '' : substr($quoted, 1, -1);
  }

  protected function cjs_ident($name)
  {
    return preg_match('/^[A-Za-z_][A-Za-z0-9_]{0,63}$/', (string)$name) ? $name : 'NULL';
  }
  protected $table     = 'workdays';
	protected $pk        = 'workdayID';
	protected $singular_classname = 'CJS_Workdays_Work_Day';
	
	protected $default_sort_column = 'workdayID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('workdayID', 'Title', 'startDate', 'startText', 'displayDate', 'region', 'location', 'provider', 
                                'advertTelephone', 'advertEmail', 'advertEmailSub', 'advertWebsite', 'advertWebsiteShort', 'advertDisplay', 'logo', 'logoAlt', 'displayAd', 'bannerAd',
                                'bannerAdPos', 'organisationID', 'organisationName', 'contactName', 'contactTelephone', 'contactEmail', 
                                'firstOnline', 'checkDate', 'dateUpdated', 'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus', 'workdayDynamicFields');	      

  public function get_workdays($region,$object){
    $regions = [['scotland','Scotland'],['wales','Wales'],['northern-ireland','Northern Ireland'],
                ['england','England'],['east-anglia','East Anglia'],['midlands','Midlands'],
                ['north-east','North East'],['north-west','North West'],['south','South'],['south-west','South West']];
    foreach($regions as $dbregion){
      if ($dbregion[0] == $region){
        $desired_region = $dbregion[1];
      }
    }
    $today = date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_workdays WHERE region='.$this->db->pdb($desired_region).' AND startDate>='.$this->db->pdb($today).' AND advertStatus="Published" ORDERED BY workdayID DESC');
//     $list = [];

//     foreach ($object as $element){
//       if ($element->region()==$desired_region && $element->startDate()>=$today && $element->advertStatus()=='Published') $list[]=$element;
//     }
//     return $list;
    
  }
  
  public function pending(){
    $data = $this->db->get_rows('SELECT * FROM perch3_workdays WHERE advertStatus="pen"');
    return $data;
  }
  
  public function published(){
    $data = $this->db->get_rows('SELECT * FROM perch3_workdays WHERE advertStatus="pub"');
    return $data;
  }
  
  public function suspended(){
    $data = $this->db->get_rows('SELECT * FROM perch3_workdays WHERE advertStatus="sus"');
    return $data;
  }
  
  public function archive(){
    $data = $this->db->get_rows('SELECT * FROM perch3_workdays WHERE advertStatus="arc"');
    return $data;
  }
  
  public function update_advert($data,$id){
	  $dates = explode("-",$data['checkDate']);
	  $date = "$dates[2]-$dates[1]-$dates[0]";
	  if($data['useCheckDate']=='Yes'){
		  $this->db->execute('UPDATE perch3_workdays SET checkDate='.$this->db->pdb($date).' WHERE workdayID='.$this->db->pdb($id).'');
	  }
	  $this->db->execute('UPDATE perch3_workdays SET advertStatus='.$this->db->pdb($data['advertStatus']).' WHERE workdayID='.$this->db->pdb($id).'');
	  
  }

  public function workdays_by_organisation($organisationID){
    $data = $this->db->get_rows('SELECT * FROM perch3_workdays WHERE organisationID='.$this->db->pdb($organisationID).'');
    return $data;
  }
  
  public function export_rows($query){
    $data = $this->db->get_rows($query);
    return $data;
  }
  
  public function export_monthly($later,$less,$region){
    $data = $this->db->get_rows('SELECT * FROM perch3_workdays WHERE region='.$this->db->pdb($region).' AND firstOnline>='.$this->db->pdb($later).' AND firstOnline<='.$this->db->pdb($less).'');
    return $data;
  }
  
  public function export_monthly_workdays($query){
    $data = $this->db->get_rows($query);
    foreach($data as $data){
      if($data['logo']<>''){
        echo "<img src=\"$data[logo]\" alt=\"$data[logoAlt]\" style=\"float:right;\" />";
      }

      echo "<p><strong>$data[displayDate]</strong> $data[Title]</p>
      <p>$data[location] Organised by $data[provider]. Contact: ";
      if($data['advertTelephone']<>''){
        echo "$data[advertTelephone] ";
      }
      if($data['advertEmail']<>''){
        echo "<a href=\"mailto:$data[advertEmail]?subject=$data[advertEmailSub]\">$data[advertEmail]</a> ";
      }
      if($data['advertWebsite']<>'' AND $data['advertDisplay']=='1' AND $data['advertWebsiteShort']<>'http://'){
        echo "<a href=\"$data[AdvertWeb]\">$data[AdvertWeb]</a> ";
      }elseif($data['advertWebsite']<>'' AND $data['advertDisplay']=='0' AND $data['advertWebsite']<>'http://'){
        echo "<a href=\"$data[advertWebsite]\">$data[advertWebsite]</a> ";
      }
      $fields = json_decode($data['workdayDynamicFields'], true);
      echo "</p>";
      echo $fields[description][processed];
    }
  }
  
  public function submitVolunteerOrganisation($data){
    
    $captcha=$_POST['g-recaptcha-response'];
    $secretKey = (defined('CJS_RECAPTCHA_SECRET') ? CJS_RECAPTCHA_SECRET : '');
    $ip = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    // should return JSON with success as true
    if($responseKeys["success"]) {
	  
      $volunteerplacements = 0;
      $volunteeropps = 0;
      $workdays = 0;
      $surveys = 0;  
        
      $name = addslashes($data->data['orgName']);
      $text = addslashes($data->data['text']);
      $volunteerplacements = addslashes($data->data['volunteerplacements']);
      $volunteeropps = addslashes($data->data['volunteeropps']);
      $workdays = addslashes($data->data['workdays']);
      $surveys = addslashes($data->data['surveys']);
      $contactemail = addslashes($data->data['contactemail']);
      $contactwebsite = $this->cjs_esc($data->data['contactwebsite']);
      $region = addslashes($data->data['region']);
      $county = addslashes($data->data['county']);
      
      // Send notification email
      $to = 'ranger@countryside-jobs.com';
      
      $emailHTML = "
      <html>
      <head>
        <title>CJS Volunteer Organisation Submission</title>
      </head>
      <body>
        <h2>Volunteer Organisation Submission</h2>
        <p><strong>Organisation Name:</strong> ".stripslashes($name)."<br />
        <strong>Contact Email:</strong> ".stripslashes($contactemail)."<br />
        <strong>Website:</strong> ".htmlspecialchars($contactwebsite)."<br />
        <strong>Region:</strong> ".stripslashes($region)."<br />
        <strong>County:</strong> ".stripslashes($county)."</p>
        <p><strong>Volunteer Placements:</strong> ".($volunteerplacements ? 'Yes' : 'No')."<br />
        <strong>Volunteer Opportunities:</strong> ".($volunteeropps ? 'Yes' : 'No')."<br />
        <strong>Work Days:</strong> ".($workdays ? 'Yes' : 'No')."<br />
        <strong>Surveys:</strong> ".($surveys ? 'Yes' : 'No')."</p>
        <h3>Description</h3>
        ".stripslashes($text)."
        <p><em>Sent automatically by the website.</em></p>
      </body>
      </html>
      ";
      
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';
      $headers[] = 'From: no-reply@countryside-jobs.com';
      $headers[] = 'Reply-To: '.stripslashes($contactemail);
      
      mail($to, 'Volunteer Organisation Submission', $emailHTML, implode("\r\n", $headers));
      
      return $this->db->execute("INSERT INTO perch3_vorganisations_wd (vorgName, advertEmail, advertWebsite, region, county, cVolPlace, cVolOpps, cWorkDays, cSurveys, advertStatus, vorganisationDynamicFields) VALUES 
      ('$name', '$contactemail', '$contactwebsite', '$region', '$county', '$volunteerplacements', '$volunteeropps', '$workdays', '$surveys', 'pen', '{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"}}')");
    
    }else{
      echo 'robot';
      exit(1);
    }
  }
  
  public function submitSurvey($data){
    
    $captcha=$_POST['g-recaptcha-response'];
    $secretKey = (defined('CJS_RECAPTCHA_SECRET') ? CJS_RECAPTCHA_SECRET : '');
    $ip = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    // should return JSON with success as true
    if($responseKeys["success"]) {
        
      $title = addslashes($data->data['title']);
      $region = addslashes($data->data['region']);
      $section = strtolower(addslashes($data->data['type']));
      $provider = addslashes($data->data['organisation']);
      $contactemail = addslashes($data->data['contactemail']);
      $contactwebsite = addslashes($data->data['contactwebsite']);
      $text = addslashes($data->data['text2']);
      
      // Send notification email
      $to = 'ranger@countryside-jobs.com';
      
      $emailHTML = "
      <html>
      <head>
        <title>CJS Survey Submission</title>
      </head>
      <body>
        <h2>Survey Submission</h2>
        <p><strong>Title:</strong> ".stripslashes($title)."<br />
        <strong>Type:</strong> ".stripslashes($section)."<br />
        <strong>Organisation:</strong> ".stripslashes($provider)."<br />
        <strong>Region:</strong> ".stripslashes($region)."<br />
        <strong>Contact Email:</strong> ".stripslashes($contactemail)."<br />
        <strong>Website:</strong> ".stripslashes($contactwebsite)."</p>
        <h3>Description</h3>
        ".stripslashes($text)."
        <p><em>Sent automatically by the website.</em></p>
      </body>
      </html>
      ";
      
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';
      $headers[] = 'From: no-reply@countryside-jobs.com';
      $headers[] = 'Reply-To: '.stripslashes($contactemail);
      
      mail($to, 'Survey Submission', $emailHTML, implode("\r\n", $headers));
      
      return $this->db->execute("INSERT INTO perch3_surveys (Title, region, section, provider, advertEmail, advertWebsite, advertStatus, surveyDynamicFields) VALUES 
      ('$title', '$region', '$section', '$provider', '$contactemail', '$contactwebsite', 'pen', '{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"}}')");
    
    }else{
      echo 'robot';
      exit(1);
    }
  }
}