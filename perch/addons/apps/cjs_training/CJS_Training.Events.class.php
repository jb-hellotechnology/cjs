<?php

class CJS_Training_Events extends PerchAPI_Factory
{
  protected $table     = 'events';
	protected $pk        = 'eventID';
	protected $singular_classname = 'CJS_Training_Event';
	
	protected $default_sort_column = 'startDate';
	protected $default_sort_order = "DESC";
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('eventID', 'eventTitle', 'startDate', 'numberDays', 'location', 'lat', 'lng', 'county', 'provider', 'advertTelephone',
                          'advertEmail', 'advertWebsite', 'advertWebsiteShort', 'advertLink', 'logo', 'logoAlt', 'displayAd', 'cost', 
                          'organisationID', 'organisationName', 'contactName', 'contactTelephone', 'contactEmail', 'firstOnline',
                          'checkDate', 'dateUpdated', 'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus','eventDynamicFields');	
  
  public function get_all_events(){
    $today=date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_events WHERE advertStatus="pub" AND startDate>='.$this->db->pdb($today).' ORDER BY startDate ASC');
  }
  
  public function events_webinars(){
    $today=date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_short_courses WHERE advertStatus="pub" AND startDate>='.$this->db->pdb($today).' AND webinar="1" AND online!="1" ORDER BY startDate ASC');
    
  }
  
  public function pending(){
    return $this->db->get_rows('SELECT * FROM perch3_events WHERE advertStatus="pen" ORDER BY startDate DESC');
  }
  
  public function published(){
    return $this->db->get_rows('SELECT * FROM perch3_events WHERE advertStatus="pub" ORDER BY startDate DESC');
  }
  
  public function suspended(){
    return $this->db->get_rows('SELECT * FROM perch3_events WHERE advertStatus="sus" ORDER BY startDate DESC');
  }
  
  public function by_organisation($organisationID){
	  $today = date('Y-m-d');
	$results = $this->db->get_rows('SELECT * FROM perch3_events WHERE organisationID='.$this->db->pdb($organisationID).' AND startDate>='.$this->db->pdb($today).' AND advertStatus="pub" ORDER BY StartDate ASC');
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
		  $this->db->execute('UPDATE perch3_events SET checkDate='.$this->db->pdb($date).' WHERE eventID='.$this->db->pdb($id).'');
	  }
	  $this->db->execute('UPDATE perch3_events SET advertStatus='.$this->db->pdb($data['advertStatus']).' WHERE eventID='.$this->db->pdb($id).'');
	  
  }
  
  public function monthsEvents($start,$end){
	if($start=='undated'){
		$results = $this->db->get_rows('SELECT * FROM perch3_events WHERE StartDate="0000-00-00" AND advertStatus="pub"');
	}else{
		$start = explode("-",$start);
		$start = "$start[0]-$start[1]-01";
		$end = explode("-",$end);
		$end = "$end[0]-$end[1]-31";
		echo "<h2>Showing Results For Date Period: $start - $end</h2>";
		$results = $this->db->get_rows('SELECT * FROM perch3_events WHERE StartDate>='.$this->db->pdb($start).' AND StartDate<='.$this->db->pdb($end).' AND advertStatus="pub" ORDER BY StartDate ASC');
	}
    return $results;
  }
  
  public function recentAdditions($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_events WHERE firstOnline>='.$this->db->pdb($start).' AND firstOnline<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function recentUpdates($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_events WHERE dateUpdated>='.$this->db->pdb($start).' AND dateUpdated<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function searchEventsText($formData){
//     print_r($formData);
    $text = $formData['search_term'];
//     print_r($text);
$today = date('Y-m-d');
    $result =  $this->db->get_rows("SELECT * FROM perch3_events WHERE advertStatus='pub' AND StartDate>=".$this->db->pdb($today)." AND (eventTitle LIKE ".$this->db->pdb('%'.$text.'%')." OR eventDynamicFields LIKE ".$this->db->pdb('%'.$text.'%').") ORDER BY StartDate ASC");
//     print_r($result);
    return $result;
  }
  
  public function searchEvents($formData){
    $Query = "SELECT * FROM perch3_events WHERE advertStatus='pub' AND (";
    $data = $formData;            
    
    (array_key_exists('date_events', $data)) ? $dates = $data['date_events'] : $dates = false;
    (array_key_exists('select_location_events', $data)) ? $counties = $data['select_location_events'] : $counties = false;
    
    $entered = false;
    
    $today = date('Y-m-d');
    
    if ($dates){
      $entered = true;
      $Query.="StartDate>=".$this->db->pdb($dates).") AND (";
    }else{
	  $Query.="StartDate>=".$this->db->pdb($today).") AND (";  
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
        
    if ($entered){
      //if any filter was entered, remove " AND (" at the end of $Query
      $Query = substr($Query, 0, -6);
    }else{
      //if no filter entered, then it removes " WHERE (" so it gets the entire DB of jobs without any filter
      $Query = substr($Query, 0, -8);
    }    
    
    $Query = $Query." ORDER BY StartDate ASC";

    return $this->db->get_rows($Query);
  }
 
  public function export_rows($query){
    $data = $this->db->get_rows($query);
    return $data;
  } 

}