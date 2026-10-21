<?php

class CJS_Workdays_Surveys extends PerchAPI_Factory
{
  protected $table     = 'surveys';
	protected $pk        = 'surveyID';
	protected $singular_classname = 'CJS_Workdays_Survey';
	
	protected $default_sort_column = 'surveyID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('surveyID', 'Title', 'region', 'section', 'provider', 'advertEmail', 'advertWebsite', 'advertWebsiteShort', 'advertDisplay', 
                                'logo', 'logoAlt', 'displayAd', 'bannerAd','bannerAdPos', 'organisationID', 'organisationName', 
                                'contactName', 'contactEmail', 'firstOnline', 'checkDate', 'displayUntil',
                                'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus', 'dateUpdated', 'surveyDynamicFields');	      

  public function get_surveys($sector,$object){
    $sectors = [['general','General'],['birds','Birds'],['mammals','Mammals'],
                ['herpetology','Herpetology'],['plants','Plants'],['fish','Fish/Marine'],
                ['invertebrates','Invertebrates'],['research','Research']];
    foreach($sectors as $dbsector){
      if ($dbsector[0] == $sector){
        $desired_sector = $dbsector[1];
      }
    }
    $today = date('Y-m-d');
    $list = [];
    foreach ($object as $element){
      if ($element->section()==$desired_sector && $element->advertStatus()=='Published' && $element->displayUntil()>=$today) $list[]=$element;
//       if ($element->section()==$desired_sector) $list[]=$element;
    }
    
    return $list;
    
  }
  
  public function surveys_by_organisation($organisationID){
    $data = $this->db->get_rows('SELECT * FROM perch3_surveys WHERE organisationID='.$this->db->pdb($organisationID).'');
    return $data;
  }
  
  public function surveys_by_sector($sector){
	  $today = date('Y-m-d');
    $data = $this->db->get_rows('SELECT * FROM perch3_surveys WHERE section='.$this->db->pdb($sector).' AND advertStatus="pub" AND (displayUntil>='.$this->db->pdb($today).' OR displayUntil="0000-00-00") ORDER BY surveyID DESC');
    return $data;
  }
  
  public function surveys_by_date(){
	  $today = date('Y-m-d');
    $data = $this->db->get_rows('SELECT * FROM perch3_surveys WHERE advertStatus="pub" AND (displayUntil>='.$this->db->pdb($today).' OR displayUntil="0000-00-00") ORDER BY surveyID DESC');
    return $data;
  }
  
  public function pending(){
    $data = $this->db->get_rows('SELECT * FROM perch3_surveys WHERE advertStatus="pen"');
    return $data;
  }
  
  public function published(){
    $data = $this->db->get_rows('SELECT * FROM perch3_surveys WHERE advertStatus="pub"');
    return $data;
  }
  
  public function suspended(){
    $data = $this->db->get_rows('SELECT * FROM perch3_surveys WHERE advertStatus="sus"');
    return $data;
  }
  
  public function archived(){
    $data = $this->db->get_rows('SELECT * FROM perch3_surveys WHERE advertStatus="arc"');
    return $data;
  }
  
  public function update_advert($data,$id){
	  $dates = explode("-",$data['checkDate']);
	  $date = "$dates[2]-$dates[1]-$dates[0]";
	  if($data['useCheckDate']=='Yes'){
		  $this->db->execute('UPDATE perch3_surveys SET checkDate='.$this->db->pdb($date).' WHERE surveyID='.$this->db->pdb($id).'');
	  }
	  $this->db->execute('UPDATE perch3_surveys SET advertStatus='.$this->db->pdb($data['advertStatus']).' WHERE surveyID='.$this->db->pdb($id).'');
	  
  }
  
  public function export_surveys($query){
    $data = $this->db->get_rows($query);

    foreach($data as $data){
	    
      $fields = json_decode($data['surveyDynamicFields'], true);
      echo "<p><strong>$data[Title]</strong></p>";
      echo $fields['description']['processed'];
      echo "<p>";
      if($data['advertEmail']<>''){
        echo "<a href=\"mailto:$data[advertEmail]?subject=$data[advertEmailSub]\">$data[advertEmail]</a> ";
      }
      if($data['advertWebsiteShort']<>''){
        echo "<a href=\"$data[advertWebsiteShort]\">$data[advertWebsiteShort]</a> ";
      }elseif($data['advertWebsite']<>''){
	    echo "<a href=\"$data[advertWebsite]\">$data[advertWebsite]</a> ";  
      }
      
      echo "</p>";
      
    }
  }
  
  public function empty_temp(){
	  
	  $result = $this->db->execute("TRUNCATE perch3_surveys_temp");
	  
  }
  
  public function create_temp($data){
	  
	  $result = $this->db->insert('perch3_surveys_temp',$data);
	  
  }
  
  public function get_temp(){
	  
	  $result = $this->db->get_rows('SELECT * FROM perch3_surveys_temp');
	  return $result;
	  
  }
  
  public function import_temp_row($data){
	  
	  $result = $this->db->insert('perch3_surveys',$data);
	  
  }
  
}