<?php

class CJS_Organisations_Orgs extends PerchAPI_Factory
{
  protected $table     = 'organisations';
  protected $pk        = 'organisationID';
  protected $singular_classname = 'CJS_Organisations_Org';	
  protected $default_sort_column = 'name';
  protected $default_sort_direction = 'ASC';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('organisationID', 'name', 'slug', 'nameTag', 'websiteURL', 'logoURL', 'organisationDynamicFields');	
  
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
  public function get_by_slug($slug){
    return $this->db->get_row('SELECT * FROM perch3_organisations WHERE slug='.$this->db->pdb($slug).'');
  }
  public function get_by_id($id){
    return $this->db->get_row('SELECT * FROM perch3_organisations WHERE organisationID='.$this->db->pdb($id).'');
  }
  
  public function get_master_t($id){
    $data = $this->db->get_row('SELECT * FROM perch3_organisations_t WHERE organisationID='.$this->db->pdb($id).'');
    return $data;
  }
  
  public function all_active(){
	$organisations = $this->db->get_row('SELECT * FROM perch3_organisations_list WHERE organisationsListID="1"');
	$list = json_decode($organisations['organisationsList'],true);
	return $list;
	  
  }
  
  public function all_active_jobs_vols(){
	$today = date('Y-m-d');
	$list = array();
	//GET ALL ORGANISATIONS
	$organisations = $this->db->get_rows("
	SELECT name, slug
	FROM perch3_organisations
	WHERE EXISTS (
		SELECT 1
		FROM perch3_employers
		JOIN perch3_daily_jobs ON perch3_daily_jobs.employerID = perch3_employers.employerID
		WHERE perch3_employers.organisationID = perch3_organisations.organisationID
		  AND perch3_daily_jobs.advertStatus = 'pub'
		  AND perch3_daily_jobs.closingDate > '$today'
	)
	OR EXISTS (
		SELECT 1
		FROM perch3_employers
		JOIN perch3_volunteers ON perch3_volunteers.employerID = perch3_employers.employerID
		WHERE perch3_employers.organisationID = perch3_organisations.organisationID
		  AND perch3_volunteers.advertStatus = 'pub'
		  AND perch3_volunteers.closingDate > '$today'
	)
	ORDER BY name ASC");

	foreach($organisations as $organisation){
		$list[] = array("name"=>$organisation['name'],"slug"=>$organisation['slug']);
	}

	return $list;
	  
  }
  
  public function org_list(){
	  $today = date('Y-m-d');
  
	  // EXISTS instead of LEFT JOINs — no row explosion, each subquery can use an index
	  $organisations = $this->db->get_rows('
		  SELECT o.name, o.slug FROM perch3_organisations o
		  WHERE EXISTS (
			  SELECT 1 FROM perch3_employers e
			  JOIN perch3_daily_jobs j ON j.employerID = e.employerID
			  WHERE e.organisationID = o.organisationID
			  AND j.advertStatus="pub" AND j.closingDate > '.$this->db->pdb($today).'
		  )
		  OR EXISTS (
			  SELECT 1 FROM perch3_employers e
			  JOIN perch3_volunteers v ON v.employerID = e.employerID
			  WHERE e.organisationID = o.organisationID
			  AND v.advertStatus="pub" AND v.closingDate > '.$this->db->pdb($today).'
		  )
		  OR EXISTS (
			  SELECT 1 FROM perch3_organisations_t t
			  JOIN perch3_long_courses lc ON lc.organisationID = t.organisationID
			  WHERE t.master_organisationID = o.organisationID AND lc.advertStatus="pub"
		  )
		  OR EXISTS (
			  SELECT 1 FROM perch3_organisations_t t
			  JOIN perch3_short_courses sc ON sc.organisationID = t.organisationID
			  WHERE t.master_organisationID = o.organisationID
			  AND sc.advertStatus="pub" AND sc.startDate > '.$this->db->pdb($today).'
		  )
		  OR EXISTS (
			  SELECT 1 FROM perch3_organisations_t t
			  JOIN perch3_events ev ON ev.organisationID = t.organisationID
			  WHERE t.master_organisationID = o.organisationID
			  AND ev.advertStatus="pub" AND ev.startDate > '.$this->db->pdb($today).'
		  )');
  
	  $timestamp = date('Y-m-d H:i:s');
  
	  // Fetch all published post tags ONCE instead of one query per org tag
	  $posts = $this->db->get_rows('SELECT postTags FROM perch3_blog_posts
		  WHERE postStatus="Published" AND postDateTime<'.$this->db->pdb($timestamp).'');
	  $allPostTags = implode(',', array_column($posts, 'postTags'));
  
	  $list = array();
	  $organisations2 = $this->db->get_rows('SELECT name, slug, nameTag FROM perch3_organisations');
	  foreach($organisations2 as $organisation){
		  foreach(explode(',', $organisation['nameTag']) as $tag){
			  $tag = trim($tag);
			  if($tag !== '' && stripos($allPostTags, $tag) !== false){
				  $list[] = array("name"=>ucfirst($organisation['name']), "slug"=>$organisation['slug']);
				  break; // one match is enough — original kept checking remaining tags
			  }
		  }
	  }
	  foreach($organisations as $organisation){
		  $list[] = array("name"=>ucfirst($organisation['name']), "slug"=>$organisation['slug']);
	  }
  
	  $list = array_map("unserialize", array_unique(array_map("serialize", $list)));
	  $columns = array_column($list, 'name');
	  array_multisort($columns, SORT_ASC, $list);
  
	  $json = json_encode($list);
	  $this->db->execute('UPDATE perch3_organisations_list
		  SET organisationsList="'.addslashes($json).'", timestamp="'.date('Y-m-d H:i:s').'"
		  WHERE organisationsListID="1"');
  }
  
  public function org_list_full(){

  	$today = date('Y-m-d');
	$organisations = $this->db->get_rows('SELECT * FROM perch3_organisations ORDER BY name ASC');

	return $organisations;
	
  }
  
  public function submitOrganisation($form){
	$captcha=$_POST['g-recaptcha-response'];
	$secretKey = (defined('CJS_RECAPTCHA_SECRET') ? CJS_RECAPTCHA_SECRET : '');
    $ip = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    // should return JSON with success as true
    if($responseKeys["success"]) {

        $data = $form->data;
        
        $name = $data['name'];
        $websiteURL = $data['websiteURL'];
        $logoURL = $data['logoURL'];
        
        $missionStatementText = $data['missionStatement'];
        $organisationOverviewText = $data['organisationOverview'];
        
        $missionStatement = array();
        $missionStatement['_flang'] = 'html';
        $missionStatement['raw'] = $missionStatementText;
        $missionStatement['processed'] = $missionStatementText;
        
        $organisationOverview = array();
        $organisationOverview['_flang'] = 'html';
        $organisationOverview['raw'] = $organisationOverviewText;
        $organisationOverview['processed'] = $organisationOverviewText;
        
        unset($data['g-recaptcha-response']);
        unset($data['name']);
        unset($data['websiteURL']);
        unset($data['logoURL']);
        
        $data['missionStatement'] = $missionStatement;
        $data['organisationOverview'] = $organisationOverview;
        
        $dynamicFields = PerchUtil::json_safe_encode($data);
        
        $this->db->execute('INSERT INTO perch3_organisations (name, websiteURL, logoURL, organisationDynamicFields) 
        	VALUES ('.$this->db->pdb($name).', '.$this->db->pdb($websiteURL).', '.$this->db->pdb($logoURL).', '.$this->db->pdb($dynamicFields).')');
		
		$organisation = $this->db->get_row('SELECT * FROM perch3_organisations ORDER BY organisationID DESC LIMIT 1');
		
		// Multiple recipients
		$to = 'ranger@countryside-jobs.com';
		
		$allFields = PerchUtil::json_safe_decode($dynamicFields, true);
		
		$fieldsHTML = '';
		foreach ($allFields as $fieldName => $fieldValue) {
			// Turn e.g. "missionStatement" into "Mission Statement"
			$label = ucwords(trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $fieldName)));
		
			if (is_array($fieldValue)) {
				// HTML fields store raw/processed values
				$value = isset($fieldValue['processed']) ? $fieldValue['processed'] : implode(', ', $fieldValue);
			} else {
				$value = htmlspecialchars(stripslashes($fieldValue));
			}
		
			$fieldsHTML .= '<p><strong>'.$label.':</strong><br />'.$value.'</p>';
		}
		
		// Message
		$emailHTML = "
		<html>
		<head>
		  <title>CJS Organisation Profile Submission</title>
		</head>
		<body>
		  <h2>Organisation Profile Submission</h2>
		  <p><strong>Name:</strong> ".stripslashes($name)."<br />
		  <strong>Website:</strong> ".$websiteURL."<br />
		  <strong>Logo:</strong> ".$logoURL."</p>
		  ".$fieldsHTML."
		  <p>View the Organisation <a href=\"https://www.countryside-jobs.com/perch/addons/apps/cjs_organisations/organisations/add_new/?id=".$organisation['organisationID']."\">here</a>.</p>
		  <p><em>Sent automatically by the website.</em></p>
		</body>
		</html>
		";
		
		// To send HTML mail, the Content-type header must be set
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		
		// Additional headers
		$headers[] = 'From: no-reply@countryside-jobs.com';
		$headers[] = 'Reply-To: '.$data['contactEmail'];
		
		// Mail it
		mail($to, 'Organisation Profile Submission', $emailHTML, implode("\r\n", $headers));
			
	}
  }
  
  public function updateSlug($org1, $org2){
	  
	  $orgData = $this->db->get_row('SELECT * FROM perch3_organisations WHERE organisationID='.$org1);
	  $this->db->execute('UPDATE perch3_organisations SET slug='.$this->db->pdb($orgData['slug']).' WHERE organisationID='.$org2);
	  
  }
}