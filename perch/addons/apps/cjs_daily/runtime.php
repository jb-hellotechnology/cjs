<?php


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

  
    include('CJS_Daily.Job.class.php');
    include('CJS_Daily.Jobs.class.php');
    include('CJS_Daily.Employer.class.php');
    include('CJS_Daily.Employers.class.php');
    include('CJS_Daily.Volunteer.class.php');
    include('CJS_Daily.Volunteers.class.php');
    include('CJS_Daily.Advertiser_Message.class.php');
    include('CJS_Daily.Advertiser_Messages.class.php');
    include('CJS_Daily.Search.class.php');
    
    PerchSystem::register_search_handler('CJS_Daily_SearchHandler');

function cjs_daily_form_handler($SubmittedForm)
{

  $CJS_Daily = new CJS_Daily_Jobs;
  $CJS_Daily_Volunteers = new CJS_Daily_Volunteers;

  if ($SubmittedForm->validate()) {

    $API  = new PerchAPI(1.0, 'cjs_daily');

    switch($SubmittedForm->formID) {

      case 'cjs_online':
      $CJS_Daily->submitDailyAdvert($SubmittedForm);
      break;
        
      case 'cjs_volunteer':
      $CJS_Daily->submitVolunteerAdvert($SubmittedForm);
      break;
      
      case 'cjs_free':
      $CJS_Daily->submitFreeAdvert($SubmittedForm);
      break;
      
      case 'subscribe':
      $CJS_Daily->subscribe($SubmittedForm);
      break;

    }            

  }
}

function all_jobs(){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $jobs->get_all();
  
}

function home_list(){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $list = $jobs->get_all_home();
  
  return $list;
  
}

function cjs_volunteers_feed(){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $volunteers = new CJS_Daily_Volunteers($API);

  $list = $volunteers->get_all_feed();

}

function cjs_jobs_feed(){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $list = $jobs->get_all_feed();

}

function cjs_jobs_sector_feed($sector){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $list = $jobs->get_sector_feed($sector);

}

function cjs_jobs_region_feed($region){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $list = $jobs->get_region_feed($region);

}

/*
 * The <url> entries for every live advert, for /sitemap.xml.
 *
 * Each one carries a lastmod, which is what Google uses to decide when a job
 * page is worth recrawling; the old plain-text list of URLs gave it nothing to
 * go on. Closed adverts are left out, as they always were.
 */
function cjs_jobs_sitemap(){

  $API = new PerchAPI(1.0, 'cjs_daily');
  $DB  = $API->get('DB');

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts_sitemap.html', 'cjs');

  $today = date('Y-m-d');
  $rows  = $DB->get_rows('SELECT advertURL, postedDate, advertAmendedDate, closingDate
                            FROM '.PERCH_DB_PREFIX.'daily_jobs
                           WHERE advertStatus="pub" AND advertURL!="" AND postedDate<="'.$today.'" AND closingDate>="'.$today.'"
                        ORDER BY daily_jobID DESC');

  if (!PerchUtil::count($rows)) return;

  foreach($rows as $row){

    /* Amended adverts are republished; the rest last changed when posted. */
    $lastmod = cjs_schema_is_date($row['advertAmendedDate']) ? $row['advertAmendedDate'] : $row['postedDate'];
    if (!cjs_schema_is_date($lastmod)) $lastmod = $today;

    $row['lastmod'] = substr($lastmod, 0, 10);

    $html = $Template->render($row, true);
    $html = $Template->apply_runtime_post_processing($html, $row);
    echo $html;

  }
}

function cjs_jobs_feed_2(){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $list = $jobs->get_all_feed_2();

}

function all_jobs_organisation($organisation){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $organisations = new CJS_Organisations_Orgs($API);
  
  $organisationData = $organisations->get_by_slug($organisation);
  
  $list = $jobs->get_by_organisation($organisationData['organisationID']);

}

function all_volunteers_organisation($organisation){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $organisations = new CJS_Organisations_Orgs($API);
  
  $organisationData = $organisations->get_by_slug($organisation);

  $list = $jobs->get_volunteers_by_organisation($organisationData['organisationID']);

}

function sector_jobs($sector){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $jobs->get_sector($sector);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
  }else{
	echo '<div class="listing"><p><strong>Sorry, no vacancies in this sector currently advertised. Please check back soon.</strong></p></div>';
  }
}

function grade_jobs($sector){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $jobs->get_grade($sector);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
  }else{
	echo '<div class="listing"><p><strong>Sorry, no vacancies in this grade currently advertised. Please check back soon.</strong></p></div>';
  }
}

function region_jobs($region){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $jobs->get_region($region);

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
  }else{
	echo '<div class="listing"><p><strong>Sorry, no vacancies in this area currently advertised. Please check back soon.</strong></p></div>';
  }
  
}

function advertiser_jobs($slug){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $advertisers = new CJS_Daily_Employers($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');
  
  $allAdvertisers = $advertisers->all();
  foreach ($allAdvertisers as $advertiser){
    if ($advertiser->slug() == $slug){
      $employerID = $advertiser->employerID();
    }
  }
  $list = $jobs->get_advertiser($employerID);

  $html = $Template->render_group($list, true);
  $html = $Template->apply_runtime_post_processing($html, $list);  

  echo $html;  
}

function volunteers_adverts_sector($sector){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $daily = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  
  $Template = $API->get('Template');
/*
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $daily->get_volunteer_adverts_sector($sector);

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
*/

  $Template->set('cjs_daily/job_adverts_volunteers.html', 'cjs');

  $list = $volunteers->get_volunteer_adverts_sector($sector);

  if(count($list)>0){
  
	  foreach($list as $item){
		  
	  	if($item['volunteerID']>0){
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);
		if(is_array($fields)){
			
			$item = array_merge($fields,$item);
				
			$html = $Template->render($item, true);
			$html = $Template->apply_runtime_post_processing($html, $item);  
			echo $html;
		}
		}
		
	}
	
  }
  
}

