<?php

class CJS_Daily_Employers extends PerchAPI_Factory
{
  protected $table     = 'employers';
	protected $pk        = 'employerID';
	protected $singular_classname = 'CJS_Daily_Employer';
	
	protected $default_sort_column = 'name';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('employerID', 'organisationID', 'name', 'slug', 'logo', 'contactName', 'contactJobTitle', 'contactPhone', 'contactEmail',
                                'doNotContact', 'employerDynamicFields');	   
  
  public function all_active(){
    $today = date('Y-m-d');
//     return $this->db->get_rows('SELECT perch3_employers.*,perch3_daily_jobs.* FROM perch3_daily_jobs,perch3_employers WHERE perch3_daily_jobs.advertStatus="pub" AND 
//     perch3_daily_jobs.postedDate>="'.$today.'" AND perch3_daily_jobs.closingDate>="'.$today.'" AND perch3_daily_jobs.employerID=perch3_employers.employerID 
//     GROUP BY perch3_employers.name');
    return $this->db->get_rows('SELECT * FROM perch3_organisations ORDER BY name ASC');
  }
  
  public function updateEmployers_Organisations($organisationID,$organisationID2){
	  
	  $query = $this->db->execute('UPDATE perch3_employers SET organisationID='.$this->db->pdb($organisationID2).' WHERE organisationID='.$this->db->pdb($organisationID).'');
	  
  }

}