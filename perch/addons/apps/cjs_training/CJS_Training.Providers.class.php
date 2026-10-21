<?php

class CJS_Training_Providers extends PerchAPI_Factory
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
  protected $table     = 'providers';
	protected $pk        = 'providerID';
	protected $singular_classname = 'CJS_Training_Provider';
	
	protected $default_sort_column = 'providerName';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('providerID', 'providerName', 'advertTelephone', 'advertEmail', 'advertWebsite', 'advertWebsiteShort', 'advertLink',
                                'logo', 'logoAlt', 'displayAd', 'location', 'county', 'cShort', 'cHnd', 'cDiploma', 'cNvq', 'cHnc', 'cMsc', 
                                'cDegree', 'cBtec', 'cFoundation', 'cPhd', 'cApprenticeship', 'organisationID', 'organisationName',
                                'contactName', 'contactTelephone', 'contactEmail' ,'firstOnline', 'checkDate', 'displayUntil', 'advertType', 
                                'advertPaid', 'advertCost', 'freeAd', 'advertStatus', 'providerDynamicFields');	

  public function get_all_providers(){
    $today=date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_providers WHERE advertStatus="pub" AND displayUntil>='.$this->db->pdb($today).' ORDERED BY providerID DESC');
  }
  
  public function get_provider($id){
    $data = $this->db->get_rows('SELECT * FROM perch3_providers WHERE providerID='.$this->db->pdb($id).'');
    return $data;
  }
  
  public function get_pending(){
    return $this->db->get_rows('SELECT * FROM perch3_providers WHERE advertStatus="pen" ORDER BY providerName ASC');
  }
  
  public function get_published(){
    return $this->db->get_rows('SELECT * FROM perch3_providers WHERE advertStatus="pub" ORDER BY providerName ASC');
  }
  
  public function get_suspended(){
    return $this->db->get_rows('SELECT * FROM perch3_providers WHERE advertStatus="sus" ORDER BY providerName ASC');
  }
  
  public function by_organisation($organisationID){
	$results = $this->db->get_rows('SELECT * FROM perch3_providers WHERE organisationID='.$this->db->pdb($organisationID).'');
    return $results;  
  }
  
  public function update_advert($data,$id){
	  $dates = explode("-",$data['checkDate']);
	  $date = "$dates[2]-$dates[1]-$dates[0]";
	  if($data['useCheckDate']=='Yes'){
		  $this->db->execute('UPDATE perch3_providers SET checkDate='.$this->db->pdb($date).' WHERE providerID='.$this->db->pdb($id).'');
	  }
	  $this->db->execute('UPDATE perch3_providers SET advertStatus='.$this->db->pdb($data['advertStatus']).' WHERE providerID='.$this->db->pdb($id).'');
	  
  }
  
  public function all_sort_name($letter){
    $today=date('Y-m-d');
    $letter = ucwords($letter);
    return $this->db->get_rows('SELECT * FROM perch3_providers WHERE advertStatus="pub" AND LEFT(providerName,1)='.$this->db->pdb($letter).' ORDER BY providerName ASC');
  }
  
  public function recentAdditions($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_providers WHERE firstOnline>='.$this->db->pdb($start).' AND firstOnline<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function recentUpdates($start,$end){
    $results = $this->db->get_rows('SELECT * FROM perch3_providers WHERE dateUpdated>='.$this->db->pdb($start).' AND dateUpdated<='.$this->db->pdb($end).' AND advertStatus="pub"');
    return $results;
  }
  
  public function searchCentresProvidersText($formData){
//     print_r($formData);
    $text = $formData['search_term'];
//     print_r($text);
$today = date('Y-m-d');
    $result =  $this->db->get_rows("SELECT * FROM perch3_providers WHERE advertStatus='pub' AND (providerName LIKE ".$this->db->pdb('%'.$text.'%')." OR providerDynamicFields LIKE ".$this->db->pdb('%'.$text.'%').")");
//     print_r($result);
    return $result;
  }  
  
  public function searchCentresProviders($formData){
$today = date('Y-m-d');
    $Query = "SELECT * FROM perch3_providers WHERE advertStatus='pub'";
    $data = $formData;            
    
    (array_key_exists('select_qualification_centres_providers', $data)) ? $qualifications = $data['select_qualification_centres_providers'] : $qualifications = false;
    (array_key_exists('select_location_centres_providers', $data)) ? $counties = $data['select_location_centres_providers'] : $counties = false;
    
    $entered = false;
    if ($qualifications){      
      if ($qualifications!=='Any') {
	    $Query.=" AND ";

        $Query.="".$this->cjs_ident($qualifications)."='1'";     
      }
    }
    
    if ($counties){
      $entered = true;
      $Query.=" AND county IN (";
      foreach ((array)$counties as $county){
        $Query.="".$this->db->pdb($county).", ";
      }
      $Query = substr($Query, 0, -2);
      $Query.=")) AND (";
    }    
    
    if ($entered){
      //if any filter was entered, remove " AND (" at the end of $Query
      $Query = substr($Query, 0, -7);
    }else{
      
    }

    return $this->db->get_rows($Query);      
  }
  

}