function volunteers_adverts_region($sector){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $daily = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  
  $Template = $API->get('Template');
/*
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $daily->get_volunteer_adverts_sector($sector);

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
*/

  $Template->set('cjs_daily/job_adverts_volunteers.html', 'cjs');

  $list = $volunteers->get_volunteer_adverts_region($sector);

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
  
}


/* ==========================================================================
   CJS_SCHEMA_HELPERS_START
   --------------------------------------------------------------------------
   Schema.org JobPosting support for cjs_daily/job_advert.html.

   The JSON-LD used to be hand written in the template. Perch HTML-encodes
   template values unless told otherwise, so the advert body reached Google as
   &lt;p&gt;... inside the JSON string, and a template cannot drop a property
   it has no value for. Everything is built here instead and handed to the
   template as one ready-encoded block.
   ========================================================================== */

/* Adverts run until 5pm UK time on their closing date. */
if (!defined('CJS_JOB_CLOSING_HOUR')) define('CJS_JOB_CLOSING_HOUR', 17);

function cjs_schema_site_url(){
	return 'https://www.countryside-jobs.com';
}

function cjs_schema_timezone(){
	static $tz = null;
	if ($tz === null) $tz = new DateTimeZone('Europe/London');
	return $tz;
}

function cjs_schema_webroot(){
	return rtrim(dirname(PERCH_PATH), DIRECTORY_SEPARATOR);
}

/* A usable Y-m-d? The table uses 0000-00-00 for "not set". */
function cjs_schema_is_date($date){
	$date = trim((string)$date);
	if ($date === '' || substr($date, 0, 10) == '0000-00-00') return false;
	return (bool)preg_match('/^\d{4}-\d{2}-\d{2}/', $date);
}

/* The moment an advert stops being live, or null if it has no closing date. */
function cjs_job_closing_time($closingDate){
	if (!cjs_schema_is_date($closingDate)) return null;
	$closes = DateTime::createFromFormat(
		'Y-m-d H:i:s',
		substr($closingDate, 0, 10).' '.sprintf('%02d:00:00', CJS_JOB_CLOSING_HOUR),
		cjs_schema_timezone()
	);
	return $closes ? $closes : null;
}

/* ---------------------------------------------------------------- description */

/*
 * The advert body as HTML Google will accept. Google wants real markup with at
 * least paragraph breaks, and ignores anything outside a small set of tags.
 */
function cjs_schema_description($html){
	$html = (string)$html;
	if (trim($html) === '') return '';

	/* Mojibake left by older imports, as corrected in the display templates. */
	$html = str_replace(
		array('Â£', 'â€™', 'â€˜', 'â€œ', 'â€�', 'â€“', 'â€”', 'Â '),
		array('£',  "'",   "'",   '"',   '"',   '–',   '—',   ' '),
		$html
	);

	/* Old imports are sometimes Windows-1252 rather than UTF-8. */
	if (!mb_check_encoding($html, 'UTF-8')) $html = mb_convert_encoding($html, 'UTF-8', 'Windows-1252');

	/* strip_tags keeps the text inside script and style, so remove those whole. */
	$html = preg_replace('#<(script|style)\b[^>]*>.*?</\1\s*>#is', ' ', $html);

	/* A div is a block of text; treat it as a paragraph rather than gluing it to its neighbours. */
	if (!preg_match('/<p\b/i', $html)) {
		$html = preg_replace('#<div\b[^>]*>#i', '<p>', $html);
		$html = preg_replace('#</div\s*>#i', '</p>', $html);
	}

	$html = strip_tags($html, '<p><br><ul><ol><li><b><strong><i><em>');

	/* Non-breaking spaces and line breaks are whitespace, not content. The old
	   code deleted newlines outright, which glued the surrounding words
	   together. */
	$html = str_replace(array('&nbsp;', '&#160;', '&#xA0;', "\xC2\xA0"), ' ', $html);
	$html = preg_replace('/[\r\n\t]+/', ' ', $html);
	$html = preg_replace('/ {2,}/', ' ', $html);
	$html = trim($html);

	/* One unbroken run of text reads to Google as a poorly formatted posting. */
	if ($html !== '' && !preg_match('/<(p|br|li)\b/i', $html)) {
		$html = '<p>'.$html.'</p>';
	}

	return $html;
}

/* -------------------------------------------------------------------- address */

/*
 * Pull the first UK postcode out of $text and remove every postcode from it.
 * Advertisers often end the location field with one, and a few give nothing
 * else.
 */
function cjs_schema_extract_postcode(&$text){
	$pattern = '/\b([A-Z]{1,2}[0-9][A-Z0-9]?)\s*([0-9][A-Z]{2})\b/i';
	if (!preg_match($pattern, $text, $m)) return '';
	$text = trim(preg_replace($pattern, ' ', $text));
	return strtoupper($m[1]).' '.strtoupper($m[2]);
}

function cjs_schema_normalise_postcode($postcode){
	$postcode = strtoupper(preg_replace('/\s+/', '', (string)$postcode));
	if ($postcode === '') return '';
	if (!preg_match('/^[A-Z]{1,2}[0-9][A-Z0-9]?[0-9][A-Z]{2}$/', $postcode)) return '';
	return substr($postcode, 0, -3).' '.substr($postcode, -3);
}

/* Tidy one comma-separated piece of an address before it is considered. */
function cjs_schema_tidy_part($part){
	$part = trim((string)$part);

	/* "Woolley Firs near Maidenhead" is really about Maidenhead. */
	if (preg_match('/\b(?:near|nr\.?)\b\s+(.+)$/i', $part, $m)) $part = $m[1];

	$part = preg_replace('/\s*\([^)]*\)\s*/', ' ', $part);   // "Linmere (Houghton Regis)"
	$part = str_replace(array('(', ')'), ' ', $part);            // a bracket split across commas
	$part = preg_replace('/\s+/', ' ', $part);

	return trim($part, " \t\n\r\0\x0B-/&.,;:");
}

