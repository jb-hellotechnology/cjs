<?php

class CJS_Workdays_Organisations extends PerchAPI_Factory
{
  protected $table     = 'organisations_wd';
	protected $pk        = 'organisationID';
	protected $singular_classname = 'CJS_Workdays_Organisation';
	
	protected $default_sort_column = 'name';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('organisationID', 'name', 'master_organisationID', 'contactName', 'contactJobTitle', 'contactPhone', 'contactEmail', 'doNotContact', 'organisationDynamicFields');	
  
  public function updateWorkdays($org1,$org2){
    $this->db->execute('UPDATE perch3_workdays SET organisationID='.$this->db->pdb($org2).' WHERE organisationID='.$this->db->pdb($org1).'');
  }
  public function updateSurveys($org1,$org2){
    $this->db->execute('UPDATE perch3_surveys SET organisationID='.$this->db->pdb($org2).' WHERE organisationID='.$this->db->pdb($org1).'');
  }
  public function updateVOrgs($org1,$org2){
    $this->db->execute('UPDATE perch3_vorganisations_wd SET organisationID='.$this->db->pdb($org2).' WHERE organisationID='.$this->db->pdb($org1).'');
  }
  
  public function updateOffices_Organisations($organisationID,$organisationID2){
	  
	  $query = $this->db->execute('UPDATE perch3_organisations_wd SET master_organisationID='.$this->db->pdb($organisationID2).' WHERE master_organisationID='.$this->db->pdb($organisationID).'');
	  
  }
}