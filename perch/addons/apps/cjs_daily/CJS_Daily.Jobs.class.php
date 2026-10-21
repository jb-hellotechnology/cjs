<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
class CJS_Daily_Jobs extends PerchAPI_Factory
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
  protected $table     = 'daily_jobs';
	protected $pk        = 'daily_jobID';
	protected $singular_classname = 'CJS_Daily_Job';
	
	protected $default_sort_column = 'daily_jobID';
	
  // THE STRUCTURE OF THE DATABASE TABLE
	public $static_fields = array('daily_jobID', 'title', 'employerDisplay', 'employerID', 'imageLogo', 'imageLogo2', 'imageLogo3', 'imageLogo4', 'imageLogo5', 'videoEmbed', 'imageAlt', 'postedDate', 
                                'closingDate', 'jobOfDay', 'jobOfDayDate', 'socialShares', 'socialSharesDate', 'cVolunteer', 'cApprentice', 'cIntern', 'cClosingDateEU', 'cDisplayDNotCDate', 'displayAd', 'displayAdURL', 
                                'advertAmendedDate', 'advertURL', 'region', 'sAdminIT', 'sArbo', 'sComVol', 'sCountry', 'sEco', 'sEnvOE',
                                'rNational', 'rInternational', 'rScotland', 'rScotlandNorth', 'rScotlandSouth', 'rWales', 'rNorthernIreland', 'rEngland', 'rAnglia', 'rMidlands', 
                                'rNorthEast', 'rNorthWest', 'rSouth', 'rSouthWest', 'sHorti', 'sPubF', 'sRoW', 'sVisMan', 'sWiWoAnC', 
                                'location', 'lat', 'lng', 'rCounty', 'grade', 'cfullTime', 'cpartTime', 'cpermanent', 'cTempFixed', 'cseasonal', 'advertStatus', 'postCode', 'baseSalary', 'salaryType', 'jobReference', 
                                'streetAddress', 'daily_jobDynamicFields');	
  
  public function get_sector($sector){
	$today = date('Y-m-d');
    $sql = 'SELECT * FROM perch3_daily_jobs WHERE '.$sector.'="1" AND closingDate>'.$this->db->pdb($today).' AND advertStatus="pub" ORDER BY postedDate DESC';
    return $this->db->get_rows($sql);
  }
  
  public function get_grade($grade){
	$today = date('Y-m-d');
	$options = explode(",", $grade);
	$query;
	foreach($options as $option){
		$query .= 'grade='.$this->db->pdb($option).' OR ';
	}
	$query = substr($query, 0, -3);
    $sql = 'SELECT * FROM perch3_daily_jobs WHERE ('.$query.') AND closingDate>'.$this->db->pdb($today).' AND advertStatus="pub" ORDER BY postedDate DESC';
    return $this->db->get_rows($sql);
  }
  
  public function get_region($wregion){
    $regions = [['scotland','rScotland'],['scotland-north','rScotlandNorth'],['scotland-south','rScotlandSouth'],['wales','rWales'],['northern-ireland','rNorthernIreland'],
                ['england','rEngland'],['england','rEngland'],['anglia','rAnglia'],['midlands','rMidlands'],
                ['north-east','rNorthEast'],['north-west','rNorthWest'],['south','rSouth'],['south-west','rSouthWest'],
                ['national','rNational'],['international','rInternational'],['home','rHome']];
    $today = date('Y-m-d');    
    foreach ($regions as $region){
      if ($wregion == $region[0]){
        $sql = 'SELECT * FROM perch3_daily_jobs WHERE '.$region[1].'="1" AND closingDate>'.$this->db->pdb($today).' AND advertStatus="pub" ORDER BY jobOfDayDate DESC, postedDate DESC';
      }
    }
    return $this->db->get_rows($sql);
    
  }
  
  public function get_advertiser($employerID){
    $today = date('Y-m-d');
    return $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE employerID='.$this->db->pdb($employerID).' AND closingDate>'.$this->db->pdb($today).' AND advertStatus="pub"');
  }
  
  public function get_organisation($slug){
    $today = date('Y-m-d');
    $advert = $this->db->get_row('SELECT * FROM perch3_daily_jobs WHERE advertURL='.$this->db->pdb($slug));
    if (!$advert) return false;
    $employer = $this->db->get_row('SELECT * FROM perch3_employers WHERE employerID='.$this->db->pdb($advert['employerID']));
    if (!$employer) return false;
    $organisation = $this->db->get_row('SELECT * FROM perch3_organisations WHERE organisationID='.$this->db->pdb($employer['organisationID']));
    return $organisation;
  }
  
  public function delete_job($id){
    return $this->db->execute('DELETE FROM perch3_daily_jobs WHERE daily_jobID='.$this->db->pdb($id).'');
  }
  
  public function get_all(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	

    $today = date('Y-m-d');
	$date = $today;
	
	$oldest = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-365, date('Y')));
	$weekago = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-7, date('Y')));
	$monthago = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-42, date('Y')));
	
	$volsDisplayed = false;
	
	while($date>$oldest){

		$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE (postedDate='.$this->db->pdb($date).' OR jobOfDayDate='.$this->db->pdb($date).') AND advertStatus="pub" AND closingDate>='.$this->db->pdb($today).' ORDER BY jobOfDayDate DESC, daily_jobID DESC');
		foreach($query as $data){

			/* THIS USED TO BE ON LINE 84 */
			/* if($data['jobOfDay']=='1' AND $data['jobOfDayDate']==$date AND $date>=$weekago){ */
			
			if($data['jobOfDay']=='1' AND $data['jobOfDayDate']==$date AND $date>=$weekago){
				//job of the day
				$Template->set('cjs_daily/job_of_day.html', 'cjs');
				$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);
		
				$data = array_merge($fields,$data);
				$html = $Template->render($data, true);
				$html = $Template->apply_runtime_post_processing($html, $data);  
				echo $html;
			}elseif($data['jobOfDay']=='1'){
				//job of the day
/*
				$Template->set('cjs_daily/job_of_day.html', 'cjs');
				$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);
		
				$data = array_merge($fields,$data);
				$html = $Template->render($data, true);
				$html = $Template->apply_runtime_post_processing($html, $data);  
				echo $html;
*/
				
				//normal advert
				$Template->set('cjs_daily/job_adverts.html', 'cjs');
				$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);
		
				$data = array_merge($fields,$data);
				$html = $Template->render($data, true);
				$html = $Template->apply_runtime_post_processing($html, $data);  
				echo $html;
			}

			if($data['jobOfDay']<>'1'){
				//normal advert
				$Template->set('cjs_daily/job_adverts.html', 'cjs');
				$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);
		
				$data = array_merge($fields,$data);
				$html = $Template->render($data, true);
				$html = $Template->apply_runtime_post_processing($html, $data);  
				echo $html;
			}
			
			$adverts = 1;
		}

		$dates = explode("-",$date);

		if($date>=$monthago){
			if(date("w",strtotime($date))==1){
				$start = $date;
				$end = date("Y-m-d", mktime(0, 0, 0, $dates[1], $dates[2]+7, $dates[0]));
				
				$vols = $this->db->get_rows('SELECT * FROM perch3_volunteers WHERE displayFrom>='.$this->db->pdb($date).' AND displayFrom<'.$this->db->pdb($end).' AND (closingDate>='.$this->db->pdb($today).' OR displayUntil>='.$this->db->pdb($today).') AND advertStatus="pub" AND typeRole<>"Apprentice" ORDER BY displayFrom ASC');
				if(count($vols)>0 AND $volsDisplayed==false){
					echo "<div class=\"textarea volunteer-jobs\">
							<p class=\"listing-title\"><strong>Volunteer posts advertised week beginning $dates[2]/$dates[1]/$dates[0]</strong></p>";
					foreach($vols as $volData){
						if($volData['typeRole']=='Regular'){
							if($volData['rNational']=='1'){$regionURL='national';}
							if($volData['rInternational']=='1'){$regionURL='international';}
							if($volData['rScotland']=='1'){$regionURL='scotland';}
							if($volData['rWales']=='1'){$regionURL='wales';}
							if($volData['rNorthernIreland']=='1'){$regionURL='northern-ireland';}
							if($volData['rEngland']=='1'){$regionURL='england';}
							if($volData['rAnglia']=='1'){$regionURL='anglia';}
							if($volData['rMidlands']=='1'){$regionURL='midlands';}
							if($volData['rNorthEast']=='1'){$regionURL='north-east';}
							if($volData['rNorthWest']=='1'){$regionURL='north-west';}
							if($volData['rSouth']=='1'){$regionURL='south';}
							if($volData['rSouthWest']=='1'){$regionURL='south-west';}
							echo "<p><a href=\"/volunteers/region/$regionURL\">$volData[title]</a>, $volData[employerDisplay]</p>";
						}else{
							if($volData['sAdminIT']=='1'){$sectorURL='admin';}
							if($volData['sArbo']=='1'){$sectorURL='arboriculture';}
							if($volData['sComVol']=='1'){$sectorURL='community-and-volunteers';}
							if($volData['sCountry']=='1'){$sectorURL='countryside';}
							if($volData['sEco']=='1'){$sectorURL='ecology';}
							if($volData['sEnvOE']=='1'){$sectorURL='environmental-and-outdoor-education';}
							if($volData['sHorti']=='1'){$sectorURL='horticulture';}
							if($volData['sPubF']=='1'){$sectorURL='publicity-and-fundraising';}
							if($volData['sRoW']=='1'){$sectorURL='rights-of-way';}
							if($volData['sVisMan']=='1'){$sectorURL='visitor-management';}
							if($volData['sWiWoAnC']=='1'){$sectorURL='wildlife-work-and-animal-care';}
							echo "<p><a href=\"/volunteers/sector/$sectorURL\">$volData[title]</a>, $volData[employerDisplay]</p>";
						}
					}
					echo "<p class=\"margin\">To view any post click on the job title.</p>
					<p><a class=\"more\" href=\"http://www.countryside-jobs.com/volunteers/\"><strong>All Volunteer Adverts Here</strong></a></p></div>";
					$volsDisplayed = true;
				}
			}	
		
		}
		
		$date = date("Y-m-d", mktime(0, 0, 0, $dates[1], $dates[2]-1, $dates[0]));
		
	}
  }
  
  public function get_all_feed(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	

    $today = date('Y-m-d');
	$date = $today;

	$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($date).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' ORDER BY daily_jobID DESC');
	foreach($query as $data){

		$Template->set('cjs_daily/job_adverts_feed.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);

		$data = array_merge($fields,$data);
		$html = $Template->render($data, true);
		$html = $Template->apply_runtime_post_processing($html, $data);  
		echo $html;
		
	}
  }
  
  public function get_sector_feed($sector){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	

    $today = date('Y-m-d');
	$date = $today;

	$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($date).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' AND '.$sector.'="1" ORDER BY daily_jobID DESC');
	foreach($query as $data){

		$Template->set('cjs_daily/job_adverts_feed.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);

		$data = array_merge($fields,$data);
		$html = $Template->render($data, true);
		$html = $Template->apply_runtime_post_processing($html, $data);  
		echo $html;
		
	}
  }
  
  public function get_region_feed($region){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	

    $today = date('Y-m-d');
	$date = $today;

	$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($date).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' AND '.$region.'="1" ORDER BY daily_jobID DESC');
	foreach($query as $data){

		$Template->set('cjs_daily/job_adverts_feed.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);

		$data = array_merge($fields,$data);
		$html = $Template->render($data, true);
		$html = $Template->apply_runtime_post_processing($html, $data);  
		echo $html;
		
	}
  }
  
  public function get_all_home(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	
    $today = date('Y-m-d');
	$date = $today;
	$html = '';
	$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($date).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' ORDER BY daily_jobID DESC LIMIT 15');
	foreach($query as $data){

		$Template->set('cjs_daily/job_adverts_home.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);

		$data = array_merge($fields,$data);
		$aHtml = $Template->render($data, true);
		$aHtml = $Template->apply_runtime_post_processing($aHtml, $data);  
		$html = $html.$aHtml;

	}
	
	return $html;
  }
  
  public function get_all_sitemap(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	

    $today = date('Y-m-d');
	$date = $today;

	$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($date).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' ORDER BY daily_jobID DESC');
	foreach($query as $data){

		$Template->set('cjs_daily/job_adverts_sitemap.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);

		$data = array_merge($fields,$data);
		$html = $Template->render($data, true);
		$html = $Template->apply_runtime_post_processing($html, $data);  
		echo $html;
		
	}
  }
  
  public function get_all_feed_2(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	
    $today = date('Y-m-d');
	$date = $today;

	$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($date).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' ORDER BY daily_jobID DESC');
	foreach($query as $data){

		$Template->set('cjs_daily/job_adverts_feed_2.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);

		$data = array_merge($fields,$data);
		$html = $Template->render($data, true);
		$html = $Template->apply_runtime_post_processing($html, $data);  
		echo $html;
		
	}
  }
  
  public function get_by_organisation($id){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$jobs = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');

    $today = date('Y-m-d');
	$date = $today;
	
	$employers = $this->db->get_rows('SELECT * FROM perch3_employers WHERE organisationID='.$this->db->pdb($id).'');
	
	$results = array();
	
	foreach($employers AS $employer){
	
		$results = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($date).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' AND employerID='.$this->db->pdb($employer['employerID']).' ORDER BY daily_jobID DESC');
		if(count($results)>0){
			echo "<div class=\"container margin-top\"><h3 class=\"no-margin\">Paid Posts</h3></div>";
		}
		foreach($results as $data){
			$Template->set('cjs_daily/job_adverts.html', 'cjs');
			$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);
	
			$data = array_merge($fields,$data);
			$html = $Template->render($data, true);
			$html = $Template->apply_runtime_post_processing($html, $data);  
			echo $html;
		}
		
	}
	
  }
  
  public function get_volunteers_by_organisation($id){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$jobs = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');

	$today = date('Y-m-d');
	
	$employers = $this->db->get_rows('SELECT * FROM perch3_employers WHERE organisationID='.$this->db->pdb($id).'');
	
	$results = array();
	
	foreach($employers AS $employer){
	
		$vols = $this->db->get_rows('SELECT * FROM perch3_volunteers WHERE (closingDate>'.$this->db->pdb($today).' OR displayUntil>='.$this->db->pdb($today).') AND advertStatus="pub" AND typeRole<>"Apprentice" AND employerID='.$this->db->pdb($employer['employerID']).' ORDER BY displayFrom ASC');

		if(count($vols)>0){
			echo "<section class=\"listing\">
					<h3 class=\"no-margin\">Volunteer Posts</h3>";
			foreach($vols as $volData){
				if($volData['typeRole']=='Regular'){
					if($volData['rNational']=='1'){$regionURL='national';}
					if($volData['rInternational']=='1'){$regionURL='international';}
					if($volData['rScotland']=='1'){$regionURL='scotland';}
					if($volData['rWales']=='1'){$regionURL='wales';}
					if($volData['rNorthernIreland']=='1'){$regionURL='northern-ireland';}
					if($volData['rEngland']=='1'){$regionURL='england';}
					if($volData['rAnglia']=='1'){$regionURL='anglia';}
					if($volData['rMidlands']=='1'){$regionURL='midlands';}
					if($volData['rNorthEast']=='1'){$regionURL='north-east';}
					if($volData['rNorthWest']=='1'){$regionURL='north-west';}
					if($volData['rSouth']=='1'){$regionURL='south';}
					if($volData['rSouthWest']=='1'){$regionURL='south-west';}
					echo "<p><a href=\"/volunteers/region/$regionURL#vol_$volData[volunteerID]\">$volData[title]</a>, $volData[employerDisplay]</p>";
				}else{
					if($volData['sAdminIT']=='1'){$sectorURL='admin';}
					if($volData['sArbo']=='1'){$sectorURL='arboriculture';}
					if($volData['sComVol']=='1'){$sectorURL='community-and-volunteers';}
					if($volData['sCountry']=='1'){$sectorURL='countryside';}
					if($volData['sEco']=='1'){$sectorURL='ecology';}
					if($volData['sEnvOE']=='1'){$sectorURL='environmental-and-outdoor-education';}
					if($volData['sHorti']=='1'){$sectorURL='horticulture';}
					if($volData['sPubF']=='1'){$sectorURL='publicity-and-fundraising';}
					if($volData['sRoW']=='1'){$sectorURL='rights-of-way';}
					if($volData['sVisMan']=='1'){$sectorURL='visitor-management';}
					if($volData['sWiWoAnC']=='1'){$sectorURL='wildlife-work-and-animal-care';}
					echo "<p><a href=\"/volunteers/sector/$sectorURL#vol_$volData[volunteerID]\">$volData[title]</a>, $volData[employerDisplay]</p>";
				}
			}
			echo "</section>";
		}
		
	}
	
  }
  
  public function get_published($order){
	  $date = date("Y-m-d", mktime(0, 0, 0, date('m')-4, date('d'), date('Y')));
	  if($order<>''){
		  return $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE advertStatus="pub" AND closingDate>='.$this->db->pdb($date).' ORDER BY '.$order.' ASC');
	  }else{
		return $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE advertStatus="pub" AND closingDate>='.$this->db->pdb($date).' ORDER BY postedDate DESC');  
	  }
  }
  
  public function get_suspended($order){
    if($order<>''){
		  return $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE advertStatus="sus" ORDER BY '.$order.' ASC');
	  }else{
		return $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE advertStatus="sus"');  
	  }
  }
  
  public function get_pending($order){
    if($order<>''){
		  return $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE (advertStatus="pen" OR advertStatus="over") ORDER BY '.$order.' ASC');
	  }else{
		return $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE (advertStatus="pen" OR advertStatus="over")');  
	  }
  }
  