/* Wording that describes an arrangement rather than naming a place. */
function cjs_schema_is_vague($value){
	$words = array('remote', 'remotely', 'home based', 'home-based', 'homebased', 'home', 'hybrid',
	               'flexible', 'various', 'multiple', 'nationwide', 'countrywide', 'anywhere',
	               'travel', 'travelling', 'throughout', 'across', 'ideally', 'tbc', 'tba',
	               'negotiable', 'field based', 'field-based', 'site based', 'site-based');
	$value = mb_strtolower(trim((string)$value));
	foreach ($words as $word) {
		if (preg_match('/\b'.preg_quote($word, '/').'\b/u', $value)) return true;
	}
	return false;
}

/*
 * Does this read as the name of a town or village? addressLocality is what
 * Google matches against a real place, so it is better left out than filled
 * with a sentence, a county or a list of options.
 */
function cjs_schema_is_locality($value, $county = ''){
	$value = trim((string)$value);
	if ($value === '' || mb_strlen($value) > 40) return false;

	/* Digits, slashes and "x or y" / "x to y" mean this is not one place. */
	if (preg_match('/[0-9@\/]|\bor\b|\bto\b|\bnr\b|\bvia\b|\band\/or\b/i', $value)) return false;

	if (cjs_schema_is_vague($value)) return false;
	if (str_word_count($value, 0, "'-") > 4) return false;

	/* Regions and nations are not localities. */
	$regions = array('national', 'international', 'midlands', 'the midlands', 'home counties',
	                 'highlands', 'east anglia', 'anglia', 'borders', 'scottish borders', 'welsh borders', 'north', 'south', 'east', 'west',
	                 'north east', 'north west', 'south east', 'south west', 'england',
	                 'scotland', 'wales', 'northern ireland', 'britain', 'uk', 'united kingdom',
	                 'north east england', 'north west england', 'south east england',
	                 'south west england', 'northern england', 'southern england', 'central england',
	                 'north wales', 'mid wales', 'south wales', 'west wales', 'east wales',
	                 'northern scotland', 'southern scotland', 'central scotland');
	if (in_array(mb_strtolower($value), $regions, true)) return false;

	/* County names in their own right: "Inverness-shire", "County Durham". */
	if (preg_match('/(?:^|[\s-])shire$|shire$|^county\s|\s(?:borough|district)$/i', $value)) return false;

	if ($county !== '') {
		if (strcasecmp($value, $county) == 0) return false;
		/* "North Devon" where the county is Devon, "East Kent" where it is Kent. */
		if (preg_match('/^(north|south|east|west|mid|central|greater|upper|lower)[\s-]+(east|west)?\s*'.preg_quote($county, '/').'$/i', $value)) return false;
	}

	return true;
}

/* "Banham Zoo" is in Banham; "Stevenage Campus" is in Stevenage. */
function cjs_schema_trim_site_suffix($value){
	$trimmed = preg_replace('/\s+(zoo|campus|depot|offices|office|hq|headquarters)$/i', '', (string)$value);
	$trimmed = trim($trimmed);
	return ($trimmed === '') ? $value : $trimmed;
}

/*
 * Split the two free-text address fields into the parts Google expects.
 * Returns streetAddress, addressLocality, addressRegion and postalCode; any of
 * them may be an empty string, in which case it is left out of the markup.
 */
function cjs_schema_address($item){
	$street = isset($item['streetAddress']) ? trim((string)$item['streetAddress']) : '';
	$place  = isset($item['location'])      ? trim((string)$item['location'])      : '';
	$county = cjs_schema_region(isset($item['rCounty']) ? $item['rCounty'] : '');
	$post   = cjs_schema_normalise_postcode(isset($item['postCode']) ? $item['postCode'] : '');

	if ($street == 'NULL') $street = '';
	if ($place  == 'NULL') $place  = '';

	/* The admin form offers both fields and advertisers routinely paste the
	   whole address into each of them. */
	if ($street !== '' && $place !== '') {
		if (stripos($street, $place) !== false)     $place  = '';
		elseif (stripos($place, $street) !== false) $street = '';
	}

	$full = trim($street.(($street !== '' && $place !== '') ? ', ' : '').$place);
	$full = str_replace('&amp;', '&', $full);

	$fromText = cjs_schema_extract_postcode($full);
	if ($post === '') $post = cjs_schema_normalise_postcode($fromText);

	$parts = array();
	foreach (preg_split('/\s*[,;]\s*/', $full) as $part) {
		$part = cjs_schema_tidy_part($part);
		if ($part !== '') $parts[] = $part;
	}

	/* Drop a trailing county or nation: those belong in addressRegion. */
	$nations = array('uk', 'u.k.', 'united kingdom', 'great britain', 'gb', 'britain',
	                 'england', 'scotland', 'wales', 'northern ireland');
	while (count($parts)) {
		$last = mb_strtolower(end($parts));
		if (in_array($last, $nations, true) || ($county !== '' && strcasecmp($last, $county) == 0)) {
			array_pop($parts);
			continue;
		}
		break;
	}

	/* Work back from the end for the first part that names a town. Anything
	   before it is the street address. */
	$locality    = '';
	$streetParts = array();
	for ($i = count($parts) - 1; $i >= 0; $i--) {
		if (!cjs_schema_is_locality($parts[$i], $county)) continue;

		$locality = cjs_schema_trim_site_suffix($parts[$i]);
		for ($j = 0; $j < $i; $j++) {
			if (!cjs_schema_is_vague($parts[$j])) $streetParts[] = $parts[$j];
		}
		break;
	}

	return array(
		'streetAddress'   => implode(', ', $streetParts),
		'addressLocality' => $locality,
		'addressRegion'   => $county,
		'postalCode'      => $post,
	);
}

