<?php

class CJS_Training_Long_Courses extends PerchAPI_Factory
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
  protected $table     = 'long_courses';
	protected $pk        = 'longID';
	protected $singular_classname = 'CJS_Training_Long_Course';
	
	protected $default_sort_column = 'longID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('longID','startDate', 'longTitle', 'categoryOne', 'categoryTwo', 'categoryThree',
                                'location', 'lat', 'lng', 'county', 'provider', 'advertTelephone', 'advertEmail', 'advertWebsite', 'advertLink', 
                                'logo', 'logoAlt', 'displayAd', 'cShort', 'cHnd', 'cDiploma', 'cNvq', 'cHnc', 'cMsc', 
                                'cDegree', 'cBtec', 'cFoundation', 'cPhd', 'cApprenticeship', 'organisationID', 'organisationName',
                                'contactName', 'contactTelephone', 'contactEmail' ,'firstOnline', 'checkDate', 'dateUpdated', 'displayUntil', 
                                'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus', 'longDynamicFields');

  /*
   * Column order for the Upload CSV screen, used to build the template offered for download
   * there. The importer in modes/upload_csv_lc.pre.php reads cells by position, so this list
   * and that mapping have to be kept in step with each other.
   *
   * advertWebsiteShort is generated from advertWebsite by Bitly, so leave its column empty.
   * The last column takes the plain description text; the importer wraps it into
   * longDynamicFields.
   */
  public $csv_columns = array('longTitle', 'startDate (dd/mm/yyyy)', 'categoryOne', 'categoryTwo', 'categoryThree',
                              'location', 'webinar', 'county', 'provider', 'advertTelephone', 'advertEmail',
                              'advertWebsite', 'advertWebsiteShort (leave empty)', 'advertLink', 'logo', 'logoAlt',
                              'displayAd', 'cShort', 'cHnd', 'cDiploma', 'cNvq', 'cHnc', 'cMsc', 'cDegree', 'cBtec',
                              'cFoundation', 'cPhd', 'cApprenticeship', 'organisationID', 'organisationName',
                              'contactName', 'contactTelephone', 'contactEmail', 'firstOnline (dd/mm/yyyy)',
                              'checkDate (dd/mm/yyyy)', 'dateUpdated (dd/mm/yyyy)', 'displayUntil (dd/mm/yyyy)',
                              'advertType', 'advertPaid', 'advertCost', 'notes', 'freeAd', 'advertStatus',
                              'description');

	public function get_columns($table){
		$data = $this->db->get_rows("SHOW COLUMNS FROM ".$table);
		return $data;
	}
	
	public function get_count($query){
		$data = $this->db->get_rows($query);
		return count($data);
	}
	
	public function process_find_replace($query){
		$this->db->execute($query);
	}

  public function long_courses($category){
    $today=date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE (categoryOne='.$this->db->pdb($category).' OR categoryTwo='.$this->db->pdb($category).' OR categoryThree='.$this->db->pdb($category).') AND advertStatus="pub" 
    AND (displayUntil="0000-00-00" OR (displayUntil<>"0000-00-00" AND displayUntil>='.$this->db->pdb($today).')) ORDER BY longTitle ASC');
  }
  
   public function long_webinars(){
    $today=date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE webinar="1" AND advertStatus="pub" AND (displayUntil="0000-00-00" OR displayUntil>'.$this->db->pdb($today).') ORDER BY longTitle ASC');
  }
  
  public function pending(){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE advertStatus="pen" ORDER BY firstOnline DESC');
    return $results;
  }
  
  public function published(){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE advertStatus="pub" ORDER BY firstOnline DESC');
    return $results;
  }
  
  public function environmental_education(){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE advertStatus="pub" AND categoryOne="enved" ORDER BY firstOnline DESC');
    return $results;
  }
  
  public function suspended(){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE advertStatus="sus" ORDER BY firstOnline DESC');
    return $results;
  }
  
  public function by_organisation($organisationID){
	  $today=date('Y-m-d');
	$results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE organisationID='.$this->db->pdb($organisationID).' AND advertStatus="pub" 
    AND (displayUntil="0000-00-00" OR (displayUntil<>"0000-00-00" AND displayUntil>='.$this->db->pdb($today).'))');
    return $results;  
  }
  
  public function get_offices($id){
	$results = $this->db->get_rows('SELECT * FROM perch3_organisations_t WHERE master_organisationID='.$this->db->pdb($id).'');
    return $results;  
  }
  
  public function by_organisation_distance($organisationID){
	$results = $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE organisationID='.$this->db->pdb($organisationID).' AND categoryOne="distance-learning"');
    return $results;  
  }
  
  public function dl_pending(){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE advertStatus="pen" AND categoryOne="distance-learning" ORDER BY firstOnline DESC');
    return $results;
  }
  
  public function dl_published(){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE advertStatus="pub" AND categoryOne="distance-learning" ORDER BY firstOnline DESC');
    return $results;
  }
  
  public function dl_suspended(){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE advertStatus="sus" AND categoryOne="distance-learning" ORDER BY firstOnline DESC');
    return $results;
  }
  
  public function update_advert($data,$id){
	  $dates = explode("-",$data['checkDate']);
	  $date = "$dates[2]-$dates[1]-$dates[0]";
	  if($data['useCheckDate']=='Yes'){
		  $this->db->execute('UPDATE perch3_long_courses SET checkDate='.$this->db->pdb($date).' WHERE longID='.$this->db->pdb($id).'');
	  }
	  $this->db->execute('UPDATE perch3_long_courses SET advertStatus='.$this->db->pdb($data['advertStatus']).' WHERE longID='.$this->db->pdb($id).'');
	  
  }
  
  public function tidyArchives($type,$date){
    
    if($type=='Providers'){
	    $this->db->execute('DELETE FROM perch3_providers WHERE displayUntil<='.$this->db->pdb($date).'');  
	  }elseif($type=='ShortCourses'){
      $this->db->execute('DELETE FROM perch3_short_courses WHERE displayUntil<='.$this->db->pdb($date).'');
    }elseif($type=='Events'){
      $this->db->execute('DELETE FROM perch3_events WHERE startDate<='.$this->db->pdb($date).'');
    }elseif($type=='LongCourses'){
      $this->db->execute('DELETE FROM perch3_long_courses WHERE displayUntil<='.$this->db->pdb($date).'');
    }
    
  }
  
  public function recentAdditions($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE firstOnline>='.$this->db->pdb($start).' AND firstOnline<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function recentUpdates($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_long_courses WHERE dateUpdated>='.$this->db->pdb($start).' AND dateUpdated<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function searchLongText($formData){
//     print_r($formData);
    $text = $formData['search_term'];
    $result =  $this->db->get_rows("SELECT * FROM perch3_long_courses WHERE advertStatus='pub' AND (longTitle LIKE ".$this->db->pdb('%'.$text.'%')." OR longDynamicFields LIKE ".$this->db->pdb('%'.$text.'%').")");
//     print_r($result);
    return $result;
  }
  
  public function searchDistanceText($formData){
//     print_r($formData);
    $text = $formData['search_term'];
    $today = date('Y-m-d');
    $result =  $this->db->get_rows("SELECT * FROM perch3_long_courses WHERE 
    (categoryOne='distance-learning' OR categoryTwo='distance-learning' OR categoryThree='distance-learning') AND 
    advertStatus='pub' AND (longTitle LIKE ".$this->db->pdb('%'.$text.'%')." OR longDynamicFields LIKE ".$this->db->pdb('%'.$text.'%').") AND displayUntil>=".$this->db->pdb($today)."");
//     print_r($result);
    return $result;
  }
  
  public function searchLongCourse($formData){
//    print_r($formData);
$today = date('Y-m-d');
    $Query = "SELECT * FROM perch3_long_courses WHERE advertStatus='pub' AND displayUntil>=".$this->db->pdb($today)." AND (";
    $data = $formData;            
    
    (array_key_exists('select_section_long', $data)) ? $section = $data['select_section_long'] : $section = false;
    (array_key_exists('select_qualification_long', $data)) ? $qualifications = $data['select_qualification_long'] : $qualifications = false;
    (array_key_exists('select_location_long', $data)) ? $counties = $data['select_location_long'] : $counties = false;
    (array_key_exists('select_provider_long', $data)) ? $provider = $data['select_provider_long'] : $provider = false;    
    
    $entered = false;
    
    if ($section){
      if ($section!='Any'){
        $entered = true;
        $Query.="categoryOne=".$this->db->pdb($section)." OR categoryTwo=".$this->db->pdb($section)." OR categoryThree=".$this->db->pdb($section)."";
        $Query.=") AND (";        
      }
    }
    
    
    if ($qualifications){      
      if (!in_array('Any', (array)$qualifications)) {
        $entered = true;
        foreach ((array)$qualifications as $qualification){
          $Query.="".$this->cjs_ident($qualification)."='1' OR ";
        }
        $Query = substr($Query, 0, -4);
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

    return $this->db->get_rows($Query);    
  }
  
  public function searchDistance($formData){
//     print_r($formData);
	$today = date('Y-m-d');
    $Query = "SELECT * FROM perch3_long_courses WHERE advertStatus='pub' AND displayUntil>=".$this->db->pdb($today)." AND (categoryOne='distance-learning' OR categoryTwo='distance-learning' OR categoryThree='distance-learning') AND (";
    $data = $formData;            
    
    (array_key_exists('select_qualification_distance', $data)) ? $qualifications = $data['select_qualification_distance'] : $qualifications = false;
    (array_key_exists('select_provider_distance', $data)) ? $provider = $data['select_provider_distance'] : $provider = false;    
    
    $entered = false;    
    
    if ($qualifications){      
      if ($qualifications=!'Any') {
        $entered = true;
        $Query.="".$this->cjs_ident($qualifications)."='1'";
        $Query.=") AND (";        
      }
    }
        
    if ($provider){
      if ($provider!='Please Select'){
        $entered = true;
        $provider = stripslashes($provider);
        $Query.="provider=".$this->db->pdb($provider)."";
        $Query.=") AND (";
      }      
    }
    
    //Since all options, including the original $Query have at the end ' AND (', then it is a rule that this needs to be removed
    $Query = substr($Query, 0, -6);
    
    return $this->db->get_rows($Query);    
    
  }
  
  public function submitLongCourseAdvert($data){
    $type = $data->data['type'];
    $title = addslashes($data->data['title']);
    $provider = addslashes($data->data['provider']);
    $location = addslashes($data->data['location']);
    $short = $this->cjs_esc($data->data['short']);
    $hnd = $this->cjs_esc($data->data['hnd']);
    $nvq = $this->cjs_esc($data->data['nvq']);
    $diploma = $this->cjs_esc($data->data['diploma']);
    $hnc = $this->cjs_esc($data->data['hnc']);
    $msc = $this->cjs_esc($data->data['msc']);
    $degree = $this->cjs_esc($data->data['degree']);
    $btec = $this->cjs_esc($data->data['btec']);
    $foundation = $this->cjs_esc($data->data['foundation']);
    $phd = $this->cjs_esc($data->data['phd']);
    $apprenticeship = $this->cjs_esc($data->data['apprenticeship']);
    $contacttelephone = $this->cjs_esc($data->data['contacttelephone']);
    $contactemail = $this->cjs_esc($data->data['contactemail']);
    $contactwebsite = $this->cjs_esc($data->data['contactwebsite']);
    $text = addslashes($data->data['text']);
    if($type=='Long Course'){
      return $this->db->execute("INSERT INTO perch3_long_courses (longTitle, provider, location, advertTelephone, advertEmail, advertWebsite, cShort, cHnd, cDiploma, cNvq, cHnc, cMsc, cDegree, cBtec, cFoundation, cPhd, cApprenticeship, advertStatus, longDynamicFields) VALUES 
      ('$title', '$provider', '$location', '$contacttelephone', '$contactemail', '$contactwebsite', '$short', '$hnd', '$nvq', '$diploma', '$hnc', '$msc', '$degree', '$btec', '$foundation', '$phd', '$apprenticeship', 'pen', '{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"}}')");
    }else if($type=='Distance Learning'){
      return $this->db->execute("INSERT INTO perch3_long_courses (longTitle, categoryOne, provider, location, advertTelephone, advertEmail, advertWebsite, cShort, cHnd, cDiploma, cNvq, cHnc, cMsc, cDegree, cBtec, cFoundation, cPhd, cApprenticeship, advertStatus, longDynamicFields) VALUES 
      ('$title', 'distance-learning', '$provider', '$location', '$contacttelephone', '$contactemail', '$contactwebsite', '$short', '$hnd', '$nvq', '$diploma', '$hnc', '$msc', '$degree', '$btec', '$foundation', '$phd', '$apprenticeship', 'pen', '{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"}}')");
    }else if($type=='Provider'){
      return $this->db->execute("INSERT INTO perch3_providers (providerName, advertTelephone, advertEmail, advertWebsite, location, cShort, cHnd, cDiploma, cNvq, cHnc, cMsc, cDegree, cBtec, cFoundation, cPhd, cApprenticeship, advertStatus, providerDynamicFields) VALUES 
      ('$title', '$contacttelephone', '$contactemail', '$contactwebsite', '$location', '$short', '$hnd', '$nvq', '$diploma', '$hnc', '$msc', '$degree', '$btec', '$foundation', '$phd', '$apprenticeship', 'pen', '{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"}}')");
    }
  }
  
  public function getLProviders(){
    $today = date('Y-m-d');
    return $this->db->get_rows('SELECT DISTINCT provider FROM perch3_long_courses WHERE provider<>"" ORDER BY provider ASC');
  }
  
  public function getDProviders(){
    $today = date('Y-m-d');
    return $this->db->get_rows('SELECT DISTINCT provider FROM perch3_long_courses WHERE provider<>"" AND (categoryOne="distance-learning" OR categoryTwo="distance-learning" OR categoryThree="distance-learning") ORDER BY provider ASC');
    
  }
  
  /*
   * CSV import staging. A CSV upload writes its rows to perch3_long_courses_temp so the admin
   * can look them over before anything reaches the live table, then import_temp_row() copies
   * each one across. Same flow as Short Courses.
   */
  public function empty_temp(){

	  $result = $this->db->execute("TRUNCATE perch3_long_courses_temp");

  }

  public function create_temp($data){

	  $result = $this->db->insert('perch3_long_courses_temp',$data);

  }

  public function get_temp(){

	  $result = $this->db->get_rows('SELECT * FROM perch3_long_courses_temp');
	  return $result;

  }

  public function import_temp_row($data){

	  $result = $this->db->insert('perch3_long_courses',$data);

  }

  /*
   * Copy a course, marked so the new one can be told apart from the original. Built from the
   * row itself rather than a column list, so any column added to perch3_long_courses later is
   * carried over without this needing to change.
   *
   * The copy goes in as pending whatever the original's status was, so duplicating a live
   * course doesn't put a second copy of it straight onto the site.
   */
  public function duplicate($id){

	  $result = $this->db->get_row('SELECT * FROM perch3_long_courses WHERE longID='.$this->db->pdb($id).'');

	  if (!$result) return false;

	  unset($result['longID']);
	  $result['longTitle'] = $result['longTitle'].' - DUPLICATE';
	  $result['advertStatus'] = 'pen';

	  return $this->db->insert('perch3_long_courses',$result);

  }

}