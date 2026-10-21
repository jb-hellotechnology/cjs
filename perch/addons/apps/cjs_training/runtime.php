<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

    /*
        This file includes the code called by the site pages at runtime.
        If you app is admin-only then don't include this file.
        
        Remember - try and be as lightweight at runtime as possble. Include only 
        what you need to, run only 100% necessary code. Make every database query
        count.
    */

    # Include your class files as needed - up to you.
    
//    include('simple_tickets.class.php');
    include('CJS_Training.Event.class.php');
    include('CJS_Training.Events.class.php');
    include('CJS_Training.Long_Course.class.php');
    include('CJS_Training.Long_Courses.class.php');
    include('CJS_Training.Organisation.class.php');
    include('CJS_Training.Organisations.class.php');
    include('CJS_Training.Provider.class.php');
    include('CJS_Training.Providers.class.php');
    include('CJS_Training.Short_Course.class.php');
    include('CJS_Training.Short_Courses.class.php');

    function cjs_training_form_handler($SubmittedForm)
    {

      $CJS_Training_Short = new CJS_Training_Short_Courses;
      $CJS_Training_Long = new CJS_Training_Long_Courses;
      $CJS_Training_Events = new CJS_Training_Events;
      $CJS_Training_Centres_Providers = new CJS_Training_Providers;

      if ($SubmittedForm->validate()) {

        $API  = new PerchAPI(1.0, 'cjs_training');

        switch($SubmittedForm->formID) {

          case 'cjs_shortcourse_event':
          $CJS_Training_Short->submitShortCourseEventAdvert($SubmittedForm);
          break;
            
          case 'cjs_longcourse':
          $CJS_Training_Long->submitLongCourseAdvert($SubmittedForm);
          break;

        }            

      }
    }

    function short_courses($category){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $shortCourses = new CJS_Training_Short_Courses($API);

      $Template = $API->get('Template');
      $Template->set('cjs_training/display_short_courses.html', 'cjs');

      $list = $shortCourses->short_courses($category,'dated');

      foreach($list as $item){
	      
	    $fields = PerchUtil::json_safe_decode($item['shortDynamicFields'], true);
		
		if(is_array($fields)){
			$item = array_merge($fields,$item);
		}
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
	      
      }
      
      $list = $shortCourses->short_courses($category,'undated');

      foreach($list as $item){
	      
	    $fields = PerchUtil::json_safe_decode($item['shortDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
	      
      }
    }
    
    function long_courses($sector){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $longCourses = new CJS_Training_Long_Courses($API);

      $Template = $API->get('Template');
      $Template->set('cjs_training/display_long_courses.html', 'cjs');
      
      $list = $longCourses->long_courses($sector);
      
      foreach($list as $item){
	      
        $fields = PerchUtil::json_safe_decode($item['longDynamicFields'], true);

        $item = array_merge($fields,$item);

        $html = $Template->render($item, true);
        $html = $Template->apply_runtime_post_processing($html, $item);  
        echo $html;
	      
      }

    }
    
    function long_courses_organisation($organisation){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $longCourses = new CJS_Training_Long_Courses($API);
      $organisations = new CJS_Organisations_Orgs($API);
      
      $organisationData = $organisations->get_by_slug($organisation);
      
      $offices = $longCourses->get_offices($organisationData['organisationID']);
      
      $banner = 0;
      
      foreach($offices as $office){
	      
	      $list = $longCourses->by_organisation($office['organisationID']);
	      
	      $i = 0;
	      foreach($list as $item){
		      if($item['categoryOne']=='agriculture'){$list[$i]['sector']='agriculture';}
		      if($item['categoryOne']=='arboriculture'){$list[$i]['sector']='arboriculture';}
		      if($item['categoryOne']=='botany'){$list[$i]['sector']='botany-and-horticulture';}
		      if($item['categoryOne']=='ecology'){$list[$i]['sector']='ecology';}
		      if($item['categoryOne']=='enved'){$list[$i]['sector']='environmental-education';}
		      if($item['categoryOne']=='land'){$list[$i]['sector']='land-and-countryside-management';}
		      if($item['categoryOne']=='marine'){$list[$i]['sector']='marine-and-coastal';}
		      if($item['categoryOne']=='craft'){$list[$i]['sector']='hobby-and-craft';}
		      $i++;
	      }
	      
	      if(count($list)>0 AND $banner==0){
		      echo "<div class=\"container margin-top\"><h3 class=\"no-margin\">Long Courses</h3></div>";
		      $banner = 1;
	      }
	      
	      $Template = $API->get('Template');
	      $Template->set('cjs_training/display_long_courses-simple.html', 'cjs');
	
	      $html = $Template->render_group($list, true);
	      $html = $Template->apply_runtime_post_processing($html, $list);
	      
	      echo $html;
      
      }
    }
    
    function short_courses_organisation($organisation){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $shortCourses = new CJS_Training_Short_Courses($API);
      $organisations = new CJS_Organisations_Orgs($API);
      
      $organisationData = $organisations->get_by_slug($organisation);
      
      $offices = $shortCourses->get_offices($organisationData['organisationID']);

	  $banner = 0;

      foreach($offices as $office){
	      
	      $list = $shortCourses->by_organisation($office['organisationID']);
	      
	      $i = 0;
	      foreach($list as $item){
		      if($item['categoryOne']=='row'){$list[$i]['sector']='access-and-rights-of-way';}
		      if($item['categoryOne']=='ad'){$list[$i]['sector']='administrative-and-office-skills';}
		      if($item['categoryOne']=='ed'){$list[$i]['sector']='community-engagement-and-environmental-education';}
		      if($item['categoryOne']=='craft'){$list[$i]['sector']='country-craft-courses';}
		      if($item['categoryOne']=='cons'){$list[$i]['sector']='countryside-management-techniques';}
		      if($item['categoryOne']=='1aid'){$list[$i]['sector']='first-aid-risk-assessment-and-other-health-safety-related-courses';}
		      if($item['categoryOne']=='hort'){$list[$i]['sector']='horticulture-and-small-holding';}
		      if($item['categoryOne']=='invert'){$list[$i]['sector']='identification-and-field-survey-skills-herpetology-fish-and-invertebrates';}
		      if($item['categoryOne']=='mammals'){$list[$i]['sector']='identification-and-field-survey-skills-mammals';}
		      if($item['categoryOne']=='birds'){$list[$i]['sector']='identification-and-field-survey-skills-ornithology';}
		      if($item['categoryOne']=='plants'){$list[$i]['sector']='identification-and-field-survey-skills-plants-and-habitats';}
		      if($item['categoryOne']=='photo'){$list[$i]['sector']='photography';}
		      if($item['categoryOne']=='skills'){$list[$i]['sector']='practical-countryside-skills';}
		      if($item['categoryOne']=='mach'){$list[$i]['sector']='practical-countryside-skills-machinery';}
		      $i++;
	      }
	      
	      if(count($list)>0 AND $banner==0){
		      echo "<div class=\"container margin-top\"><h3 class=\"no-margin\">Short Courses</h3></div>";
		      $banner = 1;
	      }
	      
	      $Template = $API->get('Template');
	      $Template->set('cjs_training/display_short_courses-blue.html', 'cjs');
	
	      $html = $Template->render_group($list, true);
	      $html = $Template->apply_runtime_post_processing($html, $list);
	      
	      echo $html;
      
      }
    }
    
    function events_organisation($organisation){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $shortCourses = new CJS_Training_Events($API);
      $organisations = new CJS_Organisations_Orgs($API);
      
      $organisationData = $organisations->get_by_slug($organisation);
      
      $offices = $shortCourses->get_offices($organisationData['organisationID']);

	  $banner = 0;

      foreach($offices as $office){
	      
	      $list = $shortCourses->by_organisation($office['organisationID']);
	      
	      if(count($list)>0 AND $banner==0){
		      echo "<div class=\"container margin-top\"><h3 class=\"no-margin\">Events</h3></div>";
		      $banner = 1;
	      }
	      
	      $Template = $API->get('Template');
	      $Template->set('cjs_training/display_events-blue.html', 'cjs');
	
	      $html = $Template->render_group($list, true);
	      $html = $Template->apply_runtime_post_processing($html, $list);
	      
	      echo $html;
      
      }
    }
    
    function long_courses_provider($id){
		$API  = new PerchAPI(1.0, 'cjs_training');
		$longCourses = new CJS_Training_Long_Courses($API);
		$organisations_t = new CJS_Training_Organisations($API);
		$organisations = new CJS_Organisations_Orgs($API);
		$providers = new CJS_Training_Providers($API);
		
		$organisationsT = $organisations_t->get_by_organisation($id);
		foreach($organisationsT AS $org){
			
			$list = $longCourses->by_organisation($org['organisationID']);
		      
			if(count($list)>0){
			  echo "<div class='textarea flow'><h3>Long Courses</h3></div>";
			  $banner = 1;
			}
			
			echo '<div class="training-columns">';
			
			$Template = $API->get('Template');
			$Template->set('cjs_training/display_long_courses.html', 'cjs');
			
			foreach($list as $item){
		      
			    $fields = PerchUtil::json_safe_decode($item['longDynamicFields'], true);
				
				$item = array_merge($fields,$item);
					
				$html = $Template->render($item, true);
				$html = $Template->apply_runtime_post_processing($html, $item);  
				echo $html;
			      
			  }
			  
			echo '</div>';
		  
		}
    }
    
    function short_courses_provider($id){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $shortCourses = new CJS_Training_Short_Courses($API);
      $organisations = new CJS_Organisations_Orgs($API);
      $organisations_t = new CJS_Training_Organisations($API);
	  
	  $organisationsT = $organisations_t->get_by_organisation($id);
		foreach($organisationsT AS $org){
	      $list = $shortCourses->by_organisation($org['organisationID']);
	      
	      if(count($list)>0){
			echo "<div class='textarea flow'><h3>Short Courses</h3></div>";
			$banner = 1;
		  }
		  
		  echo '<div class="training-columns">';
	      
	      $Template = $API->get('Template');
	      $Template->set('cjs_training/display_short_courses.html', 'cjs');
	
	
		  foreach($list as $item){
	      
		    $fields = PerchUtil::json_safe_decode($item['shortDynamicFields'], true);
			
			if(is_array($fields)){
				$item = array_merge($fields,$item);
			}
			$html = $Template->render($item, true);
			$html = $Template->apply_runtime_post_processing($html, $item);  
			echo $html;
		      
		  }
		  
		  echo '</div>';
		}
     
    }
    
    function events_provider($id){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $shortCourses = new CJS_Training_Events($API);
      $organisations = new CJS_Organisations_Orgs($API);
      $organisations_t = new CJS_Training_Organisations($API);

	  $organisationsT = $organisations_t->get_by_organisation($id);
		foreach($organisationsT AS $org){
	      $list = $shortCourses->by_organisation($org['organisationID']);
	      
	      if(count($list)>0){
			  echo "<div class='textarea flow'><h3>Events</h3></div>";
			  $banner = 1;
			}
	      
	      $Template = $API->get('Template');
	      $Template->set('cjs_training/display_events.html', 'cjs');
		  
		  echo '<div class="training-columns">';
	
	      foreach($list as $item){
	      
		    $fields = PerchUtil::json_safe_decode($item['eventDynamicFields'], true);
			
			$item = array_merge($fields,$item);
				
			$html = $Template->render($item, true);
			$html = $Template->apply_runtime_post_processing($html, $item);  
			echo $html;
		      
		  }
		  
		  echo '</div>';
      
      }
      
    }
    
    function get_provider($id){
	    
	  $API  = new PerchAPI(1.0, 'cjs_training');
      $providers = new CJS_Training_Providers($API);
      
      $provider = $providers->get_provider($id);

		return $provider;	    
    }

    function get_events(){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $events = new CJS_Training_Events($API);

      $Template = $API->get('Template');
      $Template->set('cjs_training/display_events.html', 'cjs');
      
      $list = $events->get_all_events();
      
      foreach($list as $item){
	      
	    $fields = PerchUtil::json_safe_decode($item['eventDynamicFields'], true);
		
		$item = array_merge($fields,$item);
			
		$html = $Template->render($item, true);
		$html = $Template->apply_runtime_post_processing($html, $item);  
		echo $html;
	      
      }
    }
    
    function webinars($section){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $events = new CJS_Training_Events($API);
      $shortCourses = new CJS_Training_Short_Courses($API);
      $longCourses = new CJS_Training_Long_Courses($API);

      $Template = $API->get('Template');

      if($section=='webinars'){
	      $Template->set('cjs_training/display_webinars.html', 'cjs');
	      
	      $list = $events->events_webinars();

	      foreach($list as $item){
		      
		    $fields = PerchUtil::json_safe_decode($item['shortDynamicFields'], true);
			
			$item = array_merge($fields,$item);
				
			$html = $Template->render($item, true);
			$html = $Template->apply_runtime_post_processing($html, $item);  
			echo $html;
		      
	      }
      }
      if($section=='short-courses'){
	      $Template->set('cjs_training/display_short_courses.html', 'cjs');
	      
	      $list = $shortCourses->short_webinars();
	      
	      foreach($list as $item){
		      
		    $fields = PerchUtil::json_safe_decode($item['shortDynamicFields'], true);
			
			$item = array_merge($fields,$item);
				
			$html = $Template->render($item, true);
			$html = $Template->apply_runtime_post_processing($html, $item);  
			echo $html;
		      
	      }
      }
      if($section=='long-courses'){
	      $Template->set('cjs_training/display_long_courses.html', 'cjs');
	      
	      $list = $longCourses->long_webinars();
	      
	      foreach($list as $item){
		      
		    $fields = PerchUtil::json_safe_decode($item['longDynamicFields'], true);
			
			$item = array_merge($fields,$item);
				
			$html = $Template->render($item, true);
			$html = $Template->apply_runtime_post_processing($html, $item);  
			echo $html;
		      
	      }
      }
    }

    function get_centres_providers(){
      $API  = new PerchAPI(1.0, 'cjs_training');
      $providers = new CJS_Training_Providers($API);

      $Template = $API->get('Template');
      $Template->set('cjs_training/display_providers.html', 'cjs');
      
      $html = '';
      $links = '';
      
      $alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
      foreach($alphabet as $letter){
        
        $list = $providers->all_sort_name($letter);
        if(count($list)>0){
          $html .= "<div class=\"textarea flow\"><h3 id=\"".ucwords($letter)."\" class=\"no-margin\">".ucwords($letter).":</h3></div>";
		  $html .= '<div class="textarea flow">';
          $links .= "<a href=\"#".ucwords($letter)."\">".ucwords($letter)."</a> ";
          
          foreach($list as $item){
	      
		    $fields = PerchUtil::json_safe_decode($item['providerDynamicFields'], true);
			if(is_array($fields)){
				$item = array_merge($fields,$item);
			}
			$thisHtml = $Template->render($item, true);
			$thisHtml = $Template->apply_runtime_post_processing($thisHtml, $item);  
			
			$html.=$thisHtml;
		      
	      }
		  
		  $html .= '</div>';
        }
      
      }
      echo '<div class="container"><h3>'.$links.'</h3></div>';
      echo $html;      
    }

    function show_list(){
      echo '<p><a href="/training/short-courses">Short Courses</a>: professional training events of one to ten days in length on all subjects from NVC to ranger training, community involvement to how to build your own wind turbine. Inlcudes details of work day parties.</p>
            <p><a href="/training/long-courses">Long courses</a>: as short courses but of longer duration. This section includes adverts for MSc and degree courses also day release or for courses which are run intermittently eg one day per week for three weeks.</p>
            <p><a href="/training/distance-learning">Distance Learning</a>: Sometimes it is just not possible or practical to commit to a longer course, distance learning can provide the answer by allowing you to study in your own time at home.</p>
            <p><a href="/training/events">Events</a>: these are professional events which are not necessarily training focused such as an annual conference or fayre.</p>
            <p><a href="/training/centres-and-providers">Training Centres and Providers</a>: contact details for training and event providers.</p>';
    }

    function show_list_short_courses(){

      echo '
            <p><a href="/training/short-courses/row">Access and Rights of Way</a>: Typical courses range from map interpretation to countryside furniture building (stiles, gates and signs). Also includes navigation courses, eg Mountain leader.</p>
            <p><a href="/training/short-courses/ad">Administrative and Office Skills</a>: This includes areas such as software training, eg GIS; guide to Planning; contract management skills; budgeting, wildlife legislation, fundraising techniques but not basic skills training (literacy and numeracy etc.).</p>
            <p><a href="/training/short-courses/ed">Community Engagement and Environmental Education</a>: That\'s being trained in how to deliver community events and environmental education not the actual events themselves, also volunteer management.</p>
            <p><a href="/training/short-courses/craft">Country Craft Courses</a>: This section covers craft courses which can be anything using traditional skills.</p>
            <p><a href="/training/short-courses/cons">Countryside Management Techniques</a>: The theory behind the use of the technique, see the Practical Skills pages for courses in how to apply these techniques.</p>
            <p><a href="/training/short-courses/laid">First Aid, Risk Assessment and other Health & Safety Related Courses</a>: </p>
            <p><a href="/training/short-courses/hort">Horticulture and Small Holding</a>: Practical and theoretical training in gardening, permaculture and small holding.</p>
            <p><a href="/training/short-courses/invert">Identification and Field Survey Skills - Herpetology, Fish and Invertebrates</a>: Practical training in how to identify, classify and survey reptiles amphibians, fish and invertebrates. This is sort of how to ID everything else not considered above!</p>
            <p><a href="/training/short-courses/mammals">Identification and Field Survey Sills - Mammals</a>: Practical training in how to find and identify mammals, also training in survey techniques and classifications.</p>
            <p><a href="/training/short-courses/birds">Identification and Field Survey Sills - Ornithology</a>: Practical training in how to identify and handle birds, also training in survey techniques and classifications including ringing licences.</p>
            <p><a href="/training/short-courses/plants">Identification and Field Survey Sills - Plants and Habitats</a>: Practical training in how to identify higher and lower orders of plants, how to classify vegetation groups, also survey techniques. This page details courses where a full habitat is considered.</p>
            <p><a href="/training/short-courses/photo">Photography</a>: Practical training and theoretical workshops on digital photography and image manipulation. With an emphasis on landscape photography and the use of photography as a tool in identification.</p>
            <p><a href="/training/short-courses/skills">Practical Countryside Skills</a>: Training in the practical aspects of countryside management techniques such as dry stone walling and hedge laying.</p>
            <p><a href="/training/short-courses/mach">Practical Countryside Skills - Machinery</a>: Courses on the use and maintenance of machinery and chemicals (e.g. pesticides), includes driving courses.</p>
           ';
    }

    function show_list_long_courses(){

      echo '
            <p><a href="/training/long-courses/agriculture">Agriculture</a>: farming and those related to the agricultural industry.</p>
            <p><a href="/training/long-courses/arboriculture">Arboriculture</a>: tree care and management</p>
            <p><a href="/training/long-courses/botany">Botany and Horticulture</a>: covers everything from practical vegetable gardening to research into crop production.</p>
            <p><a href="/training/long-courses/ecology">Ecology</a>: Includes biodiversity conservation and ecological consultancy, also covers courses such as Evolutionary Biology and behavioural ecology.</p>
            <p><a href="/training/long-courses/enved">Environmental Education</a>: become a trained leader within environmental education.</p>
            <p><a href="/training/long-courses/land">Land and Countryside Management</a>: for the theory behind the techniques, includes work based practical conservation and landscape management.</p>
            <p><a href="/training/long-courses/marine">Marine and Coastal</a>: including offshore renewable energy to marine environmental management.</p>
            <p><a href="/training/long-courses/craft">Hobby and Craft</a>: Not really professional qualifications; this section covers craft courses which can be anything of interest and use in a countryside career or using traditional skills. This section includes photography.</p>
           ';
      
    }

// 	function searchTraining($data){
		
// 		$API  = new PerchAPI(1.0, 'cjs_training');
// 		$short = new CJS_Training_Short_Courses($API);
		
// 		$Template = $API->get('Template');
// 		$Template->set('cjs_daily/job_adverts.html', 'cjs');
		
// 		$list = $short->searchTraining($data);
		
// 		if(count($list)>0){
		
// 			foreach($list as $item){
				
// 				$fields = PerchUtil::json_safe_decode($item['daily_jobDynamicFields'], true);
				
// 				$item = array_merge($fields,$item);
					
// 				$html = $Template->render($item, true);
// 				$html = $Template->apply_runtime_post_processing($html, $item);  
// 				echo $html;
			
// 			}
			
// 		}else{
// 			echo '<div class="listing green"><p><strong>Sorry, no courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
// 		}
		
// 	}
    
  function searchLongCourse($data){
	    
	  $API  = new PerchAPI(1.0, 'cjs_training');
		$long = new CJS_Training_Long_Courses($API);
		
		$Template = $API->get('Template');
		$Template->set('cjs_training/display_long_courses.html', 'cjs');
		
		$list = $long->searchLongCourse($data);
		
		if(count($list)>0){
		
			foreach($list as $item){
				
				$fields = PerchUtil::json_safe_decode($item['longDynamicFields'], true);
				
				$item = array_merge($fields,$item);
					
				$html = $Template->render($item, true);
				$html = $Template->apply_runtime_post_processing($html, $item);  
				echo $html;
			
			}
			
		}else{
			echo '<div class="listing green"><p><strong>Sorry, no courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
		}
		
	}
    
  function searchShortCourse($data){
	    
	  $API  = new PerchAPI(1.0, 'cjs_training');
		$long = new CJS_Training_Short_Courses($API);
		
		$Template = $API->get('Template');
		$Template->set('cjs_training/display_short_courses.html', 'cjs');
		
		$list = $long->searchShortCourse($data);
		
		if(count($list)>0){
		
			foreach($list as $item){
				
				$fields = PerchUtil::json_safe_decode($item['shortDynamicFields'], true);
				
				$item = array_merge($fields,$item);
					
				$html = $Template->render($item, true);
				$html = $Template->apply_runtime_post_processing($html, $item);  
				echo $html;
			
			}
			
		}else{
			echo '<div class="listing green"><p><strong>Sorry, no courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
		}
		
	}
    
  function searchDistance($data){
    
    $API  = new PerchAPI(1.0, 'cjs_training');
		$long = new CJS_Training_Long_Courses($API);
		
		$Template = $API->get('Template');
		$Template->set('cjs_training/display_long_courses.html', 'cjs');
		
		$list = $long->searchDistance($data);
		
		if(count($list)>0){
		
			foreach($list as $item){
				
				$fields = PerchUtil::json_safe_decode($item['longDynamicFields'], true);
				
				$item = array_merge($fields,$item);
					
				$html = $Template->render($item, true);
				$html = $Template->apply_runtime_post_processing($html, $item);  
				echo $html;
			
			}
			
		}else{
			echo '<div class="listing green"><p><strong>Sorry, no courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
		}
		
    }
    
  function searchCentresProviders($data){
	    
	  $API  = new PerchAPI(1.0, 'cjs_training');
		$providers = new CJS_Training_Providers($API);
		
		$Template = $API->get('Template');
		$Template->set('cjs_training/display_providers.html', 'cjs');
		
		$list = $providers->searchCentresProviders($data);
		
		if(count($list)>0){
		
			foreach($list as $item){
				
				$fields = PerchUtil::json_safe_decode($item['providerDynamicFields'], true);
				
				$item = array_merge($fields,$item);
					
				$html = $Template->render($item, true);
				$html = $Template->apply_runtime_post_processing($html, $item);  
				echo $html;
			
			}
			
		}else{
			echo '<div class="listing green"><p><strong>Sorry, no courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
		}
	    
    }
    
  function searchEvents($data){
	    
	  $API  = new PerchAPI(1.0, 'cjs_training');
		$events = new CJS_Training_Events($API);
		
		$Template = $API->get('Template');
		$Template->set('cjs_training/display_events.html', 'cjs');
		
		$list = $events->searchEvents($data);
		
		if(count($list)>0){
		
			foreach($list as $item){
				
				$fields = PerchUtil::json_safe_decode($item['eventDynamicFields'], true);
				
				$item = array_merge($fields,$item);
					
				$html = $Template->render($item, true);
				$html = $Template->apply_runtime_post_processing($html, $item);  
				echo $html;
			
			}
			
		}else{
			echo '<div class="listing green"><p><strong>Sorry, no events matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
		}
		
    }
    
  function searchTrainingTerm($data){
// 	  print_r($data);
	  $API  = new PerchAPI(1.0, 'cjs_training');
		$short = new CJS_Training_Short_Courses($API);
		$long = new CJS_Training_Long_Courses($API);
		$providers = new CJS_Training_Providers($API);
    $events = new CJS_Training_Events($API);
		
		$Template = $API->get('Template');

		if($data['term1']){
			
			$list = $short->searchShortText($data);
			
			$Template->set('cjs_training/display_short_courses.html', 'cjs');
			
			if(count($list)>0){
		
				foreach($list as $item){
					
					$fields = PerchUtil::json_safe_decode($item['shortDynamicFields'], true);
					
					$item = array_merge($fields,$item);
						
					$html = $Template->render($item, true);
					$html = $Template->apply_runtime_post_processing($html, $item);  
					echo $html;
				
				}
				
			}else{
				echo '<div class="listing green"><p><strong>Sorry, no short courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
			}
		
			
		}
    
    if($data['term2'] OR $data['term3']){
			if ($data['term2']){
  			$list = $long->searchLongText($data);        
      }else{
        $list = $long->searchDistanceText($data);
      }

			
			$Template->set('cjs_training/display_long_courses.html', 'cjs');
			
			if(count($list)>0){
		
				foreach($list as $item){
					
					$fields = PerchUtil::json_safe_decode($item['longDynamicFields'], true);
					
					$item = array_merge($fields,$item);
						
					$html = $Template->render($item, true);
					$html = $Template->apply_runtime_post_processing($html, $item);  
					echo $html;
				
				}
				
			}else{
        if($data['term2']){
   				echo '<div class="listing green"><p><strong>Sorry, no long courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>'; 
        }else{
          echo '<div class="listing green"><p><strong>Sorry, no distance courses matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
        }
			}
			
		}
    
    if($data['term4'] == 1){

			$list = $providers->searchCentresProvidersText($data);
			
			$Template->set('cjs_training/display_providers.html', 'cjs');
			
			if(count($list)>0){
		
				foreach($list as $item){
					
					$fields = PerchUtil::json_safe_decode($item['providerDynamicFields'], true);
					
					$item = array_merge($fields,$item);
						
					$html = $Template->render($item, true);
					$html = $Template->apply_runtime_post_processing($html, $item);  
					echo $html;
				
				}
				
			}else{
				echo '<div class="listing green"><p><strong>Sorry, no centres and providers matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
			}
			
		}
    
    if($data['term5'] == 1){
			
			$list = $events->searchEventsText($data);
			
			$Template->set('cjs_training/display_events.html', 'cjs');
			
			if(count($list)>0){
		
				foreach($list as $item){
					
					$fields = PerchUtil::json_safe_decode($item['eventDynamicFields'], true);
					
					$item = array_merge($fields,$item);
						
					$html = $Template->render($item, true);
					$html = $Template->apply_runtime_post_processing($html, $item);  
					echo $html;
				
				}
				
			}else{
				echo '<div class="listing green"><p><strong>Sorry, no events matching these criteria currently listed. <a href="/training/search">Go back and try again</a>.</strong></p></div>';
			}
			
		}

    }
    
    
  function cjs_training_feed(){

	  $API  = new PerchAPI(1.0, 'cjs_training');
	  $short = new CJS_Training_Short_Courses($API);
	  $long = new CJS_Training_Long_Courses($API);
	  $providers = new CJS_Training_Providers($API);
	  $events = new CJS_Training_Events($API);
	
	  $Template = $API->get('Template');
	  $Template->set('cjs_training/training_feed.html', 'cjs');
	
	  $shortList = $short->published();
	  $longList = $long->published();
	  $eventsList = $events->get_all_events();
	  
	  foreach($longList as $long){
		  $long['startDate'] = $long['dateUpdated'];
	  }
	  
	  $result = array_merge($shortList, $longList, $eventsList);
	  
	  $result = array_slice($result, 0, 20); 
	  
	  usort($result, function($a, $b) {
		return $b['startDate'] - $a['startDate'];
	  });
	  
	  $html = $Template->render_group($result, true);
	  $html = $Template->apply_runtime_post_processing($html, $item);  
	  echo $html;
	
	}

  function get_short_providers(){
	  $API  = new PerchAPI(1.0, 'cjs_training');
	  $short = new CJS_Training_Short_Courses($API);
    $providersArray = $short->getProviders();
    $providers = '';
    foreach ($providersArray as $provider){
      $providers.= str_replace(",","\,",str_replace("\'","'",addslashes($provider['provider'])));
      $providers.=', ';
    }
    $providers = substr($providers, 0, -2);
//     print_r($providers);
    return $providers;
//     return "Provider 1, Provider 2, Provider 3";
  }

  function get_long_providers(){
    $API  = new PerchAPI(1.0, 'cjs_training');
    $long = new CJS_Training_Long_Courses($API);
    
    $providersArray = $long->getLProviders();    
    
    $providers = '';
    
    foreach ($providersArray as $provider){

//       $providers.= addslashes($provider['provider']);
      $providers.= str_replace(",","\,",str_replace("\'","'",addslashes($provider['provider'])));
      $providers.=', ';
    }
    $providers = substr($providers, 0, -2);
//    print_r($providers);
    return $providers;    
//     return "Provider 1, Provider 2, Provider 3";
  }

  function get_distance_providers(){
    $API  = new PerchAPI(1.0, 'cjs_training');
    $distance = new CJS_Training_Long_Courses($API);
    
    $providersArray = $distance->getDProviders();    
    
    $providers = '';
    
    foreach ($providersArray as $provider){

//       $providers.= addslashes($provider['provider']);
      $providers.= str_replace(",","\,",str_replace("\'","'",addslashes($provider['provider'])));
      $providers.=', ';
    }
    $providers = substr($providers, 0, -2);
//    print_r($providers);
    return $providers;    
//     return "Provider 1, Provider 2, Provider 3";
  }
  
  function training_list(){
	  
  }
  
  function environmental_education_feed(){
	$API  = new PerchAPI(1.0, 'cjs_training');
	$short = new CJS_Training_Short_Courses($API);
	$long = new CJS_Training_Long_Courses($API);
	$providers = new CJS_Training_Providers($API);
	$events = new CJS_Training_Events($API);
	
	$Template = $API->get('Template');
	$Template->set('cjs_training/training_feed_environmental_education.html', 'cjs');
	
	$shortList = $short->environmental_education();
	$shortArray = array();
	foreach($shortList as $short){
		$json = json_decode($short['shortDynamicFields'], true);
		$short['description'] = str_replace('&nbsp;', '', strip_tags($json['description']['processed']));
		
		$date = $short['startDate'];
		if($short['numberDays']){
			$days = $short['numberDays'];
		}else{
			$days = 0;
		}
		$date = strtotime(date("Y-m-d", strtotime($date)) . " +".$days." days");
		$short['endDate'] = date("Y-m-d", $date);
		
		$short['cjs_link'] = 'https://www.countryside-jobs.com/training/community-engagement-and-environmental-education#sc_'.$short['shortID'];

		$shortArray[] = $short;
	}
	
	$longList = $long->environmental_education();
	$longArray = array();
	foreach($longList as $long){
		$json = json_decode($long['longDynamicFields'], true);
		$long['description'] = str_replace('&nbsp;', '', strip_tags($json['description']['processed']));
		$long['startDate'] = $long['dateUpdated'];
		
		$long['cjs_link'] = 'https://www.countryside-jobs.com/training/long-courses/environmental-education#lc_'.$long['longID'];
		
		$longArray[] = $long;
	}
	
	$result = array_merge($shortArray, $longArray);
	
	usort($result, function($a, $b) {
	return $b['startDate'] - $a['startDate'];
	});
	
	$html = $Template->render_group($result, true);
	$html = $Template->apply_runtime_post_processing($html, $item);  
	echo $html;
  }
  
  function training_map(){
	  $API  = new PerchAPI(1.0, 'cjs_daily');
	  $jobs = new CJS_Daily_Jobs($API);
	
		echo '
		  <div class="flow" id="map-container">
			<h2>Browse by Location</h2>
			<div class="map-grid">
			  <div id="map" class="big-map"></div>
			</div>
			<p>Please note that not all training course adverts appear on the map, only those for which a physical location has been defined by the advertiser.</p>
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
		  	if(item[0] && item[1] && parseFloat(item[0]) !== 0 && parseFloat(item[1]) !== 0) {
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