/* rCounty doubles as a "where" picker, so it is not always a region. */
function cjs_schema_region($county){
	$county = trim((string)$county);
	if ($county === '' || $county == 'NULL') return '';

	$notRegions = array('national', 'international', 'home based', 'home-based', 'homebased',
	                    'nationwide', 'various', 'multiple', 'remote', 'uk wide', 'uk', 'n/a', 'tbc');
	if (in_array(mb_strtolower($county), $notRegions, true)) return '';

	return $county;
}

/*
 * Coordinates for the advert, but only when there is exactly one pair: several
 * markers describe several places and would not match the single address.
 */
function cjs_schema_geo($item){
	$lats = array_filter(array_map('trim', explode(',', (string)(isset($item['lat']) ? $item['lat'] : ''))), 'strlen');
	$lngs = array_filter(array_map('trim', explode(',', (string)(isset($item['lng']) ? $item['lng'] : ''))), 'strlen');
	if (count($lats) != 1 || count($lngs) != 1) return null;

	$lat = (float)array_shift($lats);
	$lng = (float)array_shift($lngs);
	if ($lat == 0.0 && $lng == 0.0) return null;
	if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) return null;

	return array('@type' => 'GeoCoordinates', 'latitude' => $lat, 'longitude' => $lng);
}

/* ------------------------------------------------------------------------ logo */

/* Where a /jobs/logos/... URL lives on disk, or false if it is not ours. */
function cjs_schema_local_path($url){
	$path = trim((string)$url);
	if ($path === '') return false;

	if (preg_match('#^https?://#i', $path)) {
		$parsed = parse_url($path);
		if (!isset($parsed['host']) || stripos($parsed['host'], 'countryside-jobs.com') === false) return false;
		$path = isset($parsed['path']) ? $parsed['path'] : '';
	}

	$path = '/'.ltrim(rawurldecode($path), '/');
	if (strpos($path, '..') !== false) return false;

	/* Older adverts store /Jobs/logos/..., the directory is lower case. */
	$candidates = array($path, preg_replace('#^/Jobs/#', '/jobs/', $path));
	foreach ($candidates as $candidate) {
		$file = cjs_schema_webroot().str_replace('/', DIRECTORY_SEPARATOR, $candidate);
		if (is_file($file)) return $file;
	}

	return false;
}

function cjs_schema_absolute_url($url){
	$url = trim((string)$url);
	if ($url === '') return '';
	if (preg_match('#^https?://#i', $url)) return $url;
	return cjs_schema_site_url().'/'.ltrim($url, '/');
}

/*
 * Google only accepts organisation logos with an aspect ratio between 0.75 and
 * 2.5, so the wide CJS logos (260x81 is common) are thrown away. Pad those onto
 * a square white canvas once and cache the result next to the original.
 */
function cjs_schema_pad_logo($file, $width, $height){
	if (!function_exists('imagecreatetruecolor') || !function_exists('imagecreatefromstring')) return false;
	if ($width * $height > 20000000) return false;

	$dir = cjs_schema_webroot().DIRECTORY_SEPARATOR.'jobs'.DIRECTORY_SEPARATOR.'logos'.DIRECTORY_SEPARATOR.'schema';
	if (!is_dir($dir) && !@mkdir($dir, 0775, true)) return false;
	if (!is_writable($dir)) return false;

	$name   = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file));
	$name   = pathinfo($name, PATHINFO_FILENAME).'-square.png';
	$target = $dir.DIRECTORY_SEPARATOR.$name;
	$public = '/jobs/logos/schema/'.$name;

	if (is_file($target) && filemtime($target) >= filemtime($file)) return $public;

	$source = @imagecreatefromstring((string)@file_get_contents($file));
	if (!$source) return false;

	$side   = max($width, $height, 112);   // Google asks for at least 112px
	$canvas = imagecreatetruecolor($side, $side);
	$white  = imagecolorallocate($canvas, 255, 255, 255);
	imagefilledrectangle($canvas, 0, 0, $side, $side, $white);
	imagecopyresampled($canvas, $source,
		(int)(($side - $width) / 2), (int)(($side - $height) / 2), 0, 0,
		$width, $height, $width, $height);

	$written = @imagepng($canvas, $target);
	imagedestroy($canvas);
	imagedestroy($source);

	return $written ? $public : false;
}

/* The logo URL to publish: the original when its shape is acceptable, a padded
   copy when it is not, and the original again if padding is not possible. */
function cjs_schema_logo($url){
	$url = trim((string)$url);
	if ($url === '' || $url == 'NULL') return '';

	$file = cjs_schema_local_path($url);
	if ($file === false) return cjs_schema_absolute_url($url);

	$size = @getimagesize($file);
	if (!$size || empty($size[0]) || empty($size[1])) return cjs_schema_absolute_url($url);

	$ratio = $size[0] / $size[1];
	if ($ratio >= 0.75 && $ratio <= 2.5) return cjs_schema_absolute_url($url);

	$padded = cjs_schema_pad_logo($file, (int)$size[0], (int)$size[1]);
	return cjs_schema_absolute_url($padded === false ? $url : $padded);
}

/* ----------------------------------------------------------- other properties */

function cjs_schema_employment_types($item){
	$map = array(
		'cfullTime'  => 'FULL_TIME',
		'cpartTime'  => 'PART_TIME',
		'cTempFixed' => 'TEMPORARY',
		'cseasonal'  => 'TEMPORARY',
		'cIntern'    => 'INTERN',
		'cVolunteer' => 'VOLUNTEER',
	);

	$types = array();
	foreach ($map as $field => $type) {
		if (isset($item[$field]) && $item[$field] == '1' && !in_array($type, $types, true)) $types[] = $type;
	}

	return $types;
}

