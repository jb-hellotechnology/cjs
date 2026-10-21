<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class CJS_Training_Short_Courses extends PerchAPI_Factory
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
  protected $table     = 'short_courses';
	protected $pk        = 'shortID';
	protected $singular_classname = 'CJS_Training_Short_Course';
	
	protected $default_sort_column = 'shortID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('shortID','startDate', 'startText', 'shortTitle', 'categoryOne', 'categoryTwo', 'categoryThree',
                                'numberDays','location', 'lat', 'lng', 'county', 'provider', 'advertTelephone', 'advertEmail', 'advertWebsite', 'advertWebsiteShort',
                                'advertLink', 'freeCourse', 'logo', 'logoAlt', 'displayAd', 'organisationID', 'organisationName',
                                'contactName', 'contactTelephone', 'contactEmail' ,'firstOnline', 'checkDate', 'displayUntil', 
                                'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus', 'shortDynamicFields');	
  
  public function pending(){
    $results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE advertStatus="pen" ');
    return $results;
  }
  
  public function published(){
	$today = date('Y-m-d');
    $results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE advertStatus="pub" AND (startDate>='.$this->db->pdb($today).' OR startText LIKE "%ad hoc%" OR startText 
    LIKE "%by arrangement%" OR startText LIKE "%contact for details%" OR startText LIKE "%various%") ORDER BY startDate ASC');
    return $results;
  }
  
  public function environmental_education(){
  $today = date('Y-m-d');
    $results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE advertStatus="pub" AND (startDate>='.$this->db->pdb($today).' OR startText LIKE "%ad hoc%" OR startText LIKE "%by arrangement%" OR startText LIKE "%contact for details%" OR startText LIKE "%various%") AND categoryOne="ed" ORDER BY startDate ASC');
    return $results;
  }
  
  public function suspended(){
	  $today = date('Y-m-d');
    $results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE advertStatus="sus"');
    return $results;
  }
  
  public function by_organisation($organisationID){
	  $today = date('Y-m-d');
	$results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE organisationID='.$this->db->pdb($organisationID).' AND advertStatus="pub" AND 
	(startDate>='.$this->db->pdb($today).' OR (startText="TBC" OR startText="Ad Hoc" OR startText="Various" OR startText="Contact For Details" OR startText="By Arrangement")) ORDER BY startDate ASC');
    return $results;  
  }
  
  public function get_offices($id){
	$results = $this->db->get_rows('SELECT * FROM perch3_organisations_t WHERE master_organisationID='.$this->db->pdb($id).'');
    return $results;  
  }
  
  public function update_advert($data,$id){
	  $dates = explode("-",$data['checkDate']);
	  $date = "$dates[2]-$dates[1]-$dates[0]";
	  if($data['useCheckDate']=='Yes'){
		  $this->db->execute('UPDATE perch3_short_courses SET checkDate='.$this->db->pdb($date).' WHERE shortID='.$this->db->pdb($id).'');
	  }
	  $this->db->execute('UPDATE perch3_short_courses SET advertStatus='.$this->db->pdb($data['advertStatus']).' WHERE shortID='.$this->db->pdb($id).'');
	  
  }
  
  public function short_courses($category,$type){
    $today = date('Y-m-d');
    if($type=='dated'){
	    return $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE (categoryOne='.$this->db->pdb($category).' OR categoryTwo='.$this->db->pdb($category).' OR categoryThree='.$this->db->pdb($category).') 
	    AND advertStatus="pub" AND startDate>='.$this->db->pdb($today).' AND (displayUntil>='.$this->db->pdb($today).' OR displayUntil="0000-00-00") ORDER BY startDate ASC');
    }else{
		return $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE (categoryOne='.$this->db->pdb($category).' OR categoryTwo='.$this->db->pdb($category).' OR categoryThree='.$this->db->pdb($category).') 
		AND advertStatus="pub" AND (startText="TBC" OR startText="Ad Hoc" OR startText="Various" OR startText="Contact For Details" OR startText="By Arrangement") AND 
		(displayUntil>='.$this->db->pdb($today).' OR displayUntil="0000-00-00") ORDER BY startDate ASC');    
    }
  }
  
  public function short_webinars(){
    $today = date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE online="1" AND webinar!="1" AND advertStatus="pub" AND startDate>'.$this->db->pdb($today).' ORDER BY startDate ASC');
  }
  
  public function monthsShortCourses($start,$end,$category){
	  
  	if($start=='undated'){
	  	$results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE startDate="0000-00-00" AND advertStatus="pub" AND categoryOne='.$this->db->pdb($category).'');  
  	}else{
	  	$start = explode("-",$start);
  		$start = "$start[0]-$start[1]";
      $end = explode("-",$end);
      $end = "$end[0]-$end[1]";
      $results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE LEFT(startDate,7)>='.$this->db->pdb($start).' AND LEFT(startDate,7)<='.$this->db->pdb($end).' AND advertStatus="pub" AND categoryOne='.$this->db->pdb($category).' ORDER BY startDate ASC');
    }
    return $results;
  }
  
  public function recentAdditions($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE firstOnline>='.$this->db->pdb($start).' AND firstOnline<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function recentUpdates($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE dateUpdated>='.$this->db->pdb($start).' AND dateUpdated<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function submitShortCourseEventAdvert($data){
    $type = $data->data['type'];
    if($type=='short'){
      $startText = $this->cjs_esc($data->data['startdate']);
      $numberdays = $this->cjs_esc($data->data['runsfor']);
      $title = addslashes($data->data['title']);
      $location = addslashes($data->data['location']);
      $county = $this->cjs_esc($data->data['county']);
      $runby = addslashes($data->data['runby']);
      $online = $this->cjs_esc($data->data['online']);
      $webinar = $this->cjs_esc($data->data['webinar']);
      $contacttelephone = $this->cjs_esc($data->data['contacttelephone']);
      $contactemail = $this->cjs_esc($data->data['contactemail']);
      $contactwebsite = $this->cjs_esc($data->data['contactwebsite']);
      //$text = addslashes($data->data['text']);
      $courseCost = addslashes($data->data['cost']);
      $dates = explode("/",$startText);
      $startDate = $this->cjs_esc("$dates[2]-$dates[1]-$dates[0]");
      
      $text = $data->data['text'];
      $text = strip_tags($text,"<br>,<p>,<ul>,<ol>,<li>,<a>");
      $text = trim(preg_replace('/\s\s+/', ' ', $text));
      $text = addslashes($text);
      
      $this->db->execute("INSERT INTO perch3_short_courses (shortTitle, startDate, startText, numberDays, location, online, webinar, county, provider, advertTelephone, advertEmail, advertWebsite, courseCost, advertStatus, shortDynamicFields) VALUES 
      ('$title', '$startDate', '$startText', '$numberdays', '$location', '$online', '$webinar', '$county', '$runby', '$contacttelephone', '$contactemail', '$contactwebsite', '$courseCost', 'pen', '{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"}}')");
      
      $to = 'trainingdirectory@countrysidejobs.com'; // note the comma

	// Message
	$emailHTML = "
	<html>
	<head>
	  <title>CJS Training Submission</title>
	</head>
	<body>
	  <p>Type: Short Course<br />Title: ".addslashes($title)."<br />
	  Text: ".addslashes($text)."<br />
	  Location: ".addslashes($location)."<br />
	  County: ".addslashes($county)."<br />
	  County: ".addslashes($county)."<br />
	  Run By: ".addslashes($runby)."<br />
	  Webinar: ".addslashes($webinar)."<br />
	  Online: ".addslashes($online)."<br />
	  Contact Tel: ".addslashes($contacttelephone)."<br />
	  Contact Email: ".addslashes($contactemail)."<br />
	  Contact Website: ".addslashes($contactwebsite)."<br />
	  Course Cost: ".addslashes($courseCost)."<br />
	  Start Date: ".addslashes($startDate)."<br />
	  Number of Days: ".addslashes($numberDays)."<br />
	</body>
	</html>
	";
	
	// To send HTML mail, the Content-type header must be set
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';
	
	// Additional headers
	$headers[] = 'From: no-reply@countryside-jobs.com';
	$headers[] = 'Reply-To: '.$contactemail;
	
	// Mail it
	mail($to, $subject, $emailHTML, implode("\r\n", $headers));
      
    }else if($type=='event'){
      $startDate = $this->cjs_esc($data->data['startdate']);
      $dates = explode("/",$startDate);
      $date = $this->cjs_esc("$dates[2]-$dates[1]-$dates[0]");
      $numberdays = $this->cjs_esc($data->data['runsfor']);
      $title = addslashes($data->data['title']);
      $location = addslashes($data->data['location']);
      $county = $this->cjs_esc($data->data['county']);
      $runby = addslashes($data->data['runby']);
      $online = $this->cjs_esc($data->data['webinar']);
      $contacttelephone = $this->cjs_esc($data->data['contacttelephone']);
      $contactemail = $this->cjs_esc($data->data['contactemail']);
      $contactwebsite = $this->cjs_esc($data->data['contactwebsite']);
      //$text = addslashes($data->data['text']);
      $courseCost = addslashes($data->data['cost']);
      
      $text = $data->data['text'];
      $text = strip_tags($text,"<br>,<p>,<ul>,<ol>,<li>,<a>");
      $text = trim(preg_replace('/\s\s+/', ' ', $text));
      $text = addslashes($text);
      
      $this->db->execute("INSERT INTO perch3_events (eventTitle, startDate, numberDays, location, webinar, county, provider, advertTelephone, advertEmail, advertWebsite, cost, advertStatus, eventDynamicFields) VALUES 
      ('$title', '$date', '$numberdays', '$location', '$online', '$county', '$runby', '$contacttelephone', '$contactemail', '$contactwebsite', '$courseCost', 'pen', '{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"}}')");
  		
  		$to = 'trainingdirectory@countrysidejobs.com'; // note the comma
			
	// Message
	$emailHTML = "
	<html>
	<head>
	  <title>CJS Training Submission</title>
	</head>
	<body>
	  <p>Type: Event<br />Title: ".addslashes($title)."<br />
	  Text: ".addslashes($text)."<br />
	  Location: ".addslashes($location)."<br />
	  County: ".addslashes($county)."<br />
	  County: ".addslashes($county)."<br />
	  Run By: ".addslashes($runby)."<br />
	  Online: ".addslashes($online)."<br />
	  Contact Tel: ".addslashes($contacttelephone)."<br />
	  Contact Email: ".addslashes($contactemail)."<br />
	  Contact Website: ".addslashes($contactwebsite)."<br />
	  Course Cost: ".addslashes($courseCost)."<br />
	</body>
	</html>
	";
	
	// To send HTML mail, the Content-type header must be set
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';
	
	// Additional headers
	$headers[] = 'From: no-reply@countryside-jobs.com';
	$headers[] = 'Reply-To: '.$contactemail;
	
	// Mail it
	mail($to, $subject, $emailHTML, implode("\r\n", $headers));    
    }
  
  }
  
  public function searchShortText($formData){
//     print_r($formData);
    $text = $formData['search_term'];
//     print_r($text);
$today = date('Y-m-d');
    $result =  $this->db->get_rows("SELECT * FROM perch3_short_courses WHERE startDate>".$this->db->pdb($today)." AND advertStatus='pub' AND (shortTitle LIKE ".$this->db->pdb('%'.$text.'%')." OR shortDynamicFields LIKE ".$this->db->pdb('%'.$text.'%').") ORDER BY startDate ASC");
//     print_r($result);
    return $result;
  }
  
  public function searchShortCourse($formData){
//     print_r($formData);
    $Query = "SELECT * FROM perch3_short_courses WHERE advertStatus='pub' AND (";
    $data = $formData;   
    
    $today = date('Y-m-d');         
    
    (array_key_exists('free_shortcourse', $data)) ? $free_course = $data['free_shortcourse'] : $free_course = false;
    (array_key_exists('date_short', $data)) ? $dates = $data['date_short'] : $dates = false;
    (array_key_exists('select_section_short', $data)) ? $section = $data['select_section_short'] : $section = false;
    (array_key_exists('select_location_short', $data)) ? $counties = $data['select_location_short'] : $counties = false;
    (array_key_exists('select_provider_short', $data)) ? $provider = $data['select_provider_short'] : $provider = false;    
    
    $entered = false;

    if ($free_course){
      $entered = true;
      $Query.="freeCourse='1') AND (";
    }
    
    if ($dates){
      $entered = true;
      $Query.="startDate>=".$this->db->pdb($dates).") AND (";
    }else{
	  $Query.="startDate>=".$this->db->pdb($today).") AND (";  
    }    
    
    if ($section){
      if ($section!='Any'){
        $entered = true;
        $Query.="categoryOne=".$this->db->pdb($section)." OR categoryTwo=".$this->db->pdb($section)." OR categoryThree=".$this->db->pdb($section)."";
        $Query.=") AND (";        
      }
    }    
    
    if ($counties){
      $entered = true;
      $Query.="county IN (";
      foreach ((array)$counties as $county){
        $Query.="".$this->db->pdb($county).", ";
      }
      $Query = substr($Query, 0, -2);
      $Query.=")) AND (";
    }
    
    if ($provider){
      if ($provider!='Please Select'){
        $entered = true;
        $provider = stripslashes($provider);
        $Query.="provider=".$this->db->pdb($provider)."";
        $Query.=") AND (";
      }      
    }
        
    if ($entered){
      //if any filter was entered, remove " AND (" at the end of $Query
      $Query = substr($Query, 0, -6);
    }else{
      //if no filter entered, then it removes " WHERE (" so it gets the entire DB of jobs without any filter
      $Query = substr($Query, 0, -8);
    }
    
    $Query = $Query." ORDER BY startDate ASC";
    
    return $this->db->get_rows($Query);        
    
  }
  
  public function duplicate($id){
	  
	  $result = $this->db->get_row('SELECT * FROM perch3_short_courses WHERE shortID='.$this->db->pdb($id).'');
	  $duplicate = $this->db->execute("INSERT INTO perch3_short_courses (shortTitle, startDate, startText, categoryOne, categoryTwo, categoryThree, numberDays, location, webinar, online, county, provider, advertTelephone, advertEmail, advertWebsite, advertWebsiteShort, advertLink, freeCourse, logo, logoAlt, displayAd, organisationID, organisationName, contactName, contactTelephone, contactEmail, firstOnline, checkDate, dateUpdated, displayUntil, advertType, advertPaid, advertCost, freeAd, courseCost, concessionsAvailable, advertStatus, shortDynamicFields) VALUES ('".addslashes($result['shortTitle'])." - DUPLICATE', '$result[startDate]', '".addslashes($result['startText'])."', '$result[categoryOne]', '$result[categoryTwo]', '$result[categoryThree]', '$result[numberDays]', '".addslashes($result['location'])."', '$result[webinar]', '$result[online]', '$result[county]', '".addslashes($result['provider'])."', '$result[advertTelephone]', '$result[advertEmail]', '$result[advertWebsite]', '$result[advertWebsiteShort]', '$result[advertLink]', '$result[freeCourse]', '$result[logo]', '$result[logoAlt]', '$result[displayAd]', '$result[organisationID]', '".addslashes($result['organisationName'])."', '".addslashes($result['contactName'])."', '$result[contactTelephone]', '$result[contactEmail]', '$result[firstOnline]', '$result[checkDate]', '$result[dateUpdated]', '$result[displayUntil]', '$result[advertType]', '$result[advertPaid]', '$result[advertCost]', '$result[freeAd]', '$result[courseCost]', '$result[concessionsAvailable]', '$result[advertStatus]', '".addslashes($result['shortDynamicFields'])."')");
	  
  }
  
  public function empty_temp(){
	  
	  $result = $this->db->execute("TRUNCATE perch3_short_courses_temp");
	  
  }
  
  public function create_temp($data){
	  
	  $result = $this->db->insert('perch3_short_courses_temp',$data);
	  
  }
  
  public function get_temp(){
	  
	  $result = $this->db->get_rows('SELECT * FROM perch3_short_courses_temp');
	  return $result;
	  
  }
  
  public function import_temp_row($data){
	  
	  $result = $this->db->insert('perch3_short_courses',$data);
	  
  }
  
  public function getProviders(){
    $today = date('Y-m-d');
    return $this->db->get_rows('SELECT DISTINCT provider FROM perch3_short_courses WHERE provider<>"" AND startDate>='.$this->db->pdb($today).' ORDER BY provider ASC');
  }
  
}