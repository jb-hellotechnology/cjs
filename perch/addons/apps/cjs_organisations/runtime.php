<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
    
include('CJS_Organisations.Org.class.php');
include('CJS_Organisations.Orgs.class.php');

function cjs_organisation_form_handler($SubmittedForm)
{

  $CJS_Organisations = new CJS_Organisations_Orgs;

  if ($SubmittedForm->validate()) {

    $API  = new PerchAPI(1.0, 'cjs_organisation');

    switch($SubmittedForm->formID) {

      case 'cjs_organisation':
      	$CJS_Organisations->submitOrganisation($SubmittedForm);
      break;

    }            

  }
}

function organisation($slug,$data){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $organisations = new CJS_Organisations_Orgs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_organisations/organisation_display.html', 'cjs');

  if($data){
	 
	 $organisation = $organisations->get_by_slug($slug);
	 return $organisation;
	  
  }else{

	  $organisation = $organisations->get_by_slug($slug);
	  
	  $fields = PerchUtil::json_safe_decode($organisation['organisationDynamicFields'], true);
	  $organisation = array_merge($fields,$organisation);
	
	  $html = $Template->render($organisation, true);
	  $html = $Template->apply_runtime_post_processing($html, $organisation);  
	
	  echo $html;
	  
  }

}

function organisation_testimonials($slug,$data){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $organisations = new CJS_Organisations_Orgs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_organisations/organisation_display_testimonials.html', 'cjs');
  
  $organisation = $organisations->get_by_slug($slug);
  
  $fields = PerchUtil::json_safe_decode($organisation['organisationDynamicFields'], true);
  $organisation = array_merge($fields,$organisation);

  $html = $Template->render($organisation, true);
  $html = $Template->apply_runtime_post_processing($html, $organisation);  

  echo $html;
}

function organisation_profile($slug,$data){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $organisations = new CJS_Organisations_Orgs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_organisations/organisation_display_full.html', 'cjs');

  if($data){
	 
	 $organisation = $organisations->get_by_slug($slug);
	 return $organisation;
	  
  }else{

	  $organisation = $organisations->get_by_slug($slug);
	  
	  $fields = PerchUtil::json_safe_decode($organisation['organisationDynamicFields'], true);
	  $organisation = array_merge($fields,$organisation);
	
	  $html = $Template->render($organisation, true);
	  $html = $Template->apply_runtime_post_processing($html, $organisation);  
	
	  echo $html;
	  
  }

}

function organisation_title($slug){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $organisations = new CJS_Organisations_Orgs($API);

  $organisation = $organisations->get_by_slug($slug);
  
  return $organisation;

}

function organisation_id($id,$data){

  $API  = new PerchAPI(1.0, 'cjs_daily');
  $organisations = new CJS_Organisations_Orgs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_organisations/organisation_display.html', 'cjs');

  $organisation = $organisations->get_by_id($id);

  if($data){
  	return $organisation;
  }else{
	  $html = $Template->render($organisation, true);
	  $html = $Template->apply_runtime_post_processing($html, $organisation);  
	
	  echo $html;
  }

}

function list_advertisers_all(){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $organisations = new CJS_Organisations_Orgs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_organisations/advertisers_organisations.html', 'cjs');

  $list = $organisations->all_active();

  $html = $Template->render_group($list, true);
  $html = $Template->apply_runtime_post_processing($html, $list);  

  echo $html;
}

function list_advertisers_jobs_vols(){
  $API  = new PerchAPI(1.0, 'cjs_daily');
  $organisations = new CJS_Organisations_Orgs($API);

  $Template = $API->get('Template');
  $Template->set('cjs_organisations/advertisers_employers.html', 'cjs');

  $list = $organisations->all_active_jobs_vols();

  $html = $Template->render_group($list, true);
  $html = $Template->apply_runtime_post_processing($html, $list);  

  echo $html;
}

function employer_link($slug){
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$organisations = new CJS_Organisations_Orgs($API);
	$jobs = new CJS_Daily_Jobs($API);
	
	$organisation = $jobs->get_organisation($slug);
	
	echo '<p><a href="/jobs/employer/'.$organisation['slug'].'">See everything from this employer</a>.</p>';
	
}

function employer_name($slug){
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$organisations = new CJS_Organisations_Orgs($API);
	$jobs = new CJS_Daily_Jobs($API);
	
	$organisation = $jobs->get_organisation($slug);
	
	return $organisation['name'];
	
}

function organisation_t($id){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$organisations = new CJS_Organisations_Orgs($API);

	$org_t = $organisations->get_master_t($id);
	$organisation = $organisations->get_by_id($org_t['master_organisationID']);
	
	return $organisation;
	
}

function organisation_list(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$organisations = new CJS_Organisations_Orgs($API);
	
	$organisations->org_list();
}

function organisation_list_full(){
	
	$API  = new PerchAPI(1.0, 'cjs_daily');
	$organisations = new CJS_Organisations_Orgs($API);
	
	$organisations = $organisations->org_list_full();
	return $organisations;
}