/* Only published with a period: a bare number could be hourly or annual. */
function cjs_schema_base_salary($item){
	$amount = isset($item['baseSalary']) ? trim((string)$item['baseSalary']) : '';
	if ($amount === '' || $amount == 'NULL' || !is_numeric($amount) || (float)$amount <= 0) return null;

	$unit = isset($item['salaryType']) ? strtoupper(trim((string)$item['salaryType'])) : '';
	if (!in_array($unit, array('HOUR', 'DAY', 'WEEK', 'MONTH', 'YEAR'), true)) return null;

	$amount = (float)$amount;

	return array(
		'@type'    => 'MonetaryAmount',
		'currency' => 'GBP',
		'value'    => array(
			'@type'    => 'QuantitativeValue',
			'value'    => ($amount == floor($amount)) ? (int)$amount : round($amount, 2),
			'unitText' => $unit,
		),
	);
}

function cjs_schema_identifier($item){
	$reference = isset($item['jobReference']) ? trim((string)$item['jobReference']) : '';
	$employer  = isset($item['employerDisplay']) ? trim((string)$item['employerDisplay']) : '';

	if ($reference !== '' && $reference != 'NULL') {
		return array(
			'@type' => 'PropertyValue',
			'name'  => ($employer !== '') ? $employer : 'Countryside Jobs Service',
			'value' => $reference,
		);
	}

	if (!empty($item['daily_jobID'])) {
		return array(
			'@type' => 'PropertyValue',
			'name'  => 'Countryside Jobs Service',
			'value' => 'CJS-'.$item['daily_jobID'],
		);
	}

	return null;
}

/* ------------------------------------------------------------------- the block */

/*
 * The whole JobPosting as a JSON string, ready to drop inside a script tag, or
 * an empty string when the advert has too little to describe. Tags are hex
 * encoded so a </script> in the advert body cannot close the block early.
 */
function cjs_job_schema($item){
	$title       = isset($item['title']) ? trim((string)$item['title']) : '';
	$description = cjs_schema_description(isset($item['advertText']) ? $item['advertText'] : '');
	if ($title === '' || $description === '') return '';

	$address       = cjs_schema_address($item);
	$postalAddress = array('@type' => 'PostalAddress');
	foreach (array('streetAddress', 'addressLocality', 'addressRegion', 'postalCode') as $key) {
		if ($address[$key] !== '') $postalAddress[$key] = $address[$key];
	}
	$postalAddress['addressCountry'] = 'GB';

	$location = array('@type' => 'Place', 'address' => $postalAddress);
	$geo      = cjs_schema_geo($item);
	if ($geo !== null && ($address['addressLocality'] !== '' || $address['postalCode'] !== '')) {
		$location['geo'] = $geo;
	}

	$organisation = array(
		'@type' => 'Organization',
		'name'  => (isset($item['employerDisplay']) && trim($item['employerDisplay']) !== '')
		             ? trim($item['employerDisplay'])
		             : 'Countryside Jobs Service',
	);
	$website = isset($item['employerURL']) ? trim((string)$item['employerURL']) : '';
	if ($website !== '' && $website != 'NULL' && preg_match('#^https?://#i', $website)) {
		$organisation['sameAs'] = $website;   // left out entirely when unknown
	}
	$logo = cjs_schema_logo(isset($item['imageLogo']) ? $item['imageLogo'] : '');
	if ($logo !== '') $organisation['logo'] = $logo;

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'JobPosting',
		'title'       => $title,
		'description' => $description,
	);

	if (cjs_schema_is_date(isset($item['postedDate']) ? $item['postedDate'] : '')) {
		$schema['datePosted'] = substr($item['postedDate'], 0, 10);
	}

	$closes = cjs_job_closing_time(isset($item['closingDate']) ? $item['closingDate'] : '');
	if ($closes !== null) $schema['validThrough'] = $closes->format('c');   // 5pm on the day

	$schema['hiringOrganization'] = $organisation;
	$schema['jobLocation']        = $location;

	$types = cjs_schema_employment_types($item);
	if (count($types)) $schema['employmentType'] = (count($types) == 1) ? $types[0] : $types;

	$salary = cjs_schema_base_salary($item);
	if ($salary !== null) $schema['baseSalary'] = $salary;

	$identifier = cjs_schema_identifier($item);
	if ($identifier !== null) $schema['identifier'] = $identifier;

	/* Applications go by email or the employer's own form, never from here. */
	$schema['directApply'] = false;

	return json_encode($schema,
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT |
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE);
}

/* ---------------------------------------------------------------- canonical */

/*
 * A self-referencing canonical tag for the header layouts. Pages load just as
 * happily with a query string on the end, so without one every tracking link
 * is a separate URL as far as a search engine is concerned.
 *
 * A page can set a 'canonical' layout variable (a path, or a full URL) where
 * the request URI is not the address it should be indexed under.
 */
function cjs_canonical_tag($domain){
  if (perch_layout_has('canonical')) {
    $canonical = trim(perch_layout_var('canonical', true));
    if (!preg_match('#^https?://#i', $canonical)) $canonical = $domain.'/'.ltrim($canonical, '/');
  }else{
    $canonical = $domain.strtok($_SERVER['REQUEST_URI'], '?');
  }

  echo '<link rel="canonical" href="'.PerchUtil::html($canonical).'" />'."\n";
}

/* CJS_SCHEMA_HELPERS_END */

/*
 * A versioned URL for one of our own stylesheets or scripts.
 *
 * The assets go out with a week of cache life, so a file we have changed needs
 * a changed URL or returning visitors keep the copy they already have. The
 * query string used to be rand(), which did guarantee that, but at the price
 * of every visitor re-downloading every file on every page view. A file's
 * modification time changes exactly when the file does, so browsers hold on to
 * it until the next deploy and no longer.
 */
