<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
//    include('_subnav.php');

    // DISPLAY THE HEADING (EDIT/NEW)
    echo $HTML->heading1($heading1);

    if ($message){
	    
	    echo $message; 
	    
	  }else{
      $organisations = $Organisations->all();
      $OrgOptions = array();
      $OrgOptions[] = ['label'=>'Please Select','value'=>''];
      $CJSOrganisations = $CJSOrganisations->all();
      $CJSOrgs = array();
      foreach($CJSOrganisations as $CJSOrganisation){
	      $CJSOrgs[$CJSOrganisation->organisationID()]=$CJSOrganisation->name();
      }
      
      foreach ($organisations as $Organisation){
		  $OrgOptions[] = ['label'=>$Organisation->name()." (".$Organisation->organisationID().") | ORG: ".$CJSOrgs[$Organisation->master_organisationID()]." (".$Organisation->master_organisationID().")",'value'=>$Organisation->organisationID()];
      }
       
      $Categories = [['label'=>'Choose Your Category','value'=>'-'],
                     ['label'=>'Access and Rights of Way','value'=>'row'],
                     ['label'=>'Administrative and Office Skills','value'=>'ad'],
                     ['label'=>'Community Engagement and Environmental Education','value'=>'ed'],
                     ['label'=>'Country Craft Courses','value'=>'craft'],
                     ['label'=>'Countryside Management Techniques','value'=>'cons'],
                     ['label'=>'First Aid, Risk Assessment and other Health & Safety Related Courses','value'=>'1aid'],
                     ['label'=>'Horticulture and Small Holding','value'=>'hort'],
                     ['label'=>'Identification and Field Survey Skills - Herpetology, Fish and Invertebrates','value'=>'invert'],
                     ['label'=>'Identification and Field Survey Skills - Mammals','value'=>'mammals'],
                     ['label'=>'Identification and Field Survey Skills - Ornithology','value'=>'birds'],
                     ['label'=>'Identification and Field Survey Skills - Plants and Habitats','value'=>'plants'],
                     ['label'=>'Photography','value'=>'photo'],
                     ['label'=>'Practical Countryside Skills','value'=>'skills'],
                     ['label'=>'Practical Countryside Skills - Machinery','value'=>'mach']];
	    echo $Form->form_start();
	    
	    echo '<div class="form-grid"><div class="left">';

      // THIS IS A FIELD CREATED HERE
      echo $Form->text_field('shortTitle', 'Short Course Title', isset($details['shortTitle'])?$details['shortTitle']:false, 'm input-simple');
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $SCourses->static_fields);
      echo $Form->text_field('courseCost', 'Course Cost', isset($details['courseCost'])?$details['courseCost']:false, 'm input-simple');
      echo $Form->checkbox_field('concessionsAvailable', 'Concessions Available', '1', isset($details['concessionsAvailable'])?$details['concessionsAvailable']:false);
      echo $Form->text_field('advertCost', 'Advert Cost', isset($details['advertCost'])?$details['advertCost']:false, 'm input-simple');
      echo $Form->checkbox_field('freeCourse', 'Free Course', '1', isset($details['freeCourse'])?$details['freeCourse']:false); 

      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Short Course Details');
      
      // MORE FIELDS
      echo $Form->text_field('startDate', 'Start Date', switchDate($details['startDate']), 'm input-simple datepicker');
      echo $Form->select_field('startText', 'Start Text', array(
                array('label'=>'', 'value'=>''), array('label'=>'TBC', 'value'=>'TBC'), array('label'=>'Various', 'value'=>'Various'), array('label'=>'Ad Hoc', 'value'=>'Ad Hoc'), array('label'=>'Contact For Details', 'value'=>'Contact For Details'), array('label'=>'By Arrangement', 'value'=>'By Arrangement')
           ), isset($details['startText'])?$details['startText']:'active');
      //echo $Form->text_field('startText', 'Start Text', isset($details['startText'])?$details['startText']:false, 'm input-simple');
      echo $Form->select_field('categoryOne', 'Category 1', $Categories, 
                               isset($details['categoryOne'])?$details['categoryOne']:'active');
      echo $Form->select_field('categoryTwo', 'Category 2', $Categories,
                               isset($details['categoryTwo'])?$details['categoryTwo']:'active');
      echo $Form->select_field('categoryThree', 'Category 3', $Categories,
                               isset($details['categoryThree'])?$details['categoryThree']:'active');
      echo $Form->text_field('numberDays', 'Number of Days', isset($details['numberDays'])?$details['numberDays']:false, 'm input-simple');            
      echo $Form->text_field('location', 'Location', isset($details['location'])?$details['location']:false, 'm input-simple');
      echo $Form->text_field('lat', 'Lat', isset($details['lat'])?$details['lat']:false, 'm input-simple');
      echo $Form->text_field('lng', 'Lng', isset($details['lng'])?$details['lng']:false, 'm input-simple');
      echo $Form->checkbox_field('online', 'Online?', '1', isset($details['online'])?$details['online']:false);
      echo $Form->checkbox_field('webinar', 'Webinar?', '1', isset($details['webinar'])?$details['webinar']:false);
      echo $Form->select_field('county', 'County', array(
                array('label'=>'Avon', 'value'=>'Avon'), array('label'=>'Bedfordshire', 'value'=>'Bedfordshire'), array('label'=>'Berkshire', 'value'=>'Berkshire'), array('label'=>'Buckinghamshire', 'value'=>'Buckinghamshire'), array('label'=>'Cambridgeshire', 'value'=>'Cambridgeshire'), array('label'=>'Cheshire', 'value'=>'Cheshire'), array('label'=>'Cleveland', 'value'=>'Cleveland'), array('label'=>'Cornwall', 'value'=>'Cornwall'), array('label'=>'Cumbria', 'value'=>'Cumbria'), array('label'=>'Derbyshire', 'value'=>'Derbyshire'), array('label'=>'Devon', 'value'=>'Devon'), array('label'=>'Dorset', 'value'=>'Dorset'), array('label'=>'Durham', 'value'=>'Durham'), array('label'=>'East Sussex', 'value'=>'East Sussex'), array('label'=>'Essex', 'value'=>'Essex'), array('label'=>'Gloucestershire', 'value'=>'Gloucestershire'), array('label'=>'Hampshire', 'value'=>'Hamshire'), array('label'=>'Herefordshire', 'value'=>'Herefordshire'), array('label'=>'Hertforshire', 'value'=>'Herfordshire'), array('label'=>'Isle of Wight', 'value'=>'Isle of Wight'), array('label'=>'Kent', 'value'=>'Kent'), array('label'=>'Lancashire', 'value'=>'Lancashire'), array('label'=>'Leicestershire', 'value'=>'Leicestershire'), array('label'=>'Lincolnshire', 'value'=>'Lincolnshire'), array('label'=>'London', 'value'=>'London'), array('label'=>'Merseyside', 'value'=>'Merseyside'), array('label'=>'Middlesex', 'value'=>'Middlesex'), array('label'=>'Norfolk', 'value'=>'Norfolk'), array('label'=>'Northamptonshire', 'value'=>'Northamptonshire'), array('label'=>'Northumberland', 'value'=>'Northumberland'), array('label'=>'North Humberside', 'value'=>'North Humberside'), array('label'=>'North Yorkshire', 'value'=>'North Yorkshire'), array('label'=>'Nottinghamshire', 'value'=>'Nottinghamshire'), array('label'=>'Oxfordshire', 'value'=>'Oxfordshire'), array('label'=>'Rutland', 'value'=>'Rutland'), array('label'=>'Shropshire', 'value'=>'Shropshire'), array('label'=>'Somerset', 'value'=>'Somerset'), array('label'=>'South Humberside', 'value'=>'South Humberside'), array('label'=>'South Yorkshire', 'value'=>'South Yorkshire'), array('label'=>'Staffordshire', 'value'=>'Staffordshire'), array('label'=>'Suffolk', 'value'=>'Suffolk'), array('label'=>'Surrey', 'value'=>'Surrey'), array('label'=>'Sussex', 'value'=>'Sussex'), array('label'=>'Tyne and Wear', 'value'=>'Tyne and Wear'), array('label'=>'Warwickshire', 'value'=>'Warwickshire'), array('label'=>'West Midlands', 'value'=>'West Midlands'), array('label'=>'West Sussex', 'value'=>'West Sussex'), array('label'=>'West Yorkshire', 'value'=>'West Yorkshire'), array('label'=>'Wiltshire', 'value'=>'Wiltshire'), array('label'=>'Worcestershire', 'value'=>'Worcestershire'), array('label'=>'Carmarthenshire', 'value'=>'Carmarthenshire'), array('label'=>'Clwyd', 'value'=>'Clwyd'), array('label'=>'Dyfed', 'value'=>'Dyfed'), array('label'=>'Gwent', 'value'=>'Gwent'), array('label'=>'Gwynedd', 'value'=>'Gwynedd'), array('label'=>'Mid Glamorgan', 'value'=>'Mid Glamorgan'), array('label'=>'Powys', 'value'=>'Powys'), array('label'=>'South Glamorgan', 'value'=>'South Glamorgan'), array('label'=>'West Glamorgan', 'value'=>'West Glamorgan'), array('label'=>'Aberdeenshire', 'value'=>'Aberdeenshire'), array('label'=>'Blaenau Gwent', 'value'=>'Bleanau Gwent'), array('label'=>'Bridgend', 'value'=>'Bridgend'), array('label'=>'Caerphilly', 'value'=>'Caerphilly'), array('label'=>'Cardiff', 'value'=>'Cardiff'), array('label'=>'Ceredigion', 'value'=>'Ceredigon'), array('label'=>'Conwy', 'value'=>'Conwy'), array('label'=>'Denbighshire', 'value'=>'Denbigshire'), array('label'=>'Flintshire', 'value'=>'Flintshire'), array('label'=>'Isle of Anglesey', 'value'=>'Isle of Anglesey'), array('label'=>'Methry Tydfil', 'value'=>'Methry Tyfil'), array('label'=>'Monmouthshire', 'value'=>'Monmouthshire'), array('label'=>'Neath Port Talbot', 'value'=>'Neath Port Talbot'), array('label'=>'Newport', 'value'=>'Newport'), array('label'=>'Pembrokeshire', 'value'=>'Pembrokeshire'), array('label'=>'Rhondda Cynon Taff', 'value'=>'Rhondda Cynon Taff'), array('label'=>'Swansea', 'value'=>'Swansea'), array('label'=>'The Vale of Glamorgan', 'value'=>'The Vale of Glamorgan'), array('label'=>'Torfaen', 'value'=>'Torfaen'), array('label'=>'Wrexham', 'value'=>'Wrexham'), array('label'=>'Aberdeenshire', 'value'=>'Aberdeenshire'), array('label'=>'Angus', 'value'=>'Angus'), array('label'=>'Argyll and Bute', 'value'=>'Argyll and Bute'), array('label'=>'City of Edinburgh', 'value'=>'City of Edinburgh'), array('label'=>'Clackmannanshire', 'value'=>'Clackmannanshire'), array('label'=>'Comhairlenan Eilean Siar', 'value'=>'Comhairlenan Eilean Siar'), array('label'=>'Dumfries and Galloway', 'value'=>'Dumfries and Galloway'), array('label'=>'Dundee', 'value'=>'Dundee'), array('label'=>'East Ayrshire', 'value'=>'Easy Ayrshire'), array('label'=>'East Dunbartonshire', 'value'=>'East Dunbartonshire'), array('label'=>'East Lothian', 'value'=>'East Lothian'), array('label'=>'East Renfrewshire', 'value'=>'East Renfrewshire'), array('label'=>'Falkirk', 'value'=>'Falkirk'), array('label'=>'Fife', 'value'=>'Fife'), array('label'=>'Glasgow', 'value'=>'Glasgow'), array('label'=>'Highland', 'value'=>'Highland'), array('label'=>'Inverclyde', 'value'=>'Inverclyde'), array('label'=>'Midlothian', 'value'=>'Midlothian'), array('label'=>'Moray', 'value'=>'Moray'), array('label'=>'North Ayrshire', 'value'=>'North Ayrshire'), array('label'=>'North Lanarkshire', 'value'=>'North Lanarkshire'), array('label'=>'Orkney', 'value'=>'Orkney'), array('label'=>'Perth and Kinross', 'value'=>'Perth and Kinross'), array('label'=>'Renfrewshire', 'value'=>'Renfrewshire'), array('label'=>'Scottish Borders', 'value'=>'Scottish Borders'), array('label'=>'Shetland Islands', 'value'=>'Shetland Islands'), array('label'=>'South Ayrshire', 'value'=>'South Ayrshire'), array('label'=>'South Lanarkshire', 'value'=>'South Lanarkshire'), array('label'=>'Stirling', 'value'=>'Stirling'), array('label'=>'West Dunbartonshire', 'value'=>'West Dunbartonshire'), array('label'=>'West Lothian', 'value'=>'West Lothian'), array('label'=>'Antrim', 'value'=>'Antrim'), array('label'=>'Armagh', 'value'=>'Armagh'), array('label'=>'County Down', 'value'=>'County Down'), array('label'=>'Fermanagh', 'value'=>'Fermanagh'), array('label'=>'Londonderry', 'value'=>'Londonderry'), array('label'=>'Tyrone', 'value'=>'Tyrone'), array('label'=>'UK Wide', 'value'=>'UK Wide'), array('label'=>'England', 'value'=>'England'), array('label'=>'Scotland', 'value'=>'Scotland'), array('label'=>'Wales', 'value'=>'Wales'), array('label'=>'Northern Ireland', 'value'=>'Northern Ireland'), array('label'=>'Jersey', 'value'=>'Jersey'), array('label'=>'Ireland', 'value'=>'Ireland'), array('label'=>'International', 'value'=>'International')
            ), isset($details['county'])?$details['county']:'active');
      echo $Form->text_field('provider', 'Provider', isset($details['provider'])?$details['provider']:false, 'm input-simple');
      echo $Form->text_field('advertTelephone', 'Advert Telephone', isset($details['advertTelephone'])?$details['advertTelephone']:false, 'm input-simple');      
      echo $Form->text_field('advertEmail', 'Advert Email', isset($details['advertEmail'])?$details['advertEmail']:false, 'm input-simple');
      echo $Form->text_field('advertWebsite', 'Advert Website (Long)', isset($details['advertWebsite'])?$details['advertWebsite']:false, 'm input-simple');
      echo $Form->text_field('advertWebsiteShort', 'Advert Website (Short)', isset($details['advertWebsiteShort'])?$details['advertWebsiteShort']:false, 'm input-simple');
      echo $Form->text_field('ticketingURL', 'Ticketing URL', isset($details['ticketingURL'])?$details['ticketingURL']:false, 'm input-simple');
      echo $Form->select_field('advertLink', 'Advert Link Display', array(
                array('label'=>'Short', 'value'=>'1'), array('label'=>'Long', 'value'=>'0')
      ), isset($details['advertLink'])?$details['advertLink']:'active'); 
      echo $Form->text_field('logo', 'Logo URL', isset($details['logo'])?$details['logo']:false, 'm input-simple');
      echo $Form->text_field('logoAlt', 'Logo Alt', isset($details['logoAlt'])?$details['logoAlt']:false, 'm input-simple');
	  echo $Form->checkbox_field('displayAd', 'Display Advert', '1', isset($details['displayAd'])?$details['displayAd']:false);
     
      echo $HTML->heading2('Office Admin Details');
      echo $Form->select_field('tOrganisationID', 'Office', $OrgOptions, isset($details['organisationID'])?$details['organisationID']:'active'); 
      echo $Form->text_field('organisationName', 'Office Name', isset($details['organisationName'])?$details['organisationName']:false, 'm input-simple');
      echo $Form->text_field('contactName', 'Contact Name', isset($details['contactName'])?$details['contactName']:false, 'm input-simple');
      echo $Form->text_field('contactTelephone', 'Contact Telephone', isset($details['contactTelephone'])?$details['contactTelephone']:false, 'm input-simple');
      echo $Form->text_field('contactEmail', 'Contact Email', isset($details['contactEmail'])?$details['contactEmail']:false, 'm input-simple');
      
      echo $HTML->heading2('Advert Admin Details');
      echo $Form->text_field('firstOnline', 'First Online', switchDate($details['firstOnline']), 'm input-simple datepicker');
      echo $Form->text_field('checkDate', 'Check Date', switchDate($details['checkDate']), 'm input-simple datepicker');
      echo $Form->text_field('displayUntil', 'Display Until', switchDate($details['displayUntil']), 'm input-simple datepicker');
      echo $Form->text_field('advertType', 'Advert Type', isset($details['advertType'])?$details['advertType']:false, 'm input-simple');
      echo $Form->select_field('advertPaid', 'Advert Paid', array(
                array('label'=>'No', 'value'=>'0'), array('label'=>'Yes', 'value'=>'1')
           ), isset($details['advertPaid'])?$details['advertPaid']:'active');
      echo $Form->checkbox_field('freeAd', 'Free Advert', '1', isset($details['freeAd'])?$details['freeAd']:false);
      echo '</div><div class="right">';
    
	  echo $HTML->heading2('Admin');
      echo $Form->select_field('advertStatus', 'Advert Status', 
                               array(array('label'=>'Published', 'value'=>'pub'), 
                                     array('label'=>'Pending', 'value'=>'pen'), 
                                     array('label'=>'Suspended', 'value'=>'sus')),
                               isset($details['advertStatus'])?$details['advertStatus']:'active');
      
      if($_GET['id']){
	      echo $HTML->heading2('Delete');
	      echo '<p style="margin-left:36px"><a href="/perch/addons/apps/cjs_training/short_courses/delete/?id='.$_GET['id'].'">Click Here to Delete</a></p>';
      }
      if($_GET['id']){
	      echo $HTML->heading2('Duplicate');
	      echo '<p style="margin-left:36px"><a href="/perch/addons/apps/cjs_training/short_courses/duplicate/?id='.$_GET['id'].'">Click Here to Duplicate Course</a></p>';
      }

	  echo '</div></div>';

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/short_courses/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();