//   public function submitDailyAdvert($data){
// 	$captcha=$_POST['g-recaptcha-response'];
// 	$secretKey = (defined('CJS_RECAPTCHA_SECRET') ? CJS_RECAPTCHA_SECRET : '');
//     $ip = $_SERVER['REMOTE_ADDR'];
//     // post request to server
//     $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
//     $response = file_get_contents($url);
//     $responseKeys = json_decode($response,true);
//     // should return JSON with success as true
//     if($responseKeys["success"]) {
// 	    $type = $data->data['advertType'];
// 	    $title = $this->db->pdb($data->data['jobtitle']);
// 	    $advertText = $data->data['form_text'];
// 	    
// 	    $memberID = $this->db->pdb($data->data['memberID']);
// 	    $advertID = $this->db->pdb($data->data['advertID']);
// 	    $advertURL = $this->db->pdb($data->data['advertURL']);
// 	    $employer = $this->db->pdb($data->data['employer']);
// 	    $employerDisplay = $this->db->pdb($data->data['employerDisplay']);
// 	    $cfullTime = $this->db->pdb($data->data['form_cfullTime']);
// 	    $cpartTime = $this->db->pdb($data->data['form_cpartTime']);
// 	    $cpermanent = $this->db->pdb($data->data['form_cpermanent']);
// 	    $cTempFixed = $this->db->pdb($data->data['form_cTempFixed']);
// 	    $cseasonal = $this->db->pdb($data->data['form_cseasonal']);
// 	    $contractLength = $this->db->pdb($data->data['contractLength']);
// 	    $closingDate = $_POST['closingDate'];
// 	    $dates = explode("/", $closingDate);
// 	    $closingDate = $this->db->pdb("$dates[2]-$dates[1]-$dates[0]");
// 	    $location = $this->db->pdb($data->data['location']);
// 		$lat = $this->db->pdb($data->data['lat']);
// 		$lng = $this->db->pdb($data->data['lng']);
// 	    
// 	    $sAdminIT = $this->db->pdb($data->data['form_sAdminIT']);
// 	    $sArbo = $this->db->pdb($data->data['form_sArbo']);
// 	    $sComVol = $this->db->pdb($data->data['form_sComVol']);
// 	    $sCountry = $this->db->pdb($data->data['form_sCountry']);
// 	    $sEco = $this->db->pdb($data->data['form_sEco']);
// 	    $sEnvOE = $this->db->pdb($data->data['form_sEnvOE']);
// 	    $sHorti = $this->db->pdb($data->data['form_sHorti']);
// 	    $sPubF = $this->db->pdb($data->data['form_sPubF']);
// 	    $sRoW = $this->db->pdb($data->data['form_sRoW']);
// 	    $sVisMan = $this->db->pdb($data->data['form_sVisMan']);
// 	    $sWiWoAnC = $this->db->pdb($data->data['form_sWiWoAnC']);
// 	    $sSustainability = $this->db->pdb($data->data['form_sSustainability']);
// 	    $sPolicy = $this->db->pdb($data->data['form_sPolicy']);
// 	    $sFieldwork = $this->db->pdb($data->data['form_sFieldwork']);
// 	    $sPractical = $this->db->pdb($data->data['form_sPractical']);
// 	    $sForestry = $this->db->pdb($data->data['form_sForestry']);
// 	    
// 	    $rNational = $this->db->pdb($data->data['form_rNational']);
// 	    $rInternational = $this->db->pdb($data->data['form_rInternational']);
// 	    $rScotlandNorth = $this->db->pdb($data->data['form_rScotlandNorth']);
// 	    $rScotlandSouth = $this->db->pdb($data->data['form_rScotlandSouth']);
// 	    $rWales = $this->db->pdb($data->data['form_rWales']);
// 	    $rNorthernIreland = $this->db->pdb($data->data['form_rNorthernIreland']);
// 	    $rEngland = $this->db->pdb($data->data['form_rEngland']);
// 	    $rAnglia = $this->db->pdb($data->data['form_rAnglia']);
// 	    $rMidlands = $this->db->pdb($data->data['form_rMidlands']);
// 	    $rNorthEast = $this->db->pdb($data->data['form_rNorthEast']);
// 	    $rNorthWest = $this->db->pdb($data->data['form_rNorthWest']);
// 	    $rSouth = $this->db->pdb($data->data['form_rSouth']);
// 	    $rSouthWest = $this->db->pdb($data->data['form_rSouthWest']);
// 	    $rHome = $this->db->pdb($data->data['form_rHome']);
// 		
// 		$jobOfDay = $this->db->pdb($data->data['jod']);
// 		$jobOfDayDate = $_POST['jodDate'];
// 		$dates = explode("/", $jobOfDayDate);
// 		$jobOfDayDate = $this->db->pdb("$dates[2]-$dates[1]-$dates[0]");
// 		
// 		$socialShares = $this->db->pdb($data->data['socialshares']);
// 		$socialSharesDate = $_POST['socialsharesDate'];
// 		$dates = explode("/", $socialSharesDate);
// 		$socialSharesDate = $this->db->pdb("$dates[2]-$dates[1]-$dates[0]");
// 
// 	    $video = $data->data['video'];
// 	    $unpaid = $data->data['unpaid'];
// 	    $professional = $data->data['professional'];
// 		$weekly = $data->data['weekly'];
// 		
// 	    $invoiceaddress = $data->data['invoiceaddress'];
// 	    $reference = $data->data['reference'];
// 	    $payment = $data->data['payment'];
// 	    $name = $data->data['name'];
// 	    $organisation = $data->data['organisation'];
// 	    $emailAddress = $data->data['email'];
// 	    $telephone = $data->data['telephone'];
// 	    $message = $data->data['message'];
// 	    $status = $data->data['status'];
// 	    if($status==''){
// 		    $status = 'pen';
// 	    }
// 	    
// 	    $advertType = $data->data['advertType'];
// 	    
// 	    $textPlain = nl2br(strip_tags($data->data['text']));
// 	    $words = explode(" ",$textPlain);
// 	    $i = 0;
// 	    while($i < 100){
// 		    $paragraph .= $words[$i]." ";
// 		    $i++;
// 	    }
// 		$shorttext = $paragraph;
// 		
// 		$fields = '';
// 		foreach ($data->data as $key => $value) {
// $fields .= htmlspecialchars($key).": ".htmlspecialchars($value)."
// ";
// 		}
// 		
// 		$submissionDate = date('Y-m-d H:i:s');
// 		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/perch/addons/apps/cjs_daily/submissions/fields-'.$submissionDate.'.txt', $fields);
// 	    
// 	    $jsonData = array();
// 	    
// 	    $data = $data->data;
// 
// 		preg_match_all('/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>([^<]+)<\/a>/i', $advertText, $out);
// 		
// 		foreach($out[1] as $item){
// 			$long_url = str_replace('" target="_blank', '', $item);
// 
// 			$apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
// 			$genericAccessToken = (defined('CJS_BITLY_TOKEN') ? CJS_BITLY_TOKEN : '');
// 			
// 			$bitlyPayload = array('long_url' => $long_url); // renamed
// 			$payload = json_encode($bitlyPayload);
// 			
// 			$header = array(
// 			    'Authorization: Bearer ' . $genericAccessToken,
// 			    'Content-Type: application/json',
// 			    'Content-Length: ' . strlen($payload)
// 			);
// 			
// 			$ch = curl_init($apiv4);
// 			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
// 			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
// 			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
// 			$result = curl_exec($ch);
// 			
// 			$result = json_decode($result,true);
// 			$bitly = $result['link'] ?? $long_url; // fall back to original URL if Bitly fails
// 			$advertText = str_replace($long_url, $bitly, $advertText);
// 		}
// 		
// 		$text = $this->db->pdb($advertText);
// 
// 		$jsonData['socialmediapromotion'] = $socialmediapromotion;
// 		$jsonData['video'] = $video;
// 		$jsonData['unpaid'] = $unpaid;
// 		$jsonData['professional'] = $professional;
// 		$jsonData['weekly'] = $weekly;
// 		$jsonData['invoiceaddress'] = $invoiceaddress;
// 		$jsonData['reference'] = $reference;
// 		$jsonData['payment'] = $payment;
// 		$jsonData['name'] = $name;
// 		$jsonData['organisation'] = $organisation;
// 		$jsonData['email'] = $emailAddress;
// 		$jsonData['telephone'] = $telephone;
// 		$jsonData['message'] = $message;
// 		$jsonData['howdidyoufindus'] = $howdidyoufindus;
// 		$jsonData['advertType'] = $advertType;
// 	    
// 	    $json = json_encode($jsonData,JSON_HEX_APOS);
// 	    
// 	    $dynamicFields = $json;
// 		
// 		//FILE 1
// 		if($_FILES['logo']['tmp_name']){
// 			$rand = rand();
// 			$logo1 = $rand.".jpg";
// 			move_uploaded_file($_FILES['logo']['tmp_name'], "../../../advert-uploads/".$logo1);
// 
// 			//RESIZE
// 			$fn = "../../../advert-uploads/".$logo1;
// 			$size = getimagesize($fn);
// 			$ratio = $size[0]/$size[1]; // width/height
// 			$width = 300;
// 			$height = 300/$ratio;
// 			$src = imagecreatefromstring(file_get_contents($fn));
// 			$dst = imagecreatetruecolor($width,$height);
// 			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
// 			imagedestroy($src);
// 			imagejpeg($dst,"../../../advert-uploads/".$logo1); // adjust format as needed
// 			imagedestroy($dst);
// 			
// 			$logo1 = "/advert-uploads/".$logo1;
// 		}else{
// 			$logo1 = $data['logo1_hidden'];
// 		}
// 		
// 		//FILE 2
// 		if($_FILES['logo2']['tmp_name']){
// 			$rand = rand();
// 			$logo2 = $rand.".jpg";
// 			move_uploaded_file($_FILES['logo2']['tmp_name'], "../../../advert-uploads/".$logo2);
// 			
// 			//RESIZE
// 			$fn = "../../../advert-uploads/".$logo2;
// 			$size = getimagesize($fn);
// 			$ratio = $size[0]/$size[1]; // width/height
// 			$width = 300;
// 			$height = 300/$ratio;
// 			$src = imagecreatefromstring(file_get_contents($fn));
// 			$dst = imagecreatetruecolor($width,$height);
// 			imagetruecolortopalette($dst, false, 255);
// 			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
// 			imagedestroy($src);
// 			imagejpeg($dst,"../../../advert-uploads/".$logo2); // adjust format as needed
// 			imagedestroy($dst);
// 			
// 			$logo2 = "/advert-uploads/".$logo2;
// 		}else{
// 			$logo2 = $data['logo2_hidden'];
// 		}
// 		
// 		//FILE 3
// 		if($_FILES['logo3']['tmp_name']){
// 			$rand = rand();
// 			$logo3 = $rand.".jpg";
// 			move_uploaded_file($_FILES['logo3']['tmp_name'], "../../../advert-uploads/".$logo3);
// 			
// 			//RESIZE
// 			$fn = "../../../advert-uploads/".$logo3;
// 			$size = getimagesize($fn);
// 			$ratio = $size[0]/$size[1]; // width/height
// 			$width = 300;
// 			$height = 300/$ratio;
// 			$src = imagecreatefromstring(file_get_contents($fn));
// 			$dst = imagecreatetruecolor($width,$height);
// 			imagetruecolortopalette($dst, false, 255);
// 			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
// 			imagedestroy($src);
// 			imagejpeg($dst,"../../../advert-uploads/".$logo3); // adjust format as needed
// 			imagedestroy($dst);
// 			
// 			$logo3 = "/advert-uploads/".$logo3;
// 		}else{
// 			$logo3 = $data['logo3_hidden'];
// 		}
// 		
// 		//FILE 4
// 		if($_FILES['logo4']['tmp_name']){
// 			$rand = rand();
// 			$logo4 = $rand.".jpg";
// 			move_uploaded_file($_FILES['logo4']['tmp_name'], "../../../advert-uploads/".$logo4);
// 			
// 			//RESIZE
// 			$fn = "../../../advert-uploads/".$logo4;
// 			$size = getimagesize($fn);
// 			$ratio = $size[0]/$size[1]; // width/height
// 			$width = 300;
// 			$height = 300/$ratio;
// 			$src = imagecreatefromstring(file_get_contents($fn));
// 			$dst = imagecreatetruecolor($width,$height);
// 			imagetruecolortopalette($dst, false, 255);
// 			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
// 			imagedestroy($src);
// 			imagejpeg($dst,"../../../advert-uploads/".$logo4); // adjust format as needed
// 			imagedestroy($dst);
// 			
// 			$logo4 = "/advert-uploads/".$logo4;
// 		}else{
// 			$logo4 = $data['logo4_hidden'];
// 		}
// 		
// 		//FILE 3
// 		if($_FILES['logo5']['tmp_name']){
// 			$rand = rand();
// 			$logo5 = $rand.".jpg";
// 			move_uploaded_file($_FILES['logo5']['tmp_name'], "../../../advert-uploads/".$logo5);
// 			
// 			//RESIZE
// 			$fn = "../../../advert-uploads/".$logo5;
// 			$size = getimagesize($fn);
// 			$ratio = $size[0]/$size[1]; // width/height
// 			$width = 300;
// 			$height = 300/$ratio;
// 			$src = imagecreatefromstring(file_get_contents($fn));
// 			$dst = imagecreatetruecolor($width,$height);
// 			imagetruecolortopalette($dst, false, 255);
// 			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
// 			imagedestroy($src);
// 			imagejpeg($dst,"../../../advert-uploads/".$logo5); // adjust format as needed
// 			imagedestroy($dst);
// 			
// 			$logo5 = "/advert-uploads/".$logo5;
// 		}else{
// 			$logo5 = $data['logo5_hidden'];
// 		}
// 		
// 		$API  = new PerchAPI(1.0, 'perch_members');
// 		$Members = new PerchMembers_Members($API);
// 		$Session = PerchMembers_Session::fetch();
// 		$Member = $Members->find($Session->get('memberID'));
// 		$Properties = new stdClass();
// 		
// 		$email = false;
// 		
// 		$month = strtolower(date('M')).date('y');
// 		
// 		$delimiter = '-';
// 		$advertURL = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $title))))), $delimiter));
// 		$advertURL = "/job/".$month."/".$advertURL;
// 		
// 		$postedDate = $this->db->pdb(date('Y-m-d'));
// 		
// 		if($advertID>0){
// 			
// 			if($status=='draft'){
// 				$this->db->execute("UPDATE perch3_daily_jobs SET title=$title, advertText=$text, employerDisplay=$employerDisplay, postedDate='$postedDate', closingDate=$closingDate, sAdminIT=$sAdminIT, sArbo=$sArbo, sComVol=$sComVol, sCountry=$sCountry, sEco=$sEco, sEnvOE=$sEnvOE, rNational=$rNational, rInternational=$rInternational, rScotlandNorth=$rScotlandNorth, rScotlandSouth=$rScotlandSouth, rWales=$rWales, rNorthernIreland=$rNorthernIreland, rEngland=$rEngland, rAnglia=$rAnglia, rMidlands=$rMidlands, rNorthEast=$rNorthEast, rNorthWest=$rNorthWest, rSouth=$rSouth, rSouthWest=$rSouthWest, rHome=$rHome, sHorti=$sHorti, sPubF=$sPubF, sRow=$sRoW, sVisMan=$sVisMan, sWiWoAnC=$sWiWoAnC, sSustainability=$sSustainability, sPolicy=$sPolicy, sFieldwork=$sFieldwork, sPractical=$sPracitcal, sForestry=$Forestry, location=$location, lat=$lat, lng=$lng, imageLogo='$logo1', imageLogo2='$logo2', imageLogo3='$logo3', imageLogo4='$logo4', imageLogo5='$logo5', advertStatus='$status', daily_jobDynamicFields='$dynamicFields', contractLength=$contractLength, cfullTime=$cfullTime, cpartTime=$cpartTime, cpermanent=$cpermanent, cTempFixed=$cTempFixed, cseasonal=$cseasonal, jobOfDay=$jobOfDay, jobOfDayDate=$jobOfDayDate, socialShares=$socialShares, socialSharesDate=$socialSharesDate WHERE daily_jobID=$advertID");
// 			}elseif($status=='pen'){
// 				$this->db->execute("UPDATE perch3_daily_jobs SET title=$title, advertText=$text, employerDisplay=$employerDisplay, postedDate='$postedDate', closingDate=$closingDate, sAdminIT=$sAdminIT, sArbo=$sArbo, sComVol=$sComVol, sCountry=$sCountry, sEco=$sEco, sEnvOE=$sEnvOE, rNational=$rNational, rInternational=$rInternational, rScotlandNorth=$rScotlandNorth, rScotlandSouth=$rScotlandSouth, rWales=$rWales, rNorthernIreland=$rNorthernIreland, rEngland=$rEngland, rAnglia=$rAnglia, rMidlands=$rMidlands, rNorthEast=$rNorthEast, rNorthWest=$rNorthWest, rSouth=$rSouth, rSouthWest=$rSouthWest, rHome=$rHome, sHorti=$sHorti, sPubF=$sPubF, sRow=$sRoW, sVisMan=$sVisMan, sWiWoAnC=$sWiWoAnC, sSustainability=$sSustainability, sPolicy=$sPolicy, sFieldwork=$sFieldwork, sPractical=$sPracitcal, sForestry=$Forestry, location=$location, lat=$lat, lng=$lng, imageLogo='$logo1', imageLogo2='$logo2', imageLogo3='$logo3', imageLogo4='$logo4', imageLogo5='$logo5', advertStatus='$status', daily_jobDynamicFields='$dynamicFields', contractLength=$contractLength, cfullTime=$cfullTime, cpartTime=$cpartTime, cpermanent=$cpermanent, cTempFixed=$cTempFixed, cseasonal=$cseasonal, jobOfDay=$jobOfDay, jobOfDayDate=$jobOfDayDate, socialShares=$socialShares, socialSharesDate=$socialSharesDate WHERE daily_jobID=$advertID");
// 				$email = true;
// 				$subject = "CJS Daily Submission - Pending";
// 			}elseif($status=='pub'){
// 				if(perch_member_has_tag('package')){
// 					if($advertType=='Basic'){
// 						if($_SESSION['basiccredits']>0){
// 							$Properties->data = array(
// 							     'basiccredits' => $_SESSION['basiccredits']-1,
// 							);
// 							$Member->update_profile($Properties);
// 							$_SESSION['basiccredits'] = $_SESSION['basiccredits']-1;
// 						}else{
// 							$status = 'draft';
// 						}
// 					}
// 					if($advertType=='Standard'){
// 						if($_SESSION['standardcredits']>0){
// 							$Properties->data = array(
// 							     'standardcredits' => $_SESSION['standardcredits']-1,
// 							);
// 							$Member->update_profile($Properties);
// 							$_SESSION['standardcredits'] = $_SESSION['standardcredits']-1;
// 						}else{
// 							$status = 'draft';
// 						}
// 					}
// 					if($advertType=='Unlimited'){
// 						if($_SESSION['unlimitedcredits']>0){
// 							$Properties->data = array(
// 							     'unlimitedcredits' => $_SESSION['unlimitedcredits']-1,
// 							);
// 							$Member->update_profile($Properties);
// 							$_SESSION['unlimitedcredits'] = $_SESSION['unlimitedcredits']-1;
// 						}else{
// 							$status = 'draft';
// 						}
// 					}
// 				}
// 				$this->db->execute("UPDATE perch3_daily_jobs SET title=$title, advertText=$text, employerDisplay=$employerDisplay, postedDate=$postedDate, closingDate=$closingDate, sAdminIT=$sAdminIT, sArbo=$sArbo, sComVol=$sComVol, sCountry=$sCountry, sEco=$sEco, sEnvOE=$sEnvOE, rNational=$rNational, rInternational=$rInternational, rScotlandNorth=$rScotlandNorth, rScotlandSouth=$rScotlandSouth, rWales=$rWales, rNorthernIreland=$rNorthernIreland, rEngland=$rEngland, rAnglia=$rAnglia, rMidlands=$rMidlands, rNorthEast=$rNorthEast, rNorthWest=$rNorthWest, rSouth=$rSouth, rSouthWest=$rSouthWest, rHome=$rHome, sHorti=$sHorti, sPubF=$sPubF, sRow=$sRoW, sVisMan=$sVisMan, sWiWoAnC=$sWiWoAnC, sSustainability=$sSustainability, sPolicy=$sPolicy, sFieldwork=$sFieldwork, sPractical=$sPracitcal, sForestry=$Forestry, location=$location, lat=$lat, lng=$lng, imageLogo='$logo1', imageLogo2='$logo2', imageLogo3='$logo3', imageLogo4='$logo4', imageLogo5='$logo5', advertStatus='$status', daily_jobDynamicFields='$dynamicFields', contractLength=$contractLength, cfullTime=$cfullTime, cpartTime=$cpartTime, cpermanent=$cpermanent, cTempFixed=$cTempFixed, cseasonal=$cseasonal, jobOfDay=$jobOfDay, jobOfDayDate=$jobOfDayDate, socialShares=$socialShares, socialSharesDate=$socialSharesDate WHERE daily_jobID=$advertID");
// 				$email = true;
// 				$subject = "CJS Daily Submission - Live";
// 			}elseif($status=='over'){
// 				$query = "SELECT * FROM perch3_daily_jobs WHERE advertStatus='pub' AND daily_jobID='$advertID'";
// 				$result =  $this->db->get_rows($query);
// 				$advertURL = $result[0]['advertURL'];
// 				$this->db->execute("INSERT INTO perch3_daily_jobs (title, advertText, memberID, advertSlug, employerDisplay, employerID, imageLogo, imageLogo2, imageLogo3, imageLogo4, imageLogo5, imageAlt, imageAlt2, imageAlt3, imageAlt4, imageAlt5, videoEmbed, postedDate, closingDate, jobOfDay, jobOfDayDate, cVolunteer, cApprentice, cIntern, cClosingDateEU, cDisplayDNotCDate, displayAd, displayAdURL, advertAmendedDate, advertURL, sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, sSustainability, sPolicy, sFieldwork, sPractical, sForestry, rNational, rInternational, rScotlandNorth, rScotlandSouth, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rHome, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, location, lat, lng, rCounty, grade, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, advertStatus, postCode, baseSalary, salaryType, jobReference, streetAddress, daily_jobDynamicFields, contractLength, jobOfDay, jobOfDayDate, socialShares, socialSharesDate) SELECT $title, $text, memberID, advertSlug, $employerDisplay, employerID, '$logo1', '$logo2', '$logo3', '$logo4', '$logo5', imageAlt, imageAlt2, imageAlt3, imageAlt4, imageAlt5, videoEmbed, postedDate, closingDate, jobOfDay, jobOfDayDate, cVolunteer, cApprentice, cIntern, cClosingDateEU, cDisplayDNotCDate, displayAd, displayAdURL, advertAmendedDate, advertURL, $sAdminIT, $sArbo, $sComVol, $sCountry, $sEco, $sEnvOE, $sSustainability, $sPolicy, $sFieldwork, $sPractical, $sForestry, $rNational, $rInternational, $rScotlandNorth, $rScotlandSouth, $rWales, $rNorthernIreland, $rEngland, $rAnglia, $rMidlands, $rNorthEast, $rNorthWest, $rSouth, $rSouthWest, $rHome, $sHorti, $sPubF, $sRoW, $sVisMan, $sWiWoAnC, $location, $lat, $lng, rCounty, grade, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, 'over', postCode, baseSalary, salaryType, jobReference, streetAddress, '$dynamicFields', $jobOfDay, $jobOfDayDate, $socialShares, $socialSharesDate, contractLength FROM perch3_daily_jobs WHERE daily_jobID = $advertID");
// 				$email = true;
// 				$subject = "CJS Daily Submission - Update";
// 			}
// 			
// 		}else{
// 		
// 			if($status=='draft'){
// 
// 			}elseif($status=='pen'){
// 				$email = true;
// 				$subject = "CJS Daily Submission - Pending";
// 			}elseif($status=='over'){
// 				$email = true;
// 				$subject = "CJS Daily Submission - Update";
// 			}elseif($status=='pub'){
// 				$email = true;
// 				$subject = "CJS Daily Submission - Published";
// 			}else{
// 				$email = true;
// 				$subject = "CJS Daily Submission";
// 			}
// 			
// 			$this->db->execute("INSERT INTO perch3_daily_jobs (title, advertText, memberID, employerDisplay, postedDate, closingDate, sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, rNational, rInternational, rScotlandNorth, rScotlandSouth, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rHome, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, sSustainability, sPolicy, sFieldwork, sPractical, sForestry, location, lat, lng, imageLogo, imageLogo2, imageLogo3, imageLogo4, imageLogo5, advertURL, advertStatus, daily_jobDynamicFields, contractLength, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, jobOfDay, jobOfDayDate, socialShares, socialSharesDate) VALUES ($title, $text, $memberID, $employerDisplay, $postedDate, $closingDate, $sAdminIT, $sArbo, $sComVol, $sCountry, $sEco, $sEnvOE, $rNational, $rInternational, $rScotlandNorth, $rScotlandSouth, $rWales, $rNorthernIreland, $rEngland, $rAnglia, $rMidlands, $rNorthEast, $rNorthWest, $rSouth, $rSouthWest, $rHome, $sHorti, $sPubF, $sRoW, $sVisMan, $sWiWoAnC, $sSustainability, $sPolicy, $sFieldwork, $sPractical, $sForestry, $location, $lat, $lng, '$logo1', '$logo2', '$logo3', '$logo4', '$logo5', '$advertURL', '$status', '$dynamicFields', $contractLength, $cfullTime, $cpartTime, $cpermanent, $cTempFixed, $cseasonal, $jobOfDay, $jobOfDayDate, $socialShares, $socialSharesDate)");
// 		
// 		}
// 		
// 		$submissionDate = date('Y-m-d H:i:s');
// 		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/perch/addons/apps/cjs_daily/submissions/'.$submissionDate.'.txt', "INSERT INTO perch3_daily_jobs (title, advertText, memberID, employerDisplay, postedDate, closingDate, sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, rNational, rInternational, rScotlandNorth, rScotlandSouth, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rHome, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, sSustainability, sPolicy, sFieldwork, sPractical, sForestry, location, lat, lng, imageLogo, imageLogo2, imageLogo3, imageLogo4, imageLogo5, advertURL, advertStatus, daily_jobDynamicFields, contractLength, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, jobOfDay, jobOfDayDate, socialShares, socialSharesDate) VALUES ($title, $text, $memberID, $employerDisplay, $postedDate, $closingDate, $sAdminIT, $sArbo, $sComVol, $sCountry, $sEco, $sEnvOE, $rNational, $rInternational, $rScotlandNorth, $rScotlandSouth, $rWales, $rNorthernIreland, $rEngland, $rAnglia, $rMidlands, $rNorthEast, $rNorthWest, $rSouth, $rSouthWest, $rHome, $sHorti, $sPubF, $sRoW, $sVisMan, $sWiWoAnC, $sSustainability, $sPolicy, $sFieldwork, $sPractical, $sForestry, $location, $lat, $lng, '$logo1', '$logo2', '$logo3', '$logo4', '$logo5', '$advertURL', $status, '$dynamicFields', $contractLength, $cfullTime, $cpartTime, $cpermanent, $cTempFixed, $cseasaonal, $jobOfDay, $jobOfDayDate, $socialShares, $socialSharesDate)");
// 		
// 		if($email){
// 		    // Multiple recipients
// 			$to = 'ranger@countryside-jobs.com,jack@hellotechnology.co.uk'; // note the comma
// 			
// 			// Message
// 			$emailHTML = "
// 			<html>
// 			<head>
// 			  <title>CJS Advert Submission</title>
// 			</head>
// 			<body>
// 			  <p>Title: $title<br />
// 			  Contract Type: $contractType<br />
// 			  Contract Length: $contractLength<br />
// 			  Text: $text<br />
// 			  Closing Date: $closingDate<br />
// 			  Location: $location<br />
// 			  Latitude: $lat<br />
// 			  Longitude: $lng<br />
// 			  Admin, Support and IT: $sAdminIT<br /> 
// 			  Arboriculture: $sArbo<br /> 
// 			  Community: $sComVol<br /> 
// 			  Countryside: $sCountry<br /> 
// 			  Ecology: $sEco<br /> 
// 			  Environmental and Outdoor Education: $sEnvOE<br /> 
// 			  Horticulture: $sHorti<br /> 
// 			  Publicity and Fundraising: $sPubF<br /> 
// 			  Rights of Way: $sRoW<br /> 
// 			  Visitor Management: $sVisMan<br /> 
// 			  Wildlife Work and Animal Care: $sWiWoAnC<br />
// 			  Sustainability: $sSustainability<br />
// 			  Policy and Advice: $sPolicy<br />
// 			  Fieldwork and Research: $sFieldwork<br />
// 			  Practical Land Management: $sPractical<br />
// 			  Forestry: $sForestry<br />
// 			  National: $rNational<br /> 
// 			  International: $rInternational<br /> 
// 			  Scotland North: $rScotlandNorth<br />
// 			  Scotland South: $rScotlandSouth<br /> 
// 			  Wales: $rWales<br /> 
// 			  Northern Ireland: $rNorthernIreland<br /> 
// 			  England: $rEngland<br /> 
// 			  Anglia: $rAnglia<br /> 
// 			  Midlands: $rMidlands<br /> 
// 			  North East: $rNorthEast<br /> 
// 			  North West: $rNorthWest<br /> 
// 			  South: $rSouth<br /> 
// 			  South West: $rSouthWest<br /> 
// 			  Home Based: $rHome<br />  
// 			  Employer: $employer / $employerDisplay<br />";
// 			  if($logo1){
// 			  	$emailHTML .= "Logo 1: https://countryside-jobs.com$logo1<br />";
// 			  }
// 			  if($logo2){
// 			  	$emailHTML .= "Logo 2: https://countryside-jobs.com$logo2<br />";
// 			  }
// 			  if($logo3){
// 			  	$emailHTML .= "Logo 3: https://countryside-jobs.com$logo3<br />";
// 			  }
// 			  if($logo4){
// 			  	$emailHTML .= "Logo 4: https://countryside-jobs.com$logo4<br />";
// 			  }
// 			  if($logo5){
// 			  	$emailHTML .= "Logo 5: https://countryside-jobs.com$logo5<br />";
// 			  }
// 			  $emailHTML .= "
// 			  Video: $video<br />
// 			  Unpaid: $unpaid<br />
// 			  Professional: $professional<br />
// 			  Weekly: $weekly<br />
// 			  Job of the Day: $jobOfDay $jobOfDayDate<br />
// 			  Social Shares: $socialShares $socialSharesDate<br /><br />
// 			  
// 			  Invoice Address: $invoiceaddress<br />
// 			  Reference: $reference<br />
// 			  Payment: $payment<br />
// 			  Name: $name<br />
// 			  Organisation: $organisation<br />
// 			  Email: $emailAddress<br />
// 			  Telephone: $telephone<br />
// 			  Message: $message<br />
// 			  MemberID: $memberID<br />
// 			  Advert Type: $advertType<br />
// 			  Advert Status: $status</p>
// 			</body>
// 			</html>
// 			";
// 			
// 			// To send HTML mail, the Content-type header must be set
// 			$headers[] = 'MIME-Version: 1.0';
// 			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
// 			
// 			// Additional headers
// 			$headers[] = 'From: no-reply@countryside-jobs.com';
// 			$headers[] = 'Reply-To: '.$emailAddress;
// 			
// 			// Mail it
// 			mail($to, $subject, $emailHTML, implode("\r\n", $headers));
// 	    }   
//     }
//   }