function cjs_asset($path){
  $path = '/'.ltrim($path, '/');
  $file = rtrim($_SERVER['DOCUMENT_ROOT'], '/').$path;

  if (!is_file($file)) return $path;

  return $path.'?v='.filemtime($file);
}

/*
 * Where an advert stands right now: "live", "closed" (it ran but its closing
 * date has passed) or "missing" (no such advert, or it is not published).
 *
 * The page template uses this to send a real 404 or 410 instead of the empty
 * 200 page unknown and long-gone adverts used to return; Google reads those as
 * soft 404s, and an expired advert still carrying JobPosting markup is what
 * manual actions get issued for.
 *
 * Looked up once per slug, because the page asks several times over.
 */
function cjs_job_state($slug, $draft=false){
  static $cache = array();

  $key = $slug.'|'.($draft=='draft' ? 'draft' : 'live');
  if (isset($cache[$key])) return $cache[$key];

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $list = $jobs->get_advert_by_slug($slug, $draft);

  if (!PerchUtil::count($list)) {
    return $cache[$key] = array('state'=>'missing', 'item'=>null);
  }

  $item   = $list[0];
  $closes = cjs_job_closing_time($item['closingDate']);

  /* Drafts are previews of adverts that have not run yet. */
  $state = ($draft=='draft' || $closes===null || $closes->getTimestamp() >= time()) ? 'live' : 'closed';

  return $cache[$key] = array('state'=>$state, 'item'=>$item);
}

function cjs_job($slug,$draft){
  $state = cjs_job_state($slug, $draft);

  if ($state['state']=='missing') {
    echo '<p><strong>Sorry, this advert could not be found.</strong></p>';
    echo '<p>It may have been removed, or the address may be wrong. <a href="/jobs">Browse current vacancies</a> instead.</p>';
    return;
  }

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_advert.html', 'cjs');

  $item         = $state['item'];
  $organisation = $jobs->get_organisation($slug);

  $fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
  if (!is_array($fields)) $fields = array();

  $fields['employerURL']       = isset($organisation['websiteURL']) ? $organisation['websiteURL'] : '';
  $fields['description_plain'] = cjs_schema_description($item['advertText']);

  $item = array_merge($fields, $item);

  /* A closed advert keeps its page for anyone following an old link, but it
     loses its structured data: the page is served as 410 Gone. */
  $item['jobClosed']    = ($state['state']=='closed') ? '1' : '';
  $item['schemaJSONLD'] = ($state['state']=='live')   ? cjs_job_schema($item) : '';

  $html = $Template->render($item, true);
  $html = $Template->apply_runtime_post_processing($html, $item);
  echo $html;
}

function cjs_job_preview($id){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $employers = new CJS_Daily_Employers($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_advert.html', 'cjs');

  $list = $jobs->get_advert_by_id($id);

	foreach($list as $item){
		  
		$organisation = $jobs->get_organisation($slug);
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		$fields['description_plain'] = strip_tags($fields['fulltext']['processed'],"<p><ul><li>");
		$fields['employerURL'] = $organisation['websiteURL'];
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;

	}
}

function cjs_volunteer_preview($id){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  $employers = new CJS_Daily_Employers($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts_volunteers_preview.html', 'cjs');

  $list = $volunteers->get_advert_by_id($id);

	foreach($list as $item){
		  
		$organisation = $jobs->get_organisation($slug);
		
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);

		$fields['description'] = html_entity_decode($fields['description']['processed']);
		$fields['employerURL'] = $organisation['websiteURL'];
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;

	}
}

function cjs_free_preview($id){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  $employers = new CJS_Daily_Employers($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts_free_preview.html', 'cjs');

  $list = $jobs->get_free_advert_by_id($id);

	foreach($list as $item){
		  
		$organisation = $jobs->get_organisation($slug);
		
		$fields = PerchUtil::json_safe_decode($item['free_advertsDynamicFields'], true);

		$fields['description'] = html_entity_decode($fields['description']['processed']);
		$fields['employerURL'] = $organisation['websiteURL'];
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;

	}
}

function cjs_job_data($id){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $employers = new CJS_Daily_Employers($API);

  $list = $jobs->get_advert_by_id($id);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		  
		$organisation = $jobs->get_organisation($slug);
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		$fields['description_plain'] = strip_tags($fields['fulltext']['processed'],"<p><ul><li>");
		$fields['employerURL'] = $organisation['websiteURL'];
		$item = array_merge($fields,$item);
			
		return $item;

	}
  }
}

function cjs_vol_data($id){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  $employers = new CJS_Daily_Employers($API);

  $list = $volunteers->get_advert_by_id($id);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		  
		$organisation = $jobs->get_organisation($slug);

		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);

		$fields['description'] = strip_tags($fields['description']['processed'],"<p><ul><li>");
		$fields['employerURL'] = $organisation['websiteURL'];
		$item = array_merge($fields,$item);
	
		return $item;

	}
  }
}

function cjs_free_data($id){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  $employers = new CJS_Daily_Employers($API);

  $list = $jobs->get_free_advert_by_id($id);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		  
		$organisation = $jobs->get_organisation($slug);

		$fields = PerchUtil::json_safe_decode($item['free_advertDynamicFields'], true);

		$fields['description'] = strip_tags($fields['description']['processed'],"<p><ul><li>");
		$item = array_merge($fields,$item);
	
		return $item;

	}
  }
}

function cjs_job_title($slug,$draft){
  $state = cjs_job_state($slug,$draft);
  return ($state['item']===null) ? '' : $state['item']['title'];
}

