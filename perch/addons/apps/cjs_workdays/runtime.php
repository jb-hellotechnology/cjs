<?php

    /*
        This file includes the code called by the site pages at runtime.
        If you app is admin-only then don't include this file.
        
        Remember - try and be as lightweight at runtime as possble. Include only 
        what you need to, run only 100% necessary code. Make every database query
        count.
    */

    # Include your class files as needed - up to you.
    
    include('CJS_Workdays.Organisation.class.php');
    include('CJS_Workdays.Organisations.class.php');
    include('CJS_Workdays.Survey.class.php');
    include('CJS_Workdays.Surveys.class.php');
    include('CJS_Workdays.Vol_Org.class.php');
    include('CJS_Workdays.Vol_Orgs.class.php');
    include('CJS_Workdays.Work_Day.class.php');
    include('CJS_Workdays.Work_Days.class.php');
    include('CJS_Workdays.Search.class.php');
    
    PerchSystem::register_search_handler('CJS_Workdays_Survey_SearchHandler');

    function cjs_workdays_form_handler($SubmittedForm)
    {

      $CJS_Workdays = new CJS_Workdays_Work_Days;

      if ($SubmittedForm->validate()) {

        $API  = new PerchAPI(1.0, 'cjs_workdays');

        switch($SubmittedForm->formID) {

          case 'cjs_volunteerorganisation':
          $CJS_Workdays->submitVolunteerOrganisation($SubmittedForm);
          break;
            
          case 'cjs_survey':
          $CJS_Workdays->submitSurvey($SubmittedForm);
          break;

        }            

      }
    }

    function volunteers_organisations($region){
      $API  = new PerchAPI(1.0, 'cjs_workdays');
      $volOrgs = new CJS_Workdays_Vol_Orgs($API);

      $Template = $API->get('Template');
      $Template->set('cjs_workdays/display_vol_orgs.html', 'cjs');
      
      $object = $volOrgs->all();
      $list = $volOrgs->get_vol_orgs($region,$object);
      
      $html = $Template->render_group($list, true);
      $html = $Template->apply_runtime_post_processing($html, $list);  

      echo $html;        
      

    }

    function volunteers_workdays($region){
      $API = new PerchAPI(1.0, 'cjs_workdays');
      $VolOrgs= new CJS_Workdays_Vol_Orgs($API);

      $Template = $API->get('Template');
      $Template->set('cjs_workdays/display_vol_orgs.html', 'cjs');

      $list = $VolOrgs->vorgs_by_region($region);
      
      foreach($list as $item){
	    
	    $fields = array();
	    $fields = json_decode($item['vorganisationDynamicFields'],true);
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
	      
      }
    }
    
    function volunteers_vol_organisations($region){
      $API = new PerchAPI(1.0, 'cjs_workdays');
      $VolOrgs= new CJS_Workdays_Vol_Orgs($API);

      $Template = $API->get('Template');
      $Template->set('cjs_workdays/display_vol_orgs_2.html', 'cjs');

      $list = $VolOrgs->vorgs_by_region($region);
      
      foreach($list as $item){
	      
	    $fields = PerchUtil::json_safe_decode($item['vorganisationDynamicFields'], true);
	    
	    if(is_array($fields)){
	    
			$item = array_merge($fields,$item);
		
		}
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
	      
      }
    }

    function volunteers_surveys($sector){
      $API = new PerchAPI(1.0, 'cjs_workdays');
      $surveys = new CJS_Workdays_Surveys($API);

      $Template = $API->get('Template');
      $Template->set('cjs_workdays/display_surveys.html', 'cjs');
      
      $list = $surveys->surveys_by_sector($sector);
      
      foreach($list as $item){
	      
	    $fields = PerchUtil::json_safe_decode($item['surveyDynamicFields'], true);
	    if(is_array($fields)){
			$item = array_merge($fields,$item);
		}
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
	      
      }
    }
    
    function volunteers_surveys_all(){
      $API = new PerchAPI(1.0, 'cjs_workdays');
      $surveys = new CJS_Workdays_Surveys($API);

      $Template = $API->get('Template');
      $Template->set('cjs_workdays/display_surveys.html', 'cjs');
      
      $list = $surveys->surveys_by_date();
      
      foreach($list as $item){
	      
	    $fields = PerchUtil::json_safe_decode($item['surveyDynamicFields'], true);
	    if(is_array($fields)){
			$item = array_merge($fields,$item);
		}
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
	      
      }
    }
    
    function volunteers_vol_organisations_search($region,$type){
      $API = new PerchAPI(1.0, 'cjs_workdays');
      $VolOrgs= new CJS_Workdays_Vol_Orgs($API);

      $Template = $API->get('Template');
      $Template->set('cjs_workdays/display_vol_orgs_2.html', 'cjs');

      $list = $VolOrgs->vorgs_by_region($region);
      
      $results = 0;
      
      foreach($list as $item){
	      
	    $fields = PerchUtil::json_safe_decode($item['vorganisationDynamicFields'], true);
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		
		if(($item['cVolPlace']==1 AND $type=='Placements') OR ($item['cVolOpps']==1 AND $type=='Occasional Requirement') OR ($item['cWorkDays']==1 AND $type=='Work Days') OR ($item['cSurveys']==1 AND $type=='Citezen Science')){
		
			echo $html;
			
			$results = 1;
		
		}
	      
      }
      
      if($results==0){
	      echo "<div class='container margin-top'><p>No results. <a href='/volunteers/volunteering-organisations#form2_form'>Go back and try again</a></p></div>";
      }else{
	      echo "<div class='container margin-top'><p><a href='/volunteers/volunteering-organisations#form2_form'>Want to change region or type of volunteering? Go back and try again</a></p></div>";
      }
    }


    

