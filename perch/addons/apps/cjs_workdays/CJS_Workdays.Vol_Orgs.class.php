<?php

class CJS_Workdays_Vol_Orgs extends PerchAPI_Factory
{
  protected $table     = 'vorganisations_wd';
	protected $pk        = 'vorganisationID';
	protected $singular_classname = 'CJS_Workdays_Vol_Org';
	
	protected $default_sort_column = 'vorganisationID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('vorganisationID', 'vorgName', 'advertEmail', 'advertWebsite', 'advertDisplay', 'logo', 'logoAlt', 'displayAd', 'bannerAd',
                                'bannerAdPos', 'region', 'county', 'cVolPlace', 'cVolOpps', 'cWorkDays', 'URLAdverts', 'organisationID', 
                                'organisationName', 'contactName', 'contactJobTitle', 'contactTelephone', 'contactEmail', 'firstOnline', 'checkDate', 'displayUntil', 
                                'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus', 'vorganisationDynamicFields');	      

  public function get_vol_orgs($region,$object){
    $regions = [['scotland','Scotland'],['wales','Wales'],['northern-ireland','Northern Ireland'],
                ['england','England'],['east-anglia','East Anglia'],['midlands','Midlands'],
                ['north-east','North East'],['north-west','North West'],['south','South'],['south-west','South West'],
                ['national','National'],['international','International']];
    foreach($regions as $dbregion){
      if ($dbregion[0] == $region){
        $desired_region = $dbregion[1];
      }
    }
    return $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd WHERE advertStatus="Published" AND region='.$this->db->pdb($desired_region).' ORDERED BY vorganisationID DESC');
  }
  
  public function vorgs_by_organisation($organisationID){
    $data = $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd WHERE organisationID='.$this->db->pdb($organisationID).'');
    return $data;
  }
  
  public function vorgs_by_region($region){
	$today = date('Y-m-d');
    $data = $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd WHERE region='.$this->db->pdb($region).' AND advertStatus="pub" ORDER BY vorgName ASC');
    $i = 0;
    foreach($data as $volunteer){
	    $office = $this->db->get_row("SELECT * FROM perch3_organisations_wd WHERE organisationID=".$this->db->pdb($volunteer['organisationID']).""); 
	    $organisation = $this->db->get_row("SELECT * FROM perch3_organisations WHERE organisationID=".$this->db->pdb($office['master_organisationID']).""); 
	    $data[$i]['organisationSlug'] = $organisation['slug'];
	    $i++;
    }
    return $data;
  }
  
  public function pending(){
    $data = $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd WHERE advertStatus="pen"');
    return $data;
  }
  
  public function published(){
    $data = $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd WHERE advertStatus="pub"');
    return $data;
  }
  
  public function suspended(){
    $data = $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd WHERE advertStatus="sus"');
    return $data;
  }
  
  public function archive(){
    $data = $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd WHERE advertStatus="arc"');
    return $data;
  }
  
  public function update_advert($data,$id){
	  $dates = explode("-",$data['checkDate']);
	  $date = "$dates[2]-$dates[1]-$dates[0]";
	  if($data['useCheckDate']=='Yes'){
		  $this->db->execute('UPDATE perch3_vorganisations_wd SET checkDate='.$this->db->pdb($date).' WHERE vorganisationID='.$this->db->pdb($id).'');
	  }
	  $this->db->execute('UPDATE perch3_vorganisations_wd SET advertStatus='.$this->db->pdb($data['advertStatus']).' WHERE vorganisationID='.$this->db->pdb($id).'');
	  
  }
  
  public function empty_temp(){
	  
	  $result = $this->db->execute("TRUNCATE perch3_vorganisations_wd_temp");
	  
  }
  
  public function create_temp($data){
	  
	  $result = $this->db->insert('perch3_vorganisations_wd_temp',$data);
	  
  }
  
  public function get_temp(){
	  
	  $result = $this->db->get_rows('SELECT * FROM perch3_vorganisations_wd_temp');
	  return $result;
	  
  }
  
  public function import_temp_row($data){
	  
	  $result = $this->db->insert('perch3_vorganisations_wd',$data);
	  
  }
}