function cjs_job_description($slug,$draft){
  $state = cjs_job_state($slug,$draft);
  if ($state['item']===null) return '';

  $json = json_decode($state['item']['daily_jobDynamicFields'],true);
  $text = isset($json['text']['raw']) ? $json['text']['raw'] : $state['item']['advertText'];

  /* A meta description, so one tidy line rather than the advert's markup. */
  $text = strip_tags(str_replace(array('</p>','<br>','<br />','</li>'), ' ', $text));
  $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
  $text = trim(preg_replace('/\s+/', ' ', $text));

  if (mb_strlen($text) > 300) $text = rtrim(mb_substr($text, 0, 300)).'…';

  return $text;
}

function apps_ints(){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $jobs->get_app_ints();

}

function vol_apps_ints(){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $daily = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  
  $Template = $API->get('Template');
/*
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $daily->get_volunteer_adverts_sector($sector);

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
*/

  $Template->set('cjs_daily/job_adverts_volunteers.html', 'cjs');

  $list = $volunteers->get_volunteer_adverts_apps_ints();

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
  
}

function volunteer_additions(){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $daily = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  
  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts_volunteers.html', 'cjs');

  $list = $volunteers->get_volunteer_additions();

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
  
  $Template->set('cjs_daily/job_adverts.html', 'cjs');
  
  $list = $daily->get_volunteer_additions();

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
  
}

function volunteer_ad($id){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $daily = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);
  
  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts_volunteers.html', 'cjs');

  $list = $volunteers->get_advert_by_id($id);

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
  
/*
  $Template->set('cjs_daily/job_adverts.html', 'cjs');
  
  $list = $daily->get_volunteer_additions();

  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
	
  }
*/
  
}

function advertiser_messages(){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $advertiserMessages = new CJS_Daily_Advertiser_Messages($API);
  
  $Template = $API->get('Template');
  $Template->set('cjs_daily/advertiser_messages_public.html', 'cjs');
  
  $messages = $advertiserMessages->get_advertiser_messages();
  
  $fields = PerchUtil::json_safe_decode($messages[0]['advertiser_messageDynamicFields'], true);
		
  $item = array_merge($fields,$messages[0]);
  
  $weekago = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-7, date('Y')));
  if($item['date']>=$weekago){
	  $item['focusdate'] = '1';
  }else{
	  $item['focusdate'] = '0';
  }
	
  $html = $Template->render($item, true);
  $html = $Template->apply_runtime_post_processing($html, $item);  
  echo $html;
	
}

function advertiser_message_volunteers(){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $advertiserMessages = new CJS_Daily_Advertiser_Messages($API);
  
  $Template = $API->get('Template');
  $Template->set('cjs_daily/advertiser_messages_public_vol.html', 'cjs');
  
  $messages = $advertiserMessages->get_advertiser_messages();
  
  $fields = PerchUtil::json_safe_decode($messages[0]['advertiser_messageDynamicFields'], true);

  $item = array_merge($fields,$messages[0]);
	
  $html = $Template->render($item, true);
  $html = $Template->apply_runtime_post_processing($html, $item);  
  echo $html;
	
}

function advertiser_message_workdays(){
  $API = new PerchAPI(1.0, 'cjs_daily');
  $advertiserMessages = new CJS_Daily_Advertiser_Messages($API);
  
  $Template = $API->get('Template');
  $Template->set('cjs_daily/advertiser_messages_public_work.html', 'cjs');
  
  $messages = $advertiserMessages->get_advertiser_messages();
  
  $fields = PerchUtil::json_safe_decode($messages[0]['advertiser_messageDynamicFields'], true);

  $item = array_merge($fields,$messages[0]);
	
  $html = $Template->render($item, true);
  $html = $Template->apply_runtime_post_processing($html, $item);  
  echo $html;
	
}

function dailyTextSearch($data){
	
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  $volunteers = new CJS_Daily_Volunteers($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $jobs->dailyTextSearch($data);
  
  $ads = 0;
  
  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
		$item['newWindow'] = 1;
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
		$ads = 1;
		
	}
  }
  
  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts_volunteers.html', 'cjs');

  $list = $volunteers->dailyTextSearchVolunteers($data);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
		$ads = 1;
		
	}
  }
  
  if($ads==0){
	echo '<div class="listing"><p><strong>Sorry, no vacancies these criteria currently listed. <a href="/jobs/search">Go back and try again</a>.</strong></p></div>';
  }
	
}

function dailySearch($data){
	
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts.html', 'cjs');

  $list = $jobs->dailySearch($data);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
  }else{
	echo '<div class="listing"><p><strong>Sorry, no vacancies these criteria currently listed. <a href="/jobs/search">Go back and try again</a>.</strong></p></div>';
  }
	
}

