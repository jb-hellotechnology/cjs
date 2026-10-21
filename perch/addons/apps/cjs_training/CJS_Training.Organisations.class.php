<?php

class CJS_Training_Organisations extends PerchAPI_Factory
{
  protected $table     = 'organisations_t';
	protected $pk        = 'organisationID';
	protected $singular_classname = 'CJS_Training_Organisation';
	
	protected $default_sort_column = 'name';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('organisationID', 'name', 'contactName', 'contacPhone', 'contactEmail', 'organisationDynamicFields');	
  
  public function updateProviders($org1,$org2){
    $this->db->execute('UPDATE perch3_providers SET organisationID='.$this->db->pdb($org2).' WHERE organisationID='.$this->db->pdb($org1).'');
  }
  public function updateShortCourses($org1,$org2){
    $this->db->execute('UPDATE perch3_short_courses SET organisationID='.$this->db->pdb($org2).' WHERE organisationID='.$this->db->pdb($org1).'');
  }
  public function updateLongCourses($org1,$org2){
    $this->db->execute('UPDATE perch3_long_courses SET organisationID='.$this->db->pdb($org2).' WHERE organisationID='.$this->db->pdb($org1).'');
  }
  public function updateEvents($org1,$org2){
    $this->db->execute('UPDATE perch3_events SET organisationID='.$this->db->pdb($org2).' WHERE organisationID='.$this->db->pdb($org1).'');
  }
  
  public function updateOffices_Organisations($organisationID,$organisationID2){
	  
	  $query = $this->db->execute('UPDATE perch3_organisations_t SET master_organisationID='.$this->db->pdb($organisationID2).' WHERE master_organisationID='.$this->db->pdb($organisationID).'');
	  
  }
  
  public function search($q){
	  
	  $data = $this->db->get_rows('SELECT * FROM perch3_organisations_t WHERE (name LIKE '.$this->db->pdb('%'.$q.'%').' OR contactName LIKE '.$this->db->pdb('%'.$q.'%').' OR contactPhone LIKE '.$this->db->pdb('%'.$q.'%').' OR contactEmail LIKE '.$this->db->pdb('%'.$q.'%').') ORDER BY name ASC');
	  return $data;
	  
  }
  
  public function get_by_organisation($id){
	  $rows = $this->db->get_rows('SELECT * FROM perch3_organisations_t  WHERE master_organisationID='.$this->db->pdb($id).'');
	  return $rows;
  }
}