<?php

class CJS_Daily_Volunteers extends PerchAPI_Factory
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
  protected $table     = 'volunteers';
	protected $pk        = 'volunteerID';
	protected $singular_classname = 'CJS_Daily_Volunteer';
	
	protected $default_sort_column = 'volunteerID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('volunteerID', 'title', 'advertRef', 'memberID', 'employerDisplay', 'employerID', 'advertText', 'closingDate', 'closingDateT', 'intDate', 'location', 'payBenefits',
                                'tcOccasional', 'tcOnceorTwiceWeek', 'tcPartTime', 'tcFullTime', 'tcSixMonths', 'tcSixMonthYear', 'tcOneYearMore', 
                                'vidEmbedC', 'howToAp', 'typeRole', 'sAdminIT', 'sArbo', 'sComVol', 'sCountry', 'sEco', 'sEnvOE', 'sHorti', 'sPubF', 'sRoW', 'sVisMan', 'sWiWoAnC',  
                                'rNational', 'rInternational', 'rScotland', 'rWales', 'rNIreland', 'rEngland', 'rAnglia', 'rMidlands', 'rNorthEast', 'rNorthWest', 'rSouth', 'rSouthWest', 'rCounty',
                                'accommodation', 'expenses', 'fTraining', 'imageLogo', 'imageAlt', 'imageLogo2', 'imageAlt2', 'longRun', 'deleteDate', 'displayUntil', 'freeLin', 'displayFrom', 
                                'archive', 'advertStatus', 'volunteersDynamicFields');	      

  public function get_volunteer_adverts($sector,$object){
    $sectors = [['sAdminIT', 'admin'],['sArbo','arboriculture'],['sComVol','community-and-volunteers'],
                ['sCountry','countryside'],['sEco','ecology'],['sEnvOE','environmental-and-outdoor-education'],
                ['sHorti','horticulture'],['sPubF','publicity-and-fundraising'],['sRoW','rights-of-way'], 
                ['sVisMan','visitor-management'],['sWiWoAnC','wildlife-work-and-animal-care']];
    foreach($sectors as $dbsector){
      if ($dbsector[1] == $sector){
        $desired_sector = $dbsector[0];
      }
    }
    
    $list = [];
    $today = date('Y-m-d');
    foreach ($object as $element){
      $e_array = $element->to_array();
      $flag = false;
      foreach ($sectors as $esector){
        if (($e_array[$esector[0]]=='1') && ($esector[0]==$desired_sector)){
          $flag = true;
        }
      }      
      if ($flag && ($e_array['closingDate']>$today) && ($e_array['advertStatus']=='pub')) $list[]=$element;
    }
    
    return $list;
    
  }
  
  function get_pending(){
    return $this->db->get_rows('SELECT * FROM perch3_volunteers WHERE advertStatus="pen"');
  }
  
  function get_published(){
    return $this->db->get_rows('SELECT * FROM perch3_volunteers WHERE advertStatus="pub" ORDER BY displayFrom DESC');
  }
  
  function get_suspended(){
    return $this->db->get_rows('SELECT * FROM perch3_volunteers WHERE advertStatus="sus"');
  }
  
  function delete_volunteer($id){
    return $this->db->get_rows('DELETE FROM perch3_volunteers WHERE volunteerID='.$this->db->pdb($id).'');
  }
  
  function update_status($id,$status){
    return $this->db->execute('UPDATE perch3_volunteers SET advertStatus='.$this->db->pdb($status).' WHERE volunteerID='.$this->db->pdb($id).'');
  }
  
  function archive($date){
    $this->db->execute('UPDATE perch3_volunteers SET archive="on", advertStatus="sus" WHERE closingDate<='.$this->db->pdb($date).'');
  }
  
  function deleteOld($date){
    $this->db->execute('DELETE * FROM perch3_volunteers WHERE closingDate<='.$this->db->pdb($date).'');
  }
  
  public function dailySearchVolunteers($formData){
	  $today = date('Y-m-d');
    $Query = "SELECT * FROM perch3_volunteers WHERE advertStatus='pub' AND closingDate>".$this->db->pdb($today)." AND (";
    $data = $formData;        
    
    (array_key_exists('select_region_volunteers', $data)) ? $regions = $data['select_region_volunteers'] : $regions = false;
    (array_key_exists('select_sector_volunteers', $data)) ? $sectors = $data['select_sector_volunteers'] : $sectors = false;
    (array_key_exists('select_role', $data)) ? $timeCommitments = $data['select_role'] : $timeCommitments = false;
    (array_key_exists('select_county_volunteers', $data)) ? $counties = $data['select_county_volunteers'] : $counties = false;
    (array_key_exists('accommodation', $data)) ? $accommodation = $data['accommodation'] : $accommodation = false;
    (array_key_exists('expenses', $data)) ? $expenses = $data['expenses'] : $expenses = false;
    (array_key_exists('fTraining', $data)) ? $fTraining = $data['fTraining'] : $fTraining = false;     
    
    $entered = false;
    
    if ($regions){
      $entered = true;
      foreach ((array)$regions as $region){
        $Query.="".$this->cjs_ident($region)."='1' OR ";
      }
      $Query = substr($Query, 0, -4);
      $Query.=") AND (";      
    }
    
    if ($sectors){      
      if (!in_array('Any', (array)$sectors)) {
        $entered = true;
        foreach ((array)$sectors as $sector){
          $Query.="".$this->cjs_ident($sector)."='1' OR ";
        }
        $Query = substr($Query, 0, -4);
        $Query.=") AND (";        
      }else{
//         $entered = false;
      }
    }
    
    if ($counties){
      $entered = true;
      $Query.="rCounty IN (";
      foreach ((array)$counties as $county){
        $Query.="".$this->db->pdb($county).", ";
      }
      $Query = substr($Query, 0, -2);
      $Query.=")) AND (";
    }
    
    if ($timeCommitments){
      if (!in_array('Any', (array)$timeCommitments)){
        $entered = true;
        foreach ((array)$timeCommitments as $timeCommitment){
          $Query.="".$this->cjs_ident($timeCommitment)."='1' OR ";
        }
        $Query = substr($Query, 0, -4);
        $Query.=") AND (";
      }      
    }
    
    if ($accommodation){
      $entered = true;
      $Query.="accommodation='1') AND (";
    }
    
    if ($expenses){
      $entered = true;
      $Query.="expenses='1') AND (";
    }
    
    if ($fTraining){
      $entered = true;
      $Query.="fTraining='1') AND (";
    }    
    
    if ($entered){
      //if any filter was entered, remove " AND (" at the end of $Query
      $Query = substr($Query, 0, -6);
    }else{
      //if no filter entered, then it removes " WHERE (" so it gets the entire DB of jobs without any filter
      $Query = substr($Query, 0, -8);
    }
//     print_r($Query);
    return $this->db->get_rows($Query);
  }
  
  public function dailyTextSearchVolunteers($formData){
    $title = $formData['textSearch'];
    $today = date('Y-m-d');
    $result =  $this->db->get_rows("SELECT * FROM perch3_volunteers WHERE (title LIKE ".$this->db->pdb('%'.$title.'%')." OR volunteerDynamicFields LIKE ".$this->db->pdb('%'.$title.'%').") AND displayFrom<=".$this->db->pdb($today)." AND (displayUntil>=".$this->db->pdb($today)." OR closingDate>".$this->db->pdb($today).") AND archive<>'1' AND advertStatus='pub' ORDER BY displayFrom ASC");
    return $result;
  }
  
  public function get_volunteer_adverts_sector($sector){
	$today = date('Y-m-d');
	$query = "SELECT * FROM perch3_volunteers WHERE $sector='1' AND archive<>'1' AND advertStatus='pub' AND displayFrom<='$today' AND (closingDate>='$today' OR closingDate='0000-00-00') AND displayUntil>'$today' ORDER BY volunteerID DESC";
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_volunteer_adverts_region($region){

	$today = date('Y-m-d');
	$query = "SELECT * FROM perch3_volunteers WHERE displayFrom<='$today' AND $region='1' AND (displayUntil>='$today' OR closingDate>'$today') AND archive<>'1' 
    AND advertStatus='pub' ORDER BY displayFrom ASC";
	$result =  $this->db->get_rows($query);
	if(count($result)==0){
		$date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-21, date('Y')));
		$query = "SELECT * FROM perch3_volunteers WHERE $region='1' AND displayUntil>='$today' AND closingDate>'$today' AND archive<>'1' 
    AND advertStatus='pub' ORDER BY displayFrom ASC";
		$result =  $this->db->get_rows($query);	
	}
	return $result;
	  
  }
  
  public function get_volunteer_adverts_apps_ints(){
	
	$today = date('Y-m-d');
	$query = "SELECT * FROM perch3_volunteers WHERE displayFrom<='$today' AND typeRole='Apprentice' AND (displayUntil>='$today' OR closingDate>'$today') AND archive<>'1' 
    AND advertStatus='pub' AND displayFrom<='$today' ORDER BY displayFrom ASC";
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_volunteer_additions(){
	
	$date = date("Y-m-d",strtotime('Monday this week'));
	$query = "SELECT * FROM perch3_volunteers WHERE displayFrom>='$date' AND archive<>'1' AND advertStatus='pub' ORDER BY volunteerID DESC";
	$result =  $this->db->get_rows($query);
	if(count($result)==0){
		$date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-21, date('Y')));
		$query = "SELECT * FROM perch3_volunteers WHERE displayFrom>='$date' AND archive<>'1' AND advertStatus='pub' ORDER BY volunteerID DESC";
		$result =  $this->db->get_rows($query);	
	}
	return $result;
	  
  }
  
  public function empty_temp(){
	  
	  $result = $this->db->execute("TRUNCATE perch3_volunteers_temp");
	  
  }
  
  public function create_temp($data){
	  
	  $result = $this->db->insert('perch3_volunteers_temp',$data);
	  
  }
  
  public function get_temp(){
	  
	  $result = $this->db->get_rows('SELECT * FROM perch3_volunteers_temp');
	  return $result;
	  
  }
  
  public function import_temp_row($data){
	  
	  $result = $this->db->insert('perch3_volunteers',$data);
	  
  }
  
  public function memberJobs($memberID){
	  
	  $results = $this->db->get_rows('SELECT * FROM perch3_volunteers WHERE memberID='.$this->db->pdb($memberID).' ORDER BY volunteerID DESC');
	  return $results;
	  
  }
  
  public function get_advert_by_id($id){

	$query = "SELECT * FROM perch3_volunteers WHERE volunteerID=".$this->db->pdb($id)."";	
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_all_feed(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	
    $today = date('Y-m-d');
	$date = $today;

	$query = $this->db->get_rows('SELECT * FROM perch3_volunteers WHERE displayFrom<='.$this->db->pdb($today).' AND advertStatus="pub" ORDER BY volunteerID DESC');

	foreach($query as $data){

		$Template->set('cjs_daily/volunteer_adverts_feed.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['volunteerDynamicFields'], true);

		$data = array_merge($fields,$data);
		$html = $Template->render($data, true);
		$html = $Template->apply_runtime_post_processing($html, $data);  
		echo $html;
		
	}
  }
  
}