function dailySearchVolunteers($data){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $volunteers = new CJS_Daily_Volunteers($API);

  $Template = $API->get('Template');
  $Template->set('cjs_daily/job_adverts_volunteers.html', 'cjs');

  $list = $volunteers->dailySearchVolunteers($data);
  
  if(count($list)>0){
  
	  foreach($list as $item){
		
		$fields = PerchUtil::json_safe_decode($item['volunteerDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
		
	}
  }else{
	echo '<div class="listing"><p><strong>Sorry, no vacancies these criteria currently listed. <a href="/jobs/search">Go back and try again</a>.</strong></p></div>';
  }
  
}

function cjs_member_daily_adverts($memberID){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$jobs = new CJS_Daily_Jobs($API);
	
	$Template = $API->get('Template');
	$Template->set('cjs_daily/list_adverts.html', 'cjs');
  
	$list = $jobs->memberJobs($memberID);
	$html = $Template->render_group($list, true);
	echo $html;
	
}

function cjs_member_volunteer_adverts($memberID){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$volunteers = new CJS_Daily_Volunteers($API);
	
	$Template = $API->get('Template');
	$Template->set('cjs_daily/list_vol_adverts.html', 'cjs');
  
	$list = $volunteers->memberJobs($memberID);
	$html = $Template->render_group($list, true);
	echo $html;
	
}

function cjs_member_free_adverts($memberID){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$jobs = new CJS_Daily_Jobs($API);
	
	$Template = $API->get('Template');
	$Template->set('cjs_daily/list_free_adverts.html', 'cjs');
  
	$list = $jobs->memberFreeJobs($memberID);
	$html = $Template->render_group($list, true);
	echo $html;
	
}

function perch_log_blog_view($slug){
  
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);
  
  $jobs->logBlogView($slug);
  
}

function blog_log(){
   $API  = new PerchAPI(1.0, 'cjs_daily');
   $jobs = new CJS_Daily_Jobs($API);
   
   
   $Template = $API->get('Template');
   $Template->set('blog/post_in_list_home_top.html', 'blog');
   
   $articles = $jobs->blog_log();
   $articles_formatted = array();
   foreach($articles as $article){
     $articles_formatted[] = array("slug" => $article['slug'], "title" => str_replace("-", " ", ucwords($article['slug'])));
   }
   $html = $Template->render_group($articles_formatted, true);
   echo $html;
}

function jobs_map(){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $jobs = new CJS_Daily_Jobs($API);

    echo '
      <div class="flow" id="map-container">
        <h2>Browse by Location</h2>
        <div class="map-grid">
          <div id="map" class="big-map"></div>
        </div>
        <dl class="map-key">
          <dt><img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png" alt="Marker" /></dt><dd>National or International Role</dd>
          <dt><img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png" alt="Marker" /></dt><dd>Locally Based Role</dd>
          <dt><img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png" alt="Marker" /></dt><dd>Home Based Role</dd>
        </dl>
        <p>Please note that not all job adverts appear on the map, only those for which a physical location has been defined by the advertiser.</p>
      </div>
      <a id="map-link" href="#map-container"><i class="fa fa-map-marker"></i> View Map</a>
      <script>
      
      let markerData = new Array;
      
      var y = 0;
      
      $(".listing").each(function(i, obj) {
          var latAttr = $(this).attr("data-lat");
          
          // Check if data-lat exists before trying to split
          if(latAttr){
            var lat = latAttr.split(",");
            
            if(lat.length>0){
              const markers = true;
              var lng = $(this).attr("data-lng").split(",");
              var title = $(this).data("title");
              var url = $(this).data("url");
              var location = $(this).data("location"); // Get the location data
              var arrayLength = lat.length;
              for (var i = 0; i < arrayLength; i++) {
                  markerData[y] = new Array(lat[i], lng[i], title, url, location);
                  y++;
              }
            }
          }
      });
      
      // Create the map
      var map = L.map("map").setView([54.5536467, -0.8851795], 5);
      
      // Add a tile layer
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
      }).addTo(map);
      
      // Define custom icons for national and local jobs
      var nationalIcon = L.icon({
        iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png",
        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });
      
      var homeIcon = L.icon({
        iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });
      
      var localIcon = L.icon({
        iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png",
        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });
      
      // Step 1: Group data by lat,lng
      var grouped = {};
      markerData.forEach(function(item) {
        if(item[0]){
          var key = item[0] + "," + item[1]; // lat,lng as key
          if (!grouped[key]) {
            grouped[key] = {
              items: [],
              isNational: false
            };
          }
          grouped[key].items.push({ name: item[2], url: item[3] });
          // If any item at this location is national, mark the location as national
          if (item[4] === "national" || item[4] === "international") {
            grouped[key].isNational = true;
          }
          if (item[4] === "home") {
            grouped[key].isHome = true;
          }
        }
      });
      
      // Function to extract the last word from a location string
      function getLocationType(locationString) {
        if (!locationString || locationString.trim() === "") {
          return "local"; // default
        }
        var words = locationString.trim().split(/\s+/); // split by whitespace
        var lastWord = words[words.length - 1].toLowerCase();
        return lastWord;
      }
      
      // Step 1: Group data by lat,lng
      var grouped = {};
      markerData.forEach(function(item) {
        if(item[0]){
          var key = item[0] + "," + item[1]; // lat,lng as key
          if (!grouped[key]) {
            grouped[key] = {
              items: [],
              locationType: "local" // default to local
            };
          }
          grouped[key].items.push({ name: item[2], url: item[3] });
          
          // Get the location type from the last word
          var currentLocationType = getLocationType(item[4]);
          
          // Set location type based on priority: national > home > local
          if (currentLocationType === "national") {
            grouped[key].locationType = "national";
          } else if (currentLocationType === "home" && grouped[key].locationType !== "national") {
            grouped[key].locationType = "home";
          } else {
            // Only update if we haven\'t set a more specific type yet
            grouped[key].locationType = currentLocationType;
          }
        }
      });
      
      // Step 2: Create markers from grouped data
      var markers = [];
      
      Object.keys(grouped).forEach(function(key) {
        var parts = key.split(",");
        var lat = parseFloat(parts[0]);
        var lng = parseFloat(parts[1]);
        var data = grouped[key];
        var items = data.items;
        var locationType = data.locationType;
        
        // Create popup HTML with all items for this location
        var popupContent = items.map(function(i) {
          return `<a href="${i.url}"><b>${i.name}</b></a>`;
        }).join("<br>");
      
        // Choose icon based on location type
        var icon = localIcon; // default
        if (locationType === "national") {
          icon = nationalIcon;
        } else if (locationType === "home") {
          icon = homeIcon;
        }
      
        var marker = L.marker([lat, lng], { icon: icon })
          .addTo(map)
          .bindPopup(popupContent);
          markers.push(marker);
      });
      
      if(markers.length==0){
        $("#map-container").remove();
        $("#map-link").remove();
      }else{
        // Step 3: Fit map to all markers
        var group = L.featureGroup(markers);
        map.fitBounds(group.getBounds(), { padding: [30, 30] });
      }
      
      
    </script>';
  
}