public function submitDailyAdvert($data){
	$captcha=$_POST['g-recaptcha-response'];
	$secretKey = (defined('CJS_RECAPTCHA_SECRET') ? CJS_RECAPTCHA_SECRET : '');
	$ip = $_SERVER['REMOTE_ADDR'];
	// post request to server
	$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
	$response = file_get_contents($url);
	$responseKeys = json_decode($response,true);
	// should return JSON with success as true
	if($responseKeys["success"]) {
		$type = $data->data['advertType'];
		$title = $this->db->pdb($data->data['jobtitle']);
		$advertText = $data->data['form_text'];
		
		$memberID = $this->db->pdb($data->data['memberID']);

		// CHANGED: this was pdb(), which wraps a string in literal single quotes, so
		// $advertID held "'18410'" rather than 18410. That made the if($advertID>0)
		// test further down always false, so every edit of an existing advert fell
		// through to the "brand new advert" INSERT and silently lost the 22 columns
		// that INSERT does not name (employerID, imageAlt, baseSalary, salaryType,
		// displayAdURL, postCode, jobReference, streetAddress and the rest), as well as
		// regenerating advertURL from the title. The volunteer and free-advert versions
		// of this function use addslashes() and were never affected.
		$advertID = isset($data->data['advertID']) ? (int)$data->data['advertID'] : 0;
		$advertURL = $this->db->pdb($data->data['advertURL']);
		$employer = $this->db->pdb($data->data['employer']);
		$employerDisplay = $this->db->pdb($data->data['employerDisplay']);
		$cfullTime = $this->db->pdb($data->data['form_cfullTime']);
		$cpartTime = $this->db->pdb($data->data['form_cpartTime']);
		$cpermanent = $this->db->pdb($data->data['form_cpermanent']);
		$cTempFixed = $this->db->pdb($data->data['form_cTempFixed']);
		$cseasonal = $this->db->pdb($data->data['form_cseasonal']);
		$contractLength = $this->db->pdb($data->data['contractLength']);

		// CHANGED: guard against a blank date. explode('/','') has no [1] or [2],
		// so the old code built the string '--' and wrote 0000-00-00.
		$closingDate = 'NULL';
		if(!empty($_POST['closingDate']) && substr_count($_POST['closingDate'],'/')==2){
			$dates = explode("/", $_POST['closingDate']);
			$closingDate = $this->db->pdb("$dates[2]-$dates[1]-$dates[0]");
		}

		$location = $this->db->pdb($data->data['location']);
		$lat = $this->db->pdb($data->data['lat']);
		$lng = $this->db->pdb($data->data['lng']);
		
		$sAdminIT = $this->db->pdb($data->data['form_sAdminIT']);
		$sArbo = $this->db->pdb($data->data['form_sArbo']);
		$sComVol = $this->db->pdb($data->data['form_sComVol']);
		$sCountry = $this->db->pdb($data->data['form_sCountry']);
		$sEco = $this->db->pdb($data->data['form_sEco']);
		$sEnvOE = $this->db->pdb($data->data['form_sEnvOE']);
		$sHorti = $this->db->pdb($data->data['form_sHorti']);
		$sPubF = $this->db->pdb($data->data['form_sPubF']);
		$sRoW = $this->db->pdb($data->data['form_sRoW']);
		$sVisMan = $this->db->pdb($data->data['form_sVisMan']);
		$sWiWoAnC = $this->db->pdb($data->data['form_sWiWoAnC']);
		$sSustainability = $this->db->pdb($data->data['form_sSustainability']);
		$sPolicy = $this->db->pdb($data->data['form_sPolicy']);
		$sFieldwork = $this->db->pdb($data->data['form_sFieldwork']);
		$sPractical = $this->db->pdb($data->data['form_sPractical']);
		$sForestry = $this->db->pdb($data->data['form_sForestry']);
		
		$rNational = $this->db->pdb($data->data['form_rNational']);
		$rInternational = $this->db->pdb($data->data['form_rInternational']);
		$rScotlandNorth = $this->db->pdb($data->data['form_rScotlandNorth']);
		$rScotlandSouth = $this->db->pdb($data->data['form_rScotlandSouth']);
		$rWales = $this->db->pdb($data->data['form_rWales']);
		$rNorthernIreland = $this->db->pdb($data->data['form_rNorthernIreland']);
		$rEngland = $this->db->pdb($data->data['form_rEngland']);
		$rAnglia = $this->db->pdb($data->data['form_rAnglia']);
		$rMidlands = $this->db->pdb($data->data['form_rMidlands']);
		$rNorthEast = $this->db->pdb($data->data['form_rNorthEast']);
		$rNorthWest = $this->db->pdb($data->data['form_rNorthWest']);
		$rSouth = $this->db->pdb($data->data['form_rSouth']);
		$rSouthWest = $this->db->pdb($data->data['form_rSouthWest']);
		$rHome = $this->db->pdb($data->data['form_rHome']);
		
		$jobOfDay = $this->db->pdb($data->data['jod']);

		// CHANGED: same blank-date guard.
		$jobOfDayDate = 'NULL';
		if(!empty($_POST['jodDate']) && substr_count($_POST['jodDate'],'/')==2){
			$dates = explode("/", $_POST['jodDate']);
			$jobOfDayDate = $this->db->pdb("$dates[2]-$dates[1]-$dates[0]");
		}
		
		$socialShares = $this->db->pdb($data->data['socialshares']);

		// CHANGED: same blank-date guard.
		$socialSharesDate = 'NULL';
		if(!empty($_POST['socialsharesDate']) && substr_count($_POST['socialsharesDate'],'/')==2){
			$dates = explode("/", $_POST['socialsharesDate']);
			$socialSharesDate = $this->db->pdb("$dates[2]-$dates[1]-$dates[0]");
		}

		$video = $data->data['video'];
		$unpaid = $data->data['unpaid'];
		$professional = $data->data['professional'];
		$weekly = $data->data['weekly'];
		
		$invoiceaddress = $data->data['invoiceaddress'];
		$reference = $data->data['reference'];
		$payment = $data->data['payment'];
		$name = $data->data['name'];
		$organisation = $data->data['organisation'];
		$emailAddress = $data->data['email'];
		$telephone = $data->data['telephone'];
		$message = $data->data['message'];
		$status = $this->cjs_esc($data->data['status']);
		if($status==''){
			$status = $this->cjs_esc('pen');
		}
		
		$advertType = $data->data['advertType'];

		// CHANGED: these two were never assigned, so they went into the JSON as
		// empty and wiped whatever was stored against those keys.
		$socialmediapromotion = isset($data->data['socialmediapromotion']) ? $data->data['socialmediapromotion'] : '';
		$howdidyoufindus = isset($data->data['howdidyoufindus']) ? $data->data['howdidyoufindus'] : '';
		
		$textPlain = nl2br(strip_tags($data->data['text']));
		$words = explode(" ",$textPlain);
		$i = 0;
		$paragraph = ''; // CHANGED: was undefined on first .= 
		while($i < 100){
			$paragraph .= $words[$i]." ";
			$i++;
		}
		$shorttext = $paragraph;
		
		$fields = '';
		foreach ($data->data as $key => $value) {
$fields .= htmlspecialchars($key).": ".htmlspecialchars($value)."
";
		}
		
		$submissionDate = date('Y-m-d H:i:s');
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/perch/addons/apps/cjs_daily/submissions/fields-'.$submissionDate.'.txt', $fields);
		
		$jsonData = array();
		
		$data = $data->data;

		preg_match_all('/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>([^<]+)<\/a>/i', $advertText, $out);
		
		foreach($out[1] as $item){
			$long_url = str_replace('" target="_blank', '', $item);

			$apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
			$genericAccessToken = (defined('CJS_BITLY_TOKEN') ? CJS_BITLY_TOKEN : '');
			
			$bitlyPayload = array('long_url' => $long_url);
			$payload = json_encode($bitlyPayload);
			
			$header = array(
				'Authorization: Bearer ' . $genericAccessToken,
				'Content-Type: application/json',
				'Content-Length: ' . strlen($payload)
			);
			
			$ch = curl_init($apiv4);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$result = curl_exec($ch);
			
			$result = json_decode($result,true);
			$bitly = $result['link'] ?? $long_url;
			$advertText = str_replace($long_url, $bitly, $advertText);
		}
		
		$text = $this->db->pdb($advertText);

		$jsonData['socialmediapromotion'] = $socialmediapromotion;
		$jsonData['video'] = $video;
		$jsonData['unpaid'] = $unpaid;
		$jsonData['professional'] = $professional;
		$jsonData['weekly'] = $weekly;
		$jsonData['invoiceaddress'] = $invoiceaddress;
		$jsonData['reference'] = $reference;
		$jsonData['payment'] = $payment;
		$jsonData['name'] = $name;
		$jsonData['organisation'] = $organisation;
		$jsonData['email'] = $emailAddress;
		$jsonData['telephone'] = $telephone;
		$jsonData['message'] = $message;
		$jsonData['howdidyoufindus'] = $howdidyoufindus;
		$jsonData['advertType'] = $advertType;
		
		// CHANGED: $jsonData only carries the fifteen keys this public form knows
		// about, so encoding it on its own overwrote every other field held in
		// daily_jobDynamicFields - in particular 'text' (the summary shown on the main
		// jobs page) and 'displayalt' (the display advert ALT text), neither of which
		// appears on the advertiser's form and both of which are only ever set in the
		// Perch admin. Merge the submitted values over whatever is already stored
		// against this advert rather than replacing the lot.
		if($advertID>0){
			$stored = $this->db->get_value("SELECT daily_jobDynamicFields FROM perch3_daily_jobs WHERE daily_jobID=$advertID");
			if($stored){
				$stored = json_decode($stored, true);
				if(is_array($stored)){
					$jsonData = array_merge($stored, $jsonData);
				}
			}
		}

		$json = json_encode($jsonData,JSON_HEX_APOS);

		// CHANGED: escape it properly. The preserved fields hold HTML that can contain
		// apostrophes and backslashes, and this value is interpolated straight into the
		// queries below. pdb() returns it already quoted, so the '$dynamicFields'
		// wrappers in those queries have been dropped.
		$dynamicFields = $this->db->pdb($json);
		
		//FILE 1
		if($_FILES['logo']['tmp_name']){
			$rand = rand();
			$logo1 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo']['tmp_name'], "../../../advert-uploads/".$logo1);

			//RESIZE
			$fn = "../../../advert-uploads/".$logo1;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo1);
			imagedestroy($dst);
			
			$logo1 = $this->cjs_esc("/advert-uploads/".$logo1);
		}else{
			$logo1 = $this->cjs_esc($data['logo1_hidden']);
		}
		
		//FILE 2
		if($_FILES['logo2']['tmp_name']){
			$rand = rand();
			$logo2 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo2']['tmp_name'], "../../../advert-uploads/".$logo2);
			
			//RESIZE
			$fn = "../../../advert-uploads/".$logo2;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagetruecolortopalette($dst, false, 255);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo2);
			imagedestroy($dst);
			
			$logo2 = $this->cjs_esc("/advert-uploads/".$logo2);
		}else{
			$logo2 = $this->cjs_esc($data['logo2_hidden']);
		}
		
		//FILE 3
		if($_FILES['logo3']['tmp_name']){
			$rand = rand();
			$logo3 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo3']['tmp_name'], "../../../advert-uploads/".$logo3);
			
			//RESIZE
			$fn = "../../../advert-uploads/".$logo3;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagetruecolortopalette($dst, false, 255);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo3);
			imagedestroy($dst);
			
			$logo3 = $this->cjs_esc("/advert-uploads/".$logo3);
		}else{
			$logo3 = $this->cjs_esc($data['logo3_hidden']);
		}
		
		//FILE 4
		if($_FILES['logo4']['tmp_name']){
			$rand = rand();
			$logo4 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo4']['tmp_name'], "../../../advert-uploads/".$logo4);
			
			//RESIZE
			$fn = "../../../advert-uploads/".$logo4;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagetruecolortopalette($dst, false, 255);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo4);
			imagedestroy($dst);
			
			$logo4 = $this->cjs_esc("/advert-uploads/".$logo4);
		}else{
			$logo4 = $this->cjs_esc($data['logo4_hidden']);
		}
		
		//FILE 5
		if($_FILES['logo5']['tmp_name']){
			$rand = rand();
			$logo5 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo5']['tmp_name'], "../../../advert-uploads/".$logo5);
			
			//RESIZE
			$fn = "../../../advert-uploads/".$logo5;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagetruecolortopalette($dst, false, 255);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo5);
			imagedestroy($dst);
			
			$logo5 = $this->cjs_esc("/advert-uploads/".$logo5);
		}else{
			$logo5 = $this->cjs_esc($data['logo5_hidden']);
		}
		
		$API  = new PerchAPI(1.0, 'perch_members');
		$Members = new PerchMembers_Members($API);
		$Session = PerchMembers_Session::fetch();
		$Member = $Members->find($Session->get('memberID'));
		$Properties = new stdClass();
		
		$email = false;
		
		$month = strtolower(date('M')).date('y');
		
		$delimiter = '-';
		$advertURL = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $title))))), $delimiter));
		$advertURL = "/job/".$month."/".$advertURL;
		
		$postedDate = $this->db->pdb(date('Y-m-d'));
		
		if($advertID>0){
			
			// CHANGED throughout the three UPDATEs below:
			//   postedDate='$postedDate'  ->  postedDate=$postedDate   (pdb already adds quotes)
			//   $sPracitcal -> $sPractical, $Forestry -> $sForestry    (both were undefined)
			//   imageLogoN='$logoN' -> IF(...) so a blank value leaves the stored image alone
			
			if($status=='draft'){
				$this->db->execute("UPDATE perch3_daily_jobs SET title=$title, advertText=$text, employerDisplay=$employerDisplay, postedDate=$postedDate, closingDate=$closingDate, sAdminIT=$sAdminIT, sArbo=$sArbo, sComVol=$sComVol, sCountry=$sCountry, sEco=$sEco, sEnvOE=$sEnvOE, rNational=$rNational, rInternational=$rInternational, rScotlandNorth=$rScotlandNorth, rScotlandSouth=$rScotlandSouth, rWales=$rWales, rNorthernIreland=$rNorthernIreland, rEngland=$rEngland, rAnglia=$rAnglia, rMidlands=$rMidlands, rNorthEast=$rNorthEast, rNorthWest=$rNorthWest, rSouth=$rSouth, rSouthWest=$rSouthWest, rHome=$rHome, sHorti=$sHorti, sPubF=$sPubF, sRow=$sRoW, sVisMan=$sVisMan, sWiWoAnC=$sWiWoAnC, sSustainability=$sSustainability, sPolicy=$sPolicy, sFieldwork=$sFieldwork, sPractical=$sPractical, sForestry=$sForestry, location=$location, lat=$lat, lng=$lng, imageLogo=IF('$logo1'='',imageLogo,'$logo1'), imageLogo2=IF('$logo2'='',imageLogo2,'$logo2'), imageLogo3=IF('$logo3'='',imageLogo3,'$logo3'), imageLogo4=IF('$logo4'='',imageLogo4,'$logo4'), imageLogo5=IF('$logo5'='',imageLogo5,'$logo5'), advertStatus='$status', daily_jobDynamicFields=$dynamicFields, contractLength=$contractLength, cfullTime=$cfullTime, cpartTime=$cpartTime, cpermanent=$cpermanent, cTempFixed=$cTempFixed, cseasonal=$cseasonal, jobOfDay=$jobOfDay, jobOfDayDate=$jobOfDayDate, socialShares=$socialShares, socialSharesDate=$socialSharesDate WHERE daily_jobID=$advertID");
			}elseif($status=='pen'){
				$this->db->execute("UPDATE perch3_daily_jobs SET title=$title, advertText=$text, employerDisplay=$employerDisplay, postedDate=$postedDate, closingDate=$closingDate, sAdminIT=$sAdminIT, sArbo=$sArbo, sComVol=$sComVol, sCountry=$sCountry, sEco=$sEco, sEnvOE=$sEnvOE, rNational=$rNational, rInternational=$rInternational, rScotlandNorth=$rScotlandNorth, rScotlandSouth=$rScotlandSouth, rWales=$rWales, rNorthernIreland=$rNorthernIreland, rEngland=$rEngland, rAnglia=$rAnglia, rMidlands=$rMidlands, rNorthEast=$rNorthEast, rNorthWest=$rNorthWest, rSouth=$rSouth, rSouthWest=$rSouthWest, rHome=$rHome, sHorti=$sHorti, sPubF=$sPubF, sRow=$sRoW, sVisMan=$sVisMan, sWiWoAnC=$sWiWoAnC, sSustainability=$sSustainability, sPolicy=$sPolicy, sFieldwork=$sFieldwork, sPractical=$sPractical, sForestry=$sForestry, location=$location, lat=$lat, lng=$lng, imageLogo=IF('$logo1'='',imageLogo,'$logo1'), imageLogo2=IF('$logo2'='',imageLogo2,'$logo2'), imageLogo3=IF('$logo3'='',imageLogo3,'$logo3'), imageLogo4=IF('$logo4'='',imageLogo4,'$logo4'), imageLogo5=IF('$logo5'='',imageLogo5,'$logo5'), advertStatus='$status', daily_jobDynamicFields=$dynamicFields, contractLength=$contractLength, cfullTime=$cfullTime, cpartTime=$cpartTime, cpermanent=$cpermanent, cTempFixed=$cTempFixed, cseasonal=$cseasonal, jobOfDay=$jobOfDay, jobOfDayDate=$jobOfDayDate, socialShares=$socialShares, socialSharesDate=$socialSharesDate WHERE daily_jobID=$advertID");
				$email = true;
				$subject = "CJS Daily Submission - Pending";
			}elseif($status=='pub'){
				if(perch_member_has_tag('package')){
					if($advertType=='Basic'){
						if($_SESSION['basiccredits']>0){
							$Properties->data = array(
								 'basiccredits' => $_SESSION['basiccredits']-1,
							);
							$Member->update_profile($Properties);
							$_SESSION['basiccredits'] = $_SESSION['basiccredits']-1;
						}else{
							$status = $this->cjs_esc('draft');
						}
					}
					if($advertType=='Standard'){
						if($_SESSION['standardcredits']>0){
							$Properties->data = array(
								 'standardcredits' => $_SESSION['standardcredits']-1,
							);
							$Member->update_profile($Properties);
							$_SESSION['standardcredits'] = $_SESSION['standardcredits']-1;
						}else{
							$status = $this->cjs_esc('draft');
						}
					}
					if($advertType=='Unlimited'){
						if($_SESSION['unlimitedcredits']>0){
							$Properties->data = array(
								 'unlimitedcredits' => $_SESSION['unlimitedcredits']-1,
							);
							$Member->update_profile($Properties);
							$_SESSION['unlimitedcredits'] = $_SESSION['unlimitedcredits']-1;
						}else{
							$status = $this->cjs_esc('draft');
						}
					}
				}
				$this->db->execute("UPDATE perch3_daily_jobs SET title=$title, advertText=$text, employerDisplay=$employerDisplay, postedDate=$postedDate, closingDate=$closingDate, sAdminIT=$sAdminIT, sArbo=$sArbo, sComVol=$sComVol, sCountry=$sCountry, sEco=$sEco, sEnvOE=$sEnvOE, rNational=$rNational, rInternational=$rInternational, rScotlandNorth=$rScotlandNorth, rScotlandSouth=$rScotlandSouth, rWales=$rWales, rNorthernIreland=$rNorthernIreland, rEngland=$rEngland, rAnglia=$rAnglia, rMidlands=$rMidlands, rNorthEast=$rNorthEast, rNorthWest=$rNorthWest, rSouth=$rSouth, rSouthWest=$rSouthWest, rHome=$rHome, sHorti=$sHorti, sPubF=$sPubF, sRow=$sRoW, sVisMan=$sVisMan, sWiWoAnC=$sWiWoAnC, sSustainability=$sSustainability, sPolicy=$sPolicy, sFieldwork=$sFieldwork, sPractical=$sPractical, sForestry=$sForestry, location=$location, lat=$lat, lng=$lng, imageLogo=IF('$logo1'='',imageLogo,'$logo1'), imageLogo2=IF('$logo2'='',imageLogo2,'$logo2'), imageLogo3=IF('$logo3'='',imageLogo3,'$logo3'), imageLogo4=IF('$logo4'='',imageLogo4,'$logo4'), imageLogo5=IF('$logo5'='',imageLogo5,'$logo5'), advertStatus='$status', daily_jobDynamicFields=$dynamicFields, contractLength=$contractLength, cfullTime=$cfullTime, cpartTime=$cpartTime, cpermanent=$cpermanent, cTempFixed=$cTempFixed, cseasonal=$cseasonal, jobOfDay=$jobOfDay, jobOfDayDate=$jobOfDayDate, socialShares=$socialShares, socialSharesDate=$socialSharesDate WHERE daily_jobID=$advertID");
				$email = true;
				$subject = "CJS Daily Submission - Live";
			}elseif($status=='over'){
				// CHANGED: this only matched rows already at 'pub', so re-editing an
				// advert that is itself still awaiting an overwrite left $advertURL null
				// (and raised a warning) in the notification email further down. Read the
				// stored URL whatever the status, and keep the generated one as a
				// fallback. Now that the branch above actually runs, this matters.
				$stored_url = $this->db->get_value("SELECT advertURL FROM perch3_daily_jobs WHERE daily_jobID=$advertID");
				if($stored_url) $advertURL = $stored_url;

				// CHANGED, three things:
				//   1. jobOfDay and jobOfDayDate were listed TWICE in the column list,
				//      which MySQL rejects outright (error 1110).
				//   2. contractLength sat last in the value list but fourth-from-last
				//      in the column list, shifting every value after position 77 by one.
				//      That is what was putting a 0/1 flag into jobOfDayDate.
				//   3. closingDate and contractLength now take the submitted values
				//      rather than copying the old row's.
				$this->db->execute("INSERT INTO perch3_daily_jobs (title, advertText, memberID, advertSlug, employerDisplay, employerID, imageLogo, imageLogo2, imageLogo3, imageLogo4, imageLogo5, imageAlt, imageAlt2, imageAlt3, imageAlt4, imageAlt5, videoEmbed, postedDate, closingDate, cVolunteer, cApprentice, cIntern, cClosingDateEU, cDisplayDNotCDate, displayAd, displayAdURL, advertAmendedDate, advertURL, sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, sSustainability, sPolicy, sFieldwork, sPractical, sForestry, rNational, rInternational, rScotlandNorth, rScotlandSouth, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rHome, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, location, lat, lng, rCounty, grade, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, advertStatus, postCode, baseSalary, salaryType, jobReference, streetAddress, daily_jobDynamicFields, contractLength, jobOfDay, jobOfDayDate, socialShares, socialSharesDate) SELECT $title, $text, memberID, advertSlug, $employerDisplay, employerID, IF('$logo1'='',imageLogo,'$logo1'), IF('$logo2'='',imageLogo2,'$logo2'), IF('$logo3'='',imageLogo3,'$logo3'), IF('$logo4'='',imageLogo4,'$logo4'), IF('$logo5'='',imageLogo5,'$logo5'), imageAlt, imageAlt2, imageAlt3, imageAlt4, imageAlt5, videoEmbed, postedDate, $closingDate, cVolunteer, cApprentice, cIntern, cClosingDateEU, cDisplayDNotCDate, displayAd, displayAdURL, advertAmendedDate, advertURL, $sAdminIT, $sArbo, $sComVol, $sCountry, $sEco, $sEnvOE, $sSustainability, $sPolicy, $sFieldwork, $sPractical, $sForestry, $rNational, $rInternational, $rScotlandNorth, $rScotlandSouth, $rWales, $rNorthernIreland, $rEngland, $rAnglia, $rMidlands, $rNorthEast, $rNorthWest, $rSouth, $rSouthWest, $rHome, $sHorti, $sPubF, $sRoW, $sVisMan, $sWiWoAnC, $location, $lat, $lng, rCounty, grade, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, 'over', postCode, baseSalary, salaryType, jobReference, streetAddress, $dynamicFields, $contractLength, $jobOfDay, $jobOfDayDate, $socialShares, $socialSharesDate FROM perch3_daily_jobs WHERE daily_jobID = $advertID");
				$email = true;
				$subject = "CJS Daily Submission - Update";
			}
			
		}else{
		
			if($status=='draft'){

			}elseif($status=='pen'){
				$email = true;
				$subject = "CJS Daily Submission - Pending";
			}elseif($status=='over'){
				$email = true;
				$subject = "CJS Daily Submission - Update";
			}elseif($status=='pub'){
				$email = true;
				$subject = "CJS Daily Submission - Published";
			}else{
				$email = true;
				$subject = "CJS Daily Submission";
			}
			
			$this->db->execute("INSERT INTO perch3_daily_jobs (title, advertText, memberID, employerDisplay, postedDate, closingDate, sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, rNational, rInternational, rScotlandNorth, rScotlandSouth, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rHome, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, sSustainability, sPolicy, sFieldwork, sPractical, sForestry, location, lat, lng, imageLogo, imageLogo2, imageLogo3, imageLogo4, imageLogo5, advertURL, advertStatus, daily_jobDynamicFields, contractLength, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, jobOfDay, jobOfDayDate, socialShares, socialSharesDate) VALUES ($title, $text, $memberID, $employerDisplay, $postedDate, $closingDate, $sAdminIT, $sArbo, $sComVol, $sCountry, $sEco, $sEnvOE, $rNational, $rInternational, $rScotlandNorth, $rScotlandSouth, $rWales, $rNorthernIreland, $rEngland, $rAnglia, $rMidlands, $rNorthEast, $rNorthWest, $rSouth, $rSouthWest, $rHome, $sHorti, $sPubF, $sRoW, $sVisMan, $sWiWoAnC, $sSustainability, $sPolicy, $sFieldwork, $sPractical, $sForestry, $location, $lat, $lng, '$logo1', '$logo2', '$logo3', '$logo4', '$logo5', '$advertURL', '$status', $dynamicFields, $contractLength, $cfullTime, $cpartTime, $cpermanent, $cTempFixed, $cseasonal, $jobOfDay, $jobOfDayDate, $socialShares, $socialSharesDate)");
		
		}
		
		$submissionDate = date('Y-m-d H:i:s');
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/perch/addons/apps/cjs_daily/submissions/'.$submissionDate.'.txt', "INSERT INTO perch3_daily_jobs (title, advertText, memberID, employerDisplay, postedDate, closingDate, sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, rNational, rInternational, rScotlandNorth, rScotlandSouth, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rHome, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, sSustainability, sPolicy, sFieldwork, sPractical, sForestry, location, lat, lng, imageLogo, imageLogo2, imageLogo3, imageLogo4, imageLogo5, advertURL, advertStatus, daily_jobDynamicFields, contractLength, cfullTime, cpartTime, cpermanent, cTempFixed, cseasonal, jobOfDay, jobOfDayDate, socialShares, socialSharesDate) VALUES ($title, $text, $memberID, $employerDisplay, $postedDate, $closingDate, $sAdminIT, $sArbo, $sComVol, $sCountry, $sEco, $sEnvOE, $rNational, $rInternational, $rScotlandNorth, $rScotlandSouth, $rWales, $rNorthernIreland, $rEngland, $rAnglia, $rMidlands, $rNorthEast, $rNorthWest, $rSouth, $rSouthWest, $rHome, $sHorti, $sPubF, $sRoW, $sVisMan, $sWiWoAnC, $sSustainability, $sPolicy, $sFieldwork, $sPractical, $sForestry, $location, $lat, $lng, '$logo1', '$logo2', '$logo3', '$logo4', '$logo5', '$advertURL', $status, $dynamicFields, $contractLength, $cfullTime, $cpartTime, $cpermanent, $cTempFixed, $cseasonal, $jobOfDay, $jobOfDayDate, $socialShares, $socialSharesDate)");
		
		if($email){
			// Multiple recipients
			$to = 'ranger@countryside-jobs.com,jack@hellotechnology.co.uk'; // note the comma
			
			// Message
			$emailHTML = "
			<html>
			<head>
			  <title>CJS Advert Submission</title>
			</head>
			<body>
			  <p>Title: $title<br />
			  Contract Length: $contractLength<br />
			  Text: $text<br />
			  Closing Date: $closingDate<br />
			  Location: $location<br />
			  Latitude: $lat<br />
			  Longitude: $lng<br />
			  Rangers and Countryside Wardens: $sCountry<br /> 
			  Practical Land and Habitat Management: $sPractical<br />
			  Rights of Way and Public Access: $sRoW<br /> 
			  Policy and Advice: $sPolicy<br />
			  Ecology and Biodiversity: $sEco<br /> 
			  Fieldwork and Research: $sFieldwork<br />
			  Wildlife Work and Animal Care: $sWiWoAnC<br />
			  Environmental Stewardship and Nature Recovery: $sSustainability<br />
			  Arboriculture and Woodland: $sArbo<br /> 
			  Commercial Forestry and Timber: $sForestry<br />
			  Heritage and Conservation Horticulture: $sHorti<br /> 
			  Administration, IT and Support Services: $sAdminIT<br /> 
			  Environmental and Outdoor Education: $sEnvOE<br /> 
			  Community Engagement and Volunteer Management: $sComVol<br /> 
			  Countryside Recreation and Visitor Management: $sVisMan<br /> 
			  Publicity and Fundraising: $sPubF<br /> 
			  National: $rNational<br /> 
			  International: $rInternational<br /> 
			  Scotland North: $rScotlandNorth<br />
			  Scotland South: $rScotlandSouth<br /> 
			  Wales: $rWales<br /> 
			  Northern Ireland: $rNorthernIreland<br /> 
			  England: $rEngland<br /> 
			  Anglia: $rAnglia<br /> 
			  Midlands: $rMidlands<br /> 
			  North East: $rNorthEast<br /> 
			  North West: $rNorthWest<br /> 
			  South: $rSouth<br /> 
			  South West: $rSouthWest<br /> 
			  Home Based: $rHome<br />  
			  Employer: $employer / $employerDisplay<br />";
			  if($logo1){
				  $emailHTML .= "Logo 1: https://countryside-jobs.com$logo1<br />";
			  }
			  if($logo2){
				  $emailHTML .= "Logo 2: https://countryside-jobs.com$logo2<br />";
			  }
			  if($logo3){
				  $emailHTML .= "Logo 3: https://countryside-jobs.com$logo3<br />";
			  }
			  if($logo4){
				  $emailHTML .= "Logo 4: https://countryside-jobs.com$logo4<br />";
			  }
			  if($logo5){
				  $emailHTML .= "Logo 5: https://countryside-jobs.com$logo5<br />";
			  }
			  $emailHTML .= "
			  Video: $video<br />
			  Unpaid: $unpaid<br />
			  Professional: $professional<br />
			  Weekly: $weekly<br />
			  Job of the Day: $jobOfDay $jobOfDayDate<br />
			  Social Shares: $socialShares $socialSharesDate<br /><br />
			  
			  Invoice Address: $invoiceaddress<br />
			  Reference: $reference<br />
			  Payment: $payment<br />
			  Name: $name<br />
			  Organisation: $organisation<br />
			  Email: $emailAddress<br />
			  Telephone: $telephone<br />
			  Message: $message<br />
			  MemberID: $memberID<br />
			  Advert Type: $type<br />
			  Advert Status: $status</p>
			</body>
			</html>
			";
			
			// To send HTML mail, the Content-type header must be set
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			
			// Additional headers
			$headers[] = 'From: no-reply@countryside-jobs.com';
			$headers[] = 'Reply-To: '.$emailAddress;
			
			// Mail it
			mail($to, $subject, $emailHTML, implode("\r\n", $headers));
		}   
	}else{
		// CHANGED: the old code had no else, so a failed captcha (or a failed
		// file_get_contents to Google) silently discarded the whole submission
		// and still showed the user a success page.
		error_log('[cjs_daily] reCAPTCHA verification failed for '.$ip);
	}
  }
  
  public function update_draft($data){
	  $this->db->execute("UPDATE perch3_daily_jobs_draft SET title='$data[title]', advertText='$data[form_text]', memberID='$data[memberID]', employerDisplay='$data[employerDisplay]', advertStatus='$data[advertStatus]', employerID = '$data[employerID]', imageLogo = '$data[imageLogo]', imageLogo2 = '$data[imageLogo2]', imageLogo3 = '$data[imageLogo3]', imageLogo4 = '$data[imageLogo4]', imageLogo5 = '$data[imageLogo5]', imageAlt = '$data[imageAlt]', imageAlt2 = '$data[imageAlt2]', imageAlt3 = '$data[imageAlt3]', imageAlt4 = '$data[imageAlt4]', imageAlt5 = '$data[imageAlt5]', videoEmbed = '$data[videoEmbed]', postedDate = '$data[postedDate]', closingDate = '$data[closingDate]', jobOfDay = '$data[jobOfDay]', jobOfDayDate = '$data[jobOfDayDate]', cVolunteer = '$data[cVolunteer]', cApprentice = '$data[cApprentice]', cIntern = '$data[cIntern]', cClosingDateEU = '$data[cClosingDateEU]', cDisplayDNotCDate = '$data[cDisplayDNotCDate]', displayAd = '$data[displayAd]', displayAdURL = '$data[displayAdURL]', advertAmendedDate = '$data[advertAmendedDate]', advertURL = '$data[advertURL]', sAdminIT = '$data[sAdminIT]', sArbo = '$data[sArbo]', sComVol = '$data[sComVol]', sCountry = '$data[sCountry]', sEco = '$data[sEco]', sEnvOE = '$data[sEnvOE]', rNational = '$data[rNational]', rInternational = '$data[rInternational]', rScotland = '$data[rScotland]', rWales = '$data[rWales]', rNorthernIreland = '$data[rNorthernIreland]', rEngland = '$data[rEngland]', rAnglia = '$data[rAnglia]', rMidlands = '$data[rMidlands]', rNorthEast = '$data[rNorthEast]', rNorthWest = '$data[rNorthWest]', rSouth = '$data[rSouth]', rSouthWest = '$data[rSouthWest]', rHome = '$data[rHome]', sHorti = '$data[sHorti]', sPubF = '$data[sPubF]', sRoW = '$data[sRoW]', sVisMan = '$data[sVisMan]', sWiWoAnC = '$data[sWiWoAnC]', location = '$data[location]', lat='$data[lat]', lng='$data[lng]', rCounty = '$data[rCounty]', grade = '$data[grade]', cfullTime = '$data[cfullTime]', cpartTime = '$data[cpartTime]', cpermanent = '$data[cpermanent]', cTempFixed = '$data[cTempFixed]', cseasonal = '$data[cseasonal]', postCode = '$data[postCode]', baseSalary = '$data[baseSalary]', salaryType = '$data[salaryType]', jobReference = '$data[jobReference]', streetAddress = '$data[streetAddress]', daily_jobDynamicFields = '$data[daily_jobDynamicFields]' WHERE jobID='$data[jobID]'");
  }
  
  public function submitVolunteerAdvert($data){
	$captcha=$_POST['g-recaptcha-response'];
	$secretKey = (defined('CJS_RECAPTCHA_SECRET') ? CJS_RECAPTCHA_SECRET : '');
    $ip = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    // should return JSON with success as true
    if($responseKeys["success"]) {
		
		$title = addslashes($data->data['jobtitle']);
	    $memberID = addslashes($data->data['memberID']);
	    $advertID = addslashes($data->data['advertID']);
	    $closingDate = addslashes($data->data['closingdate']);
	    $location = addslashes($data->data['location']);
	    $payBenefits = addslashes($data->data['benefits']);
	    $employer = addslashes($data->data['employer']);
	    $advertText = addslashes($data->data['form_text']);
	    $role = addslashes($data->data['role']);
	    $text1 = addslashes($data->data['text']);
	    $time = addslashes($data->data['timecommitment']);
	    $enddate = addslashes($data->data['enddate']);
	    $enhanced = $data->data['enhanced'];
	    
	    $name = addslashes($data->data['name']);
	    $organisation = addslashes($data->data['organisation']);
	    $emailAddress = addslashes($data->data['email']);
	    $telephone = addslashes($data->data['telephone']);
	    $message = addslashes($data->data['message']);
	    $howdidyoufindus = addslashes($data->data['howdidyoufindus']);
	    
	    $status = $this->cjs_esc($data->data['status']);
	    
	    $text = preg_replace( "/\r|\n/", "", $data->data['text']);
	    $text = str_replace("'","\'",$text);
	    $text = str_replace('"','\"',$text);
	    $text = htmlentities($text, ENT_QUOTES);
	    
	    $jsonData = array();
	    
	    $data = $data->data;

	    //$text = $data['text'];
	    $emailText = $text;
		
		$text = $advertText;
	    $text = strip_tags($text,"<br>,<p>,<ul>,<ol>,<li>,<a>");

	    $text = trim(preg_replace('/\s\s+/', ' ', $text));
		
		preg_match_all('/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>([^<]+)<\/a>/i', $advertText, $out);


		foreach($out[1] as $item){
			$long_url = str_replace('" target="_blank', '', $item);
			$apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
			$genericAccessToken = (defined('CJS_BITLY_TOKEN') ? CJS_BITLY_TOKEN : '');
			
			$data = array(
			    'long_url' => $long_url
			);
			$payload = json_encode($data);
			
			$header = array(
			    'Authorization: Bearer ' . $genericAccessToken,
			    'Content-Type: application/json',
			    'Content-Length: ' . strlen($payload)
			);
			
			$ch = curl_init($apiv4);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$result = curl_exec($ch);
			
			$result = json_decode($result,true);
			$bitly = $result['link'];
			$text = str_replace($long_url,$bitly,$text);
		}

		$text = addslashes($text);
	    
	    $text = array(
		  'description' => array(
		    '_flang' => "html",
		    'raw' => $text,
		    'processed' => $text
		  )
		);
		
		$jsonData['timecommittment'] = $time;
		$jsonData['enddate'] = $enddate;
		$jsonData['enhanced'] = $enhanced;
		$jsonData['name'] = $name;
		$jsonData['organisation'] = $organisation;
		$jsonData['email'] = $emailAddress;
		$jsonData['telephone'] = $telephone;
		$jsonData['message'] = $message;
		$jsonData['howdidyoufindus'] = $howdidyoufindus;
	    
	    $json = json_encode($jsonData,JSON_HEX_APOS);
	    
	    $dynamicFields = $this->cjs_esc($json);
	    
// 	    $dynamicFields = "{\"description\":{\"_flang\":\"html\",\"raw\":\"$text\",\"processed\":\"$text\"},\"timecommitment\":\"$time\",\"enddate\":\"$enddate\",\"enhanced\":\"$enhanced\",\"name\":\"$name\",\"organisation\":\"$organisation\",\"email\":\"$emailAddress\",\"telephone\":\"$telephone\",\"message\":\"$message\",\"howdidyoufindus\":\"$howdidyoufindus\"}";

	    //FILE 1
		if($_FILES['logo']['tmp_name']){
			$rand = rand();
			$logo1 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo']['tmp_name'], "../../../advert-uploads/".$logo1);

			//RESIZE
			$fn = "../../../advert-uploads/".$logo1;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagetruecolortopalette($dst, false, 255);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo1); // adjust format as needed
			imagedestroy($dst);
			
			$logo1 = $this->cjs_esc("/advert-uploads/".$logo1);
		}else{
			$logo1 = $this->cjs_esc($data['logo_hidden']);
		}
		
		//FILE 2
		if($_FILES['logo2']['tmp_name']){
			$rand = rand();
			$logo2 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo2']['tmp_name'], "../../../advert-uploads/".$logo2);
			
			//RESIZE
			$fn = "../../../advert-uploads/".$logo2;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagetruecolortopalette($dst, false, 255);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo2); // adjust format as needed
			imagedestroy($dst);
			
			$logo2 = $this->cjs_esc("/advert-uploads/".$logo2);
		}else{
			$logo2 = $this->cjs_esc($data['logo2_hidden']);
		}
		
		$email = false;
		$displayFrom = date('Y-m-d');
		
		if($advertID>0){
	    
	    	if($status=='draft'){
		    	$this->db->execute("UPDATE perch3_volunteers SET title='$title', advertText='$advertText', closingDateT='$closingDate', location='$location', payBenefits='$payBenefits', typeRole='$role', employerDisplay='$employer', imageLogo='$logo1', imageLogo2='$logo2', displayFrom='$displayFrom', advertStatus='$status', volunteerDynamicFields='$dynamicFields' WHERE volunteerID='$advertID'");
			}elseif($status=='pen'){
				$this->db->execute("UPDATE perch3_volunteers SET title='$title', advertText='$advertText', closingDateT='$closingDate', location='$location', payBenefits='$payBenefits', typeRole='$role', employerDisplay='$employer', imageLogo='$logo1', imageLogo2='$logo2', displayFrom='$displayFrom', advertStatus='$status', volunteerDynamicFields='$dynamicFields' WHERE volunteerID='$advertID'");
				$email = true;
				$subject = "CJS Volunteer Submission - Updated";
			}elseif($status=='pub'){
				$this->db->execute("UPDATE perch3_volunteers SET title='$title', advertText='$advertText', closingDateT='$closingDate', location='$location', payBenefits='$payBenefits', typeRole='$role', employerDisplay='$employer', imageLogo='$logo1', imageLogo2='$logo2', displayFrom='$displayFrom', advertStatus='$status', volunteerDynamicFields='$dynamicFields' WHERE volunteerID='$advertID'");
				$email = true;
				$subject = "CJS Volunteer Submission - Published";
			}elseif($status=='over'){
// 				$this->db->execute("UPDATE perch3_volunteers SET title='$title', closingDateT='$closingDate', location='$location', payBenefits='$payBenefits', typeRole='$role', employerDisplay='$employer', imageLogo='$logo1', imageLogo2='$logo2', displayFrom='$displayFrom', advertStatus='pub', volunteerDynamicFields='$dynamicFields' WHERE volunteerID='$advertID'");
				$query = "SELECT * FROM perch3_volunteers WHERE advertStatus='pub' AND volunteerID='$advertID'";
				$result =  $this->db->get_rows($query);
				$advertRef = $result[0]['advertRef'];
				$this->db->execute("INSERT INTO perch3_volunteers (title, advertRef, memberID, employerDisplay, employerID, advertText, closingDate, closingDateT, intDate, location, payBenefits, tcOccasional, tcOnceorTwiceWeek, tcPartTime, tcFullTime, tcSixMonths, tcSixMonthYear, tcOneYearMore, vidEmbedC, typeRole, sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, rNational, rInternational, rScotland, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rCounty, accommodation, expenses, fTraining, imageLogo, imageAlt, imageLogo2, imageAlt2, longRun, deleteDate, displayUntil, freeLin, displayFrom, archive, advertStatus, volunteerDynamicFields) SELECT '$title', '$advertRef', memberID, '$employer', employerID, '$advertText', closingDate, '$closingDate', intDate, '$location', '$payBenefits', tcOccasional, tcOnceorTwiceWeek, tcPartTime, tcFullTime, tcSixMonths, tcSixMonthYear, tcOneYearMore, vidEmbedC, '$role', sAdminIT, sArbo, sComVol, sCountry, sEco, sEnvOE, sHorti, sPubF, sRoW, sVisMan, sWiWoAnC, rNational, rInternational, rScotland, rWales, rNorthernIreland, rEngland, rAnglia, rMidlands, rNorthEast, rNorthWest, rSouth, rSouthWest, rCounty, accommodation, expenses, fTraining, '$logo1', imageAlt, '$logo2', imageAlt2, longRun, deleteDate, displayUntil, freeLin, displayFrom, archive, '$status', '$dynamicFields' FROM perch3_volunteers WHERE volunteerID = '$advertID'");
				$email = true;
				$subject = "CJS Volunteer Updated";
			}
	    
	    }else{
		    
		    $advertRef = "vol_".rand();
		   
		    if($status=='draft'){
				$this->db->execute("INSERT INTO perch3_volunteers (title, advertText, advertRef, memberID, closingDateT, location, payBenefits, employerDisplay, typeRole, imageLogo, imageLogo2, displayFrom, advertStatus, volunteerDynamicFields) VALUES ('$title', '$advertText', '$advertRef', '$memberID', '$closingDate', '$location', '$payBenefits', '$employer', '$role', '$logo1', '$logo2', '$displayFrom', '$status', '$dynamicFields')");
			}elseif($status=='pen'){
				$this->db->execute("INSERT INTO perch3_volunteers (title, advertText, advertRef, memberID, closingDateT, location, payBenefits, employerDisplay, typeRole, imageLogo, imageLogo2, displayFrom, advertStatus, volunteerDynamicFields) VALUES ('$title', '$advertText', '$advertRef', '$memberID', '$closingDate', '$location', '$payBenefits', '$employer', '$role', '$logo1', '$logo2', '$displayFrom', '$status', '$dynamicFields')");
				$email = true;
				$subject = "CJS Volunteer Submission - Pending";
			}elseif($status=='over'){
				$this->db->execute("INSERT INTO perch3_volunteers (title, advertText, advertRef, memberID, closingDateT, location, payBenefits, employerDisplay, typeRole, imageLogo, imageLogo2, displayFrom, advertStatus, volunteerDynamicFields) VALUES ('$title', '$advertText', '$advertRef', '$memberID', '$closingDate', '$location', '$payBenefits', '$employer', '$role', '$logo1', '$logo2', '$displayFrom', '$status', '$dynamicFields')");
				$email = true;
				$subject = "CJS Volunteer Update";
			}elseif($status=='pub'){
				$this->db->execute("INSERT INTO perch3_volunteers (title, advertText, advertRef, memberID, closingDateT, location, payBenefits, employerDisplay, typeRole, imageLogo, imageLogo2, displayFrom, advertStatus, volunteerDynamicFields) VALUES ('$title', '$advertText', '$advertRef', '$memberID', '$closingDate', '$location', '$payBenefits', '$employer', '$role', '$logo1', '$logo2', '$displayFrom', '$status', '$dynamicFields')");
				$email = true;
				$subject = "CJS Volunteer Submission - Published";
			}else{
				$this->db->execute("INSERT INTO perch3_volunteers (title, advertText, advertRef, memberID, closingDateT, location, payBenefits, employerDisplay, typeRole, imageLogo, imageLogo2, displayFrom, advertStatus, volunteerDynamicFields) VALUES ('$title', '$advertText', '$advertRef', '$memberID', '$closingDate', '$location', '$payBenefits', '$employer', '$role', '$logo1', '$logo2', '$displayFrom', 'pen', '$dynamicFields')");
				$email = true;
				$subject = "CJS Volunteer Submission - Pending";
			}
		    
	    }
	    
	    if($email){
		    // Multiple recipients
			$to = 'ranger@countryside-jobs.com';
			
			// Message
			$emailHTML = "
			<html>
			<head>
			  <title>CJS Free Volunteer Submission</title>
			</head>
			<body>
			  <p>Title: ".stripslashes($title)."<br />
			  Text: ".stripslashes($advertText)."<br />
			  Closing Date: $closingDate<br />
			  Location: $location<br />
			  Pay/Benefits: $payBenefits<br />
			  Employer: ".stripslashes($employer)."<br />
			  Role: ".stripslashes($role)."<br />
			  Time: $time<br />
			  End Date: $enddate<br />";
			if($logo1){
			  $emailHTML .= "Logo 1: https://countryside-jobs.com$logo1<br />";
			}
			if($logo2){
			  $emailHTML .= "Logo 2: https://countryside-jobs.com$logo2<br />";
			}
			$emailHTML .= "
			  Enhanced: $enhanced<br />
			  Name: ".stripslashes($name)."<br />
			  Organisation: ".stripslashes($organisation)."<br />
			  Email: ".stripslashes($emailAddress)."<br />
			  Telephone: $telephone<br />
			  Message: ".stripslashes($message)."<br />
			  Referral: $howdidyoufindus<br />
			  MemberID: $memberID<br />
			  Advert Status: $advertStatus</p>
			</body>
			</html>
			";
			
			// To send HTML mail, the Content-type header must be set
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			
			// Additional headers
			$headers[] = 'From: no-reply@countryside-jobs.com';
			$headers[] = 'Reply-To: '.$emailAddress;
			
			// Mail it
			mail($to, $subject, $emailHTML, implode("\r\n", $headers));
	    }
	    
	    
    }else{
	    echo 'robot';
    }
  }
  
  public function submitFreeAdvert($data){

	if($data->data['enquiry']==''){
		
		$title = addslashes(str_replace("\"","'",$data->data['jobtitle']));
	    $closingdate = addslashes($data->data['closingdate']);
	    $location = addslashes(str_replace("\"","'",$data->data['location']));
	    $salary = addslashes($data->data['salary']);
	    $employer = addslashes(str_replace("\"","'",$data->data['employer']));
	    $advertText = addslashes(str_replace("\"","'",$data->data['text']));
	    $text1 = addslashes($data->data['text']);
	    $enhanced = $this->cjs_esc($data->data['enhanced']);
	    
	    $name = addslashes(str_replace("\"","'",$data->data['name']));
	    $organisation = addslashes(str_replace("\"","'",$data->data['organisation']));
	    $emailAddress = addslashes($data->data['email']);
	    $telephone = $this->cjs_esc($data->data['telephone']);
	    $message = addslashes(str_replace("\"","'",$data->data['message']));
	    $referral = $this->cjs_esc($data->data['howdidyoufindus']);
	    
	    $advertStatus = $this->cjs_esc($data->data['status']);
	    $memberID = addslashes($data->data['memberID']);
	    $advertID = addslashes($data->data['advertID']);
	    
	    //$text = preg_replace( "/\r|\n/", "", htmlentities($data->data['text'], ENT_QUOTES));
	    
	    $jsonData = array();
	    
	    $data = $data->data;

	    //$text = $data['text'];
	    $text = strip_tags($advertText,"<br>,<p>,<ul>,<ol>,<li>,<a>");
	    $text = trim(preg_replace('/\s\s+/', ' ', $text));
		
		preg_match_all('/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>([^<]+)<\/a>/i', $advertText, $out);

		foreach($out[1] as $item){
			$long_url = str_replace('" target="_blank', '', $item);
			$apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
			$genericAccessToken = (defined('CJS_BITLY_TOKEN') ? CJS_BITLY_TOKEN : '');
			
			$data = array(
			    'long_url' => $long_url
			);
			$payload = json_encode($data);
			
			$header = array(
			    'Authorization: Bearer ' . $genericAccessToken,
			    'Content-Type: application/json',
			    'Content-Length: ' . strlen($payload)
			);
			
			$ch = curl_init($apiv4);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$result = curl_exec($ch);
			
			$result = json_decode($result,true);
			$bitly = $result['link'];
			$text = str_replace($long_url,$bitly,$text);
		}

		$text = addslashes($text);
		$emailText = $text;
	    
	    $text = array(
		  'description' => array(
		    '_flang' => "html",
		    'raw' => $text,
		    'processed' => $text
		  )
		);
		
		$jsonData = array_merge($jsonData,$text);
	    
	    $json = json_encode($jsonData,JSON_HEX_APOS);
	    
	    $dynamicFields = $this->cjs_esc($json);
	    
	    //$dynamicFields = "{\"description\":{\"_flang\":\"html\",\"raw\":\"".stripslashes($text)."\",\"processed\":\"".stripslashes($text)."\"}}";
	    
	    //FILE 1
		if($_FILES['logo']['tmp_name']){
			$rand = rand();
			$logo1 = $this->cjs_esc($rand.".jpg");
			move_uploaded_file($_FILES['logo']['tmp_name'], "../../../advert-uploads/".$logo1);

			//RESIZE
			$fn = "../../../advert-uploads/".$logo1;
			$size = getimagesize($fn);
			$ratio = $size[0]/$size[1]; // width/height
			$width = 300;
			$height = 300/$ratio;
			$src = imagecreatefromstring(file_get_contents($fn));
			$dst = imagecreatetruecolor($width,$height);
			imagetruecolortopalette($dst, false, 255);
			imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
			imagedestroy($src);
			imagejpeg($dst,"../../../advert-uploads/".$logo1); // adjust format as needed
			imagedestroy($dst);
			
			$logo1 = $this->cjs_esc("/advert-uploads/".$logo1);
		}else{
			$logo1 = $this->cjs_esc($data['logo_hidden']);
		}
		
		$email = false;
		
		$date = date('Y-m-d');
		
		if($advertID>0){

	    	if($advertStatus=='draft'){
		    	$this->db->execute("UPDATE perch3_free_adverts SET title='$title', closingdate='$closingdate', location='$location', salary='$salary', employer='$employer', advertText='$advertText', logo='$logo1', enhanced='$enhanced', name='$name', organisation='$organisation', email='$emailAddress', telephone='$telephone', message='$message', referral='$referral', memberID='$memberID', advertStatus='$advertStatus', free_advertsDynamicFields='$dynamicFields' WHERE advertID='$advertID'");
			}elseif($advertStatus=='pen'){
				$this->db->execute("UPDATE perch3_free_adverts SET title='$title', closingdate='$closingdate', location='$location', salary='$salary', employer='$employer', advertText='$advertText', logo='$logo1', enhanced='$enhanced', name='$name', organisation='$organisation', email='$emailAddress', telephone='$telephone', message='$message', referral='$referral', memberID='$memberID', advertStatus='$advertStatus', free_advertsDynamicFields='$dynamicFields' WHERE advertID='$advertID'");
				$email = true;	
				$subject = 'CJS Free Advert Re-Submission';
			}
	    
	    }else{
		    
		    $advertRef = "vol_".rand();
		    
		    if($advertStatus=='draft'){
				$this->db->execute("INSERT INTO perch3_free_adverts (title, closingdate, location, salary, employer, advertText, logo, enhanced, name, organisation, email, telephone, message, referral, memberID, advertStatus, dateSubmitted, free_advertsDynamicFields) VALUES ('$title', '$closingdate', '$location', '$salary', '$employer', '$advertText', '$logo1', '$enhanced', '$name', '$organisation', '$emailAddress', '$telephone', '$message', '$referral', '$memberID', '$advertStatus', '$date', '$dynamicFields')");
			}elseif($advertStatus=='pen'){
				$this->db->execute("INSERT INTO perch3_free_adverts (title, closingdate, location, salary, employer, advertText, logo, enhanced, name, organisation, email, telephone, message, referral, memberID, advertStatus, dateSubmitted, free_advertsDynamicFields) VALUES ('$title', '$closingdate', '$location', '$salary', '$employer', '$advertText', '$logo1', '$enhanced', '$name', '$organisation', '$emailAddress', '$telephone', '$message', '$referral', '$memberID', '$advertStatus', '$date', '$dynamicFields')");
				$email = true;
				$subject = 'CJS Free Advert Submission';
			}else{
				$this->db->execute("INSERT INTO perch3_free_adverts (title, closingdate, location, salary, employer, advertText, logo, enhanced, name, organisation, email, telephone, message, referral, memberID, advertStatus, dateSubmitted, free_advertsDynamicFields) VALUES ('$title', '$closingdate', '$location', '$salary', '$employer', '$advertText', '$logo1', '$enhanced', '$name', '$organisation', '$emailAddress', '$telephone', '$message', '$referral', '$memberID', 'pen', '$date', '$dynamicFields')");
				$email = true;
				$subject = 'CJS Free Advert Submission';
			}
		    
	    }
	    
	    if($email){
		    // Multiple recipients
			$to = 'jack@jackbarber.co.uk,ranger@countryside-jobs.com';
			
			// Message
			$emailHTML = "
			<html>
			<head>
			  <title>CJS Free Advert Submission</title>
			</head>
			<body>
			  <p>Title: ".stripslashes($title)."<br />
			  Text: ".stripslashes($emailText)."<br />
			  Closing Date: $closingdate<br />
			  Location: $location<br />
			  Salary: $salary<br />
			  Employer: $employer<br />";
			if($logo1){
			  $emailHTML .= "Logo: https://countryside-jobs.com$logo1<br />";
			}
			$emailHTML .= "Enhanced: $enhanced<br />
			  Name: ".stripslashes($name)."<br />
			  Organisation: ".stripslashes($organisation)."<br />
			  Email: $emailAddress<br />
			  Telephone: $telephone<br />
			  Message: ".stripslashes($message)."<br />
			  Referral: $referral<br />
			  MemberID: $memberID<br />
			  Advert Status: $advertStatus</p>
			</body>
			</html>
			";
			
			// To send HTML mail, the Content-type header must be set
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			
			// Additional headers
			$headers[] = 'From: no-reply@countryside-jobs.com';
			$headers[] = 'Reply-To: '.$emailAddress;
			
			// Mail it
			mail($to, $subject, $emailHTML, implode("\r\n", $headers));
	    }
	    
    }else{
	    echo 'robot';
    }
  }
  
  public function dailyTextSearch($formData){
    $title = $formData['textSearch'];
    $today = date('Y-m-d');
    $result =  $this->db->get_rows("SELECT * FROM perch3_daily_jobs WHERE (title LIKE ".$this->db->pdb('%'.$title.'%')." OR daily_jobDynamicFields LIKE ".$this->db->pdb('%'.$title.'%').") AND closingDate>".$this->db->pdb($today)." AND advertStatus='pub' ORDER BY closingDate DESC");
    return $result;
  }
  
  public function dailySearch($formData){
	  $today = date('Y-m-d');
    $Query = "SELECT * FROM perch3_daily_jobs WHERE advertStatus='pub' AND closingDate>".$this->db->pdb($today)." AND (";
    $data = $formData;        
    
    (array_key_exists('select_region_daily', $data)) ? $regions = $data['select_region_daily'] : $regions = false;
    (array_key_exists('select_sector_daily', $data)) ? $sectors = $data['select_sector_daily'] : $sectors = false;
    (array_key_exists('select_county_daily', $data)) ? $counties = $data['select_county_daily'] : $counties = false;
    (array_key_exists('select_grade', $data)) ? $grades = $data['select_grade'] : $grades = false; 
    (array_key_exists('select_contract', $data)) ? $contracts = $data['select_contract'] : $contracts = false;
    
    $entered = false;
    
    if ($regions){
      $entered = true;
      foreach ((array)$regions as $region){
	      if($region=='rScotland'){
		    $Query.="".$this->cjs_ident($region)."='1' OR rScotlandNorth='1' OR rScotlandSouth='1' OR ";  
	      }elseif($region=='rEngland'){
		    $Query.="".$this->cjs_ident($region)."='1' OR rAnglia='1' OR rMidlands='1' OR rNorthEast='1' OR rNorthWest='1' OR rSouth='1' OR rSouthWest='1' OR ";  
	      }else{
		  	$Query.="".$this->cjs_ident($region)."='1' OR ";
		  }
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
    
    if ($grades){
      if (!in_array('Any', (array)$grades)){
        $entered = true;
        $Query.="grade IN (";
        foreach ((array)$grades as $grade){
          $Query.="".$this->db->pdb($grade).", ";
        }
      $Query = substr($Query, 0, -2);
      $Query.=")) AND (";       
      }else{
//         $entered = false;
      }
    }
    
    if ($contracts){     
      if (!in_array('Any', (array)$contracts)) {
        $entered = true;
        foreach ((array)$contracts as $contract){
          $Query.="".$this->cjs_ident($contract)."='1' OR ";
        }
        $Query = substr($Query, 0, -4);
        $Query.=") AND (";        
      }else{
//         $entered = false;
      }
    }
    
    if ($entered){
      //if any filter was entered, remove " AND (" at the end of $Query
      $Query = substr($Query, 0, -6);
    }else{
      //if no filter entered, then it removes " WHERE (" so it gets the entire DB of jobs without any filter
      $Query = substr($Query, 0, -8);
    }
    
    //echo $Query;

    return $this->db->get_rows($Query);
  }
  

  public function get_volunteer_adverts_sector($sector){
	
	$today = date('Y-m-d');
	$query = "SELECT * FROM perch3_daily_jobs WHERE postedDate<='$today' AND $sector='1' AND cVolunteer='1' AND closingDate>'$today' AND advertStatus='pub' ORDER BY closingDate DESC";
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_advert_by_slug($slug,$draft){
	
	$today = date('Y-m-d');
	/* $slug comes straight from the URL, so it must be escaped. */
	$slug = $this->db->pdb($slug);

	if($draft=='draft'){
		$query = "SELECT * FROM perch3_daily_jobs WHERE advertURL=$slug";	
	}else{
		$query = "SELECT * FROM perch3_daily_jobs WHERE advertURL=$slug AND advertStatus='pub' AND postedDate<='$today'";
	}
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_advert_by_id($id){

	$query = "SELECT * FROM perch3_daily_jobs WHERE daily_jobID=".$this->db->pdb($id)."";	
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_free_advert_by_id($id){

	$query = "SELECT * FROM perch3_free_adverts WHERE advertID=".$this->db->pdb($id)."";	
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_advert_draft_by_job_url($url){

	$query = "SELECT * FROM perch3_daily_jobs WHERE advertURL=".$this->db->pdb($url)." AND advertStatus='over'";	
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_vol_advert_draft_by_job_ref($ref){
	  
	  $query = "SELECT * FROM perch3_volunteers WHERE advertRef=".$this->db->pdb($ref)." AND advertStatus='over'";	
		$result =  $this->db->get_rows($query);
		return $result;
	  
  }
  
  public function delete_overwritten_job($url){
	
	if($url<>''){
			
		$query = "DELETE FROM perch3_daily_jobs WHERE advertURL=".$this->db->pdb($url)." AND (advertStatus='pub' OR advertStatus='pen' OR advertStatus='sus')";	
		$result =  $this->db->execute($query);
		return $result;
	
	}
	  
  }
  
  public function delete_overwritten_vol_job($ref){
	
	if($ref<>''){
			
		$query = "DELETE FROM perch3_volunteers WHERE advertRef=".$this->db->pdb($ref)." AND (advertStatus='pub' OR advertStatus='pen' OR advertStatus='sus')";	
		$result =  $this->db->execute($query);
		return $result;
	
	}
	  
  }
  
  public function draft_exists($url){

	$query = "SELECT * FROM perch3_daily_jobs WHERE advertURL=".$this->db->pdb($url)." AND advertStatus='over'";	
	$result =  $this->db->get_rows($query);
	if(count($result)>0){
		return true;
	}else{
		return false;
	}
	  
  }
  
  public function draft_vol_exists($ref){

	$query = "SELECT * FROM perch3_volunteers WHERE advertRef=".$this->db->pdb($ref)." AND advertStatus='over'";	
	$result =  $this->db->get_rows($query);
	if(count($result)>0){
		return true;
	}else{
		return false;
	}
	  
  }
  
  public function get_app_ints(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$properties = new CJS_Daily_Jobs($API);
	  
	$Template = $API->get('Template');
	
    $today = date('Y-m-d');

	$query = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($today).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' AND (cApprentice="1" OR cIntern="1") ORDER BY daily_jobID DESC');
	foreach($query as $data){

		$Template->set('cjs_daily/job_adverts.html', 'cjs');
		$fields = PerchUtil::json_safe_decode($data['daily_jobDynamicFields'], true);
	
		$data = array_merge($fields,$data);
		$html = $Template->render($data, true);
		$html = $Template->apply_runtime_post_processing($html, $data);  
		echo $html;

	}

  }
  
  public function get_volunteer_additions(){
	
	$date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-7, date('Y')));
	$today = date("Y-m-d");
	$query = 'SELECT * FROM perch3_daily_jobs WHERE postedDate<='.$this->db->pdb($today).' AND advertStatus="pub" AND closingDate>'.$this->db->pdb($today).' AND cVolunteer="1"';
	$result =  $this->db->get_rows($query);
	return $result;
	  
  }
  
  public function get_count_month($month){
	  
	  $results = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE advertURL LIKE '.$this->db->pdb('%'.$month.'%').'');
      return(count($results));
  }
  
  public function memberJobs($memberID){
	  
	  $results = $this->db->get_rows('SELECT * FROM perch3_daily_jobs WHERE memberID='.$this->db->pdb($memberID).' ORDER BY daily_jobID DESC');
	  return $results;
	  
  }
  
  public function memberFreeJobs($memberID){
	  
	  $results = $this->db->get_rows('SELECT * FROM perch3_free_adverts WHERE memberID='.$this->db->pdb($memberID).' ORDER BY advertID DESC');
	  return $results;
	  
  }
  
  public function logBlogView($slug){
	
	// GET FOCUS OR NEWS
	$sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'blog_posts WHERE postSlug='.$this->db->pdb($slug);
	$row = $this->db->get_row($sql);
	  
	$this->db->execute("INSERT INTO perch3_blog_log (slug, type) VALUES (".$this->db->pdb($slug).", ".$this->db->pdb($row['postTemplate']).")");
  }
  
  public function blog_log(){
	  $sql = "SELECT slug, type, COUNT(*) as occurrences
	  FROM perch3_blog_log
	  WHERE 
		  timestamp >= NOW() - INTERVAL 7 DAY
		  AND type != ''
		  AND type NOT LIKE '%scientific%'
	  GROUP BY slug
	  ORDER BY occurrences DESC
	  LIMIT 10;";
	  $results = $this->db->get_rows($sql);
	  return $results;
  }
  
  public function subscribe($data){
  
	if($data->data['email']){
  
		$curl = curl_init();
		
		curl_setopt_array($curl, [
		  CURLOPT_URL => "https://api.brevo.com/v3/contacts",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode([
			'updateEnabled' => false,
			'email' => $data->data['email'],
			'listIds' => [
				4
			]
		  ]),
		  CURLOPT_HTTPHEADER => [
			"accept: application/json",
			"api-key: " . (defined('CJS_BREVO_API_KEY') ? CJS_BREVO_API_KEY : ''),
			"content-type: application/json"
		  ],
		]);
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
		  //echo "cURL Error #:" . $err;
		} else {
		  //echo $response;
		}
	}
		
  }
  
}