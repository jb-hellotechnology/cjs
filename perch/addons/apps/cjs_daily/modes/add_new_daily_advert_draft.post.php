<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
    // include('_subnav.php');

    // DISPLAY THE HEADING (EDIT/NEW)
    echo $HTML->heading1($heading1);

    // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }else{
		  
	  $CJSOrganisations = $CJSOrganisations->all();
      $CJSOrgs = array();
      foreach($CJSOrganisations as $CJSOrganisation){
	      $CJSOrgs[$CJSOrganisation->organisationID()]=$CJSOrganisation->name();
      }

      $employers = $Employers->all();
      $EmpOptions = array();
      $EmpOptions[] = ['label'=>'Please Select','value'=>''];
      foreach ($employers as $Employer){
        $EmpOptions[] = ['label'=>$Employer->name()." (".$Employer->employerID().") | ORG: ".$CJSOrgs[$Employer->organisationID()]." (".$Employer->organisationID().")",'value'=>$Employer->employerID()];              
      }      
            
      // NO MESSAGE - DISPLAY FORM
	    echo $Form->form_start();    
      
      echo '<div class="form-grid"><div class="left">';
      
      // CREATE ALL STATIC FIELDS
      echo $HTML->heading2('Daily Advert Details');
      echo $Form->text_field('title', 'Title', isset($details['title'])?$details['title']:false, 'm input-simple');
      echo $Form->text_field('memberID', 'Member ID', isset($details['memberID'])?$details['memberID']:false, 'm input-simple');
      echo $Form->text_field('employerDisplay', 'Employer Display', isset($details['employerDisplay'])?$details['employerDisplay']:false, 'm input-simple');
      echo $Form->select_field('employerID', 'Employer', $EmpOptions, isset($details['employerID'])?$details['employerID']:'active');
      
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Daily_Jobs->static_fields);
      
      echo $Form->text_field('displayAdURL', 'Display Ad URL', isset($details['displayAdURL'])?$details['displayAdURL']:false, 'm input-simple');
      
      echo $HTML->heading2('Logos');
      echo $Form->text_field('imageLogo', 'Image/Logo #1', isset($details['imageLogo'])?$details['imageLogo']:false, 'm input-simple');
      echo $Form->text_field('imageAlt', 'Image Alt Text', isset($details['imageAlt'])?$details['imageAlt']:false, 'm input-simple');
      echo $Form->text_field('imageLogo2', 'Image/Logo #2', isset($details['imageLogo2'])?$details['imageLogo2']:false, 'm input-simple');
      echo $Form->text_field('imageAlt2', 'Image Alt Text #2', isset($details['imageAlt2'])?$details['imageAlt2']:false, 'm input-simple');
      echo $Form->text_field('imageLogo3', 'Image/Logo #3', isset($details['imageLogo3'])?$details['imageLogo3']:false, 'm input-simple');
      echo $Form->text_field('imageAlt3', 'Image Alt Text #3', isset($details['imageAlt3'])?$details['imageAlt3']:false, 'm input-simple');
      echo $Form->text_field('imageLogo4', 'Image/Logo #4', isset($details['imageLogo4'])?$details['imageLogo4']:false, 'm input-simple');
      echo $Form->text_field('imageAlt4', 'Image Alt Text #4', isset($details['imageAlt4'])?$details['imageAlt4']:false, 'm input-simple');
      echo $Form->text_field('imageLogo5', 'Image/Logo #5', isset($details['imageLogo5'])?$details['imageLogo5']:false, 'm input-simple');
      echo $Form->text_field('imageAlt5', 'Image Alt Text #5', isset($details['imageAlt5'])?$details['imageAlt5']:false, 'm input-simple');
      
      echo $Form->text_field('videoEmbed', 'Video Embed', isset($details['videoEmbed'])?$details['videoEmbed']:false, 'm input-simple');
      
      echo $Form->text_field('postedDate', 'Posted Date', isset($details['postedDate'])?$details['postedDate']:false, 'm input-simple datepicker');
      echo $Form->text_field('closingDate', 'Closing Date', isset($details['closingDate'])?$details['closingDate']:false, 'm input-simple datepicker');
      echo $Form->checkbox_field('jobOfDay', 'Job Of The Day?', '1', isset($details['jobOfDay'])?$details['jobOfDay']:false);
      echo $Form->text_field('jobOfDayDate', 'Job Of the Day Date', isset($details['jobOfDayDate'])?$details['jobOfDayDate']:false, 'm input-simple datepicker');
      echo $Form->checkbox_field('socialShares', 'Social Shares?', '1', isset($details['socialShares'])?$details['socialSharesDay']:false);
      echo $Form->text_field('socialSharesDate', 'Social Shares Date', isset($details['socialSharesDate'])?$details['socialSharesDate']:false, 'm input-simple datepicker');
        
      echo $Form->checkbox_field('cClosingDateEU', 'Closing Date Extended/Updated', '1', isset($details['cClosingDateEU'])?$details['cClosingDateEU']:false);
      echo $Form->checkbox_field('cDisplayDNotCDate', 'Display \'Delete Date\' Not \'Closing Date\'', '1', isset($details['cDisplayDNotCDate'])?$details['cDisplayDNotCDate']:false);
      echo $Form->checkbox_field('displayAd', 'This is a Display Advert', '1', isset($details['displayAd'])?$details['displayAd']:false);  

      echo $Form->text_field('advertAmendedDate', 'Advert Amended Date', isset($details['advertAmendedDate'])?$details['advertAmendedDate']:false, 'm input-simple datepicker');
      echo $Form->text_field('advertURL', 'Advert URL', isset($details['advertURL'])?$details['advertURL']:false, 'm input-simple');
    
      echo $HTML->heading2('Role Type');
      echo $Form->checkbox_field('cVolunteer', 'Volunteer', '1', isset($details['cVolunteer'])?$details['cVolunteer']:false);
      echo $Form->checkbox_field('cApprentice', 'Apprentice', '1', isset($details['cApprentice'])?$details['cApprentice']:false);
      echo $Form->checkbox_field('cIntern', 'Intern', '1', isset($details['cIntern'])?$details['cIntern']:false);         
      
      echo $HTML->heading2('Region');
      echo $Form->text_field('streetAddress', 'Street Address', isset($details['streetAddress'])?$details['streetAddress']:false, 'm input-simple');
      echo $Form->text_field('location', 'Location', isset($details['location'])?$details['location']:false, 'm input-simple');
      echo $Form->text_field('lat', 'Lat', isset($details['lat'])?$details['lat']:false, 'm input-simple');
      echo $Form->text_field('lng', 'Lng', isset($details['lng'])?$details['lng']:false, 'm input-simple');
      echo $Form->text_field('postCode', 'Post Code (if available)', isset($details['postCode'])?$details['postCode']:false, 'm input-simple');
      $counties = explode(",","Bedfordshire,Berkshire,Bristol,Buckinghamshire,Cambridgeshire,Cheshire,Cornwall,County Durham,Cumbria,Derbyshire,Devon,Dorset,East Riding of Yorkshire,East Sussex,Essex,Gloucestershire,Greater London,Greater Manchester,Hampshire,Herefordshire,Hertfordshire,Humberside,Isle of Wight,Isles of Scilly,Kent,Lancashire,Leicestershire,Lincolnshire,Merseyside,Norfolk,North Somerset,North Yorkshire,Northamptonshire,Northumberland,Nottinghamshire,Oxfordshire,Rutland,Shropshire,Somerset,South Gloucestershire,South Yorkshire,Staffordshire,Suffolk,Surrey,Tyne & Wear,Warwickshire,West Midlands,West Sussex,West Yorkshire,Wiltshire,Worcestershire,Aberdeenshire,Angus,Argyll & Bute,Ayrshire,Banffshire,Berwickshire,Borders,Caithness,Clackmannanshire,Dumfries & Galloway,Dunbartonshire,East Ayrshire,East Dunbartonshire,East Lothian,East Renfrewshire,Fife,Highland,Inverclyde,Kincardineshire,Lanarkshire,Midlothian,Moray,North Ayrshire,North Lanarkshire,Orkney,Perth & Kinross,Renfrewshire,Shetland,South Ayrshire,South Lanarkshire,Stirlingshire,West Dunbartonshire,West Lothian,Western Isles,Blaenau Gwent,Bridgend,Caerphilly,Cardiff,Carmarthenshire,Ceredigion,Conwy,Denbighshire,Flintshire,Gwynedd,Isle of Anglesey,Merthyr Tydfil,Monmouthshire,Neath Port Talbot,Newport,Pembrokeshire,Powys,Rhondda Cynon Taff,Swansea,Torfaen,Vale of Glamorgan,Wrexham,Antrim,Armagh,Down,Fermanagh,Londonderry,Tyrone,National,International,Home Based");
      $Counties = array();
      foreach($counties as $county){
        $Counties[] = array("label"=>$county, "value"=>$county);
      }
      echo $Form->select_field('rCounty','County', $Counties, isset($details['rCounty'])?$details['rCounty']:'active', true, true);
      echo $Form->checkbox_field('rNational', 'National', '1', isset($details['rNational'])?$details['rNational']:false);
      echo $Form->checkbox_field('rInternational', 'International', '1', isset($details['rInternational'])?$details['rInternational']:false);
      echo $Form->checkbox_field('rScotland', 'Scotland', '1', isset($details['rScotland'])?$details['rScotland']:false);
      echo $Form->checkbox_field('rScotlandNorth', 'Scotland North', '1', isset($details['rScotlandNorth'])?$details['rScotlandNorth']:false);
      echo $Form->checkbox_field('rScotlandSouth', 'Scotland South', '1', isset($details['rScotlandSouth'])?$details['rScotlandSouth']:false);
      echo $Form->checkbox_field('rWales', 'Wales', '1', isset($details['rWales'])?$details['rWales']:false);
      echo $Form->checkbox_field('rNorthernIreland', 'Northern Ireland', '1', isset($details['rNorthernIreland'])?$details['rNorthernIreland']:false);
      echo $Form->checkbox_field('rEngland', 'England', '1', isset($details['rEngland'])?$details['rEngland']:false);
      echo $Form->checkbox_field('rAnglia', 'Anglia', '1', isset($details['rAnglia'])?$details['rAnglia']:false);
      echo $Form->checkbox_field('rMidlands', 'Midlands', '1', isset($details['rMidlands'])?$details['rMidlands']:false);
      echo $Form->checkbox_field('rNorthEast', 'North East', '1', isset($details['rNorthEast'])?$details['rNorthEast']:false);
      echo $Form->checkbox_field('rNorthWest', 'North West', '1', isset($details['rNorthWest'])?$details['rNorthWest']:false);
      echo $Form->checkbox_field('rSouth', 'South', '1', isset($details['rSouth'])?$details['rSouth']:false);
      echo $Form->checkbox_field('rSouthWest', 'South West', '1', isset($details['rSouthWest'])?$details['rSouthWest']:false);  
      echo $Form->checkbox_field('rHome', 'Home', '1', isset($details['rHome'])?$details['rHome']:false);  
      
      echo $HTML->heading2('Sector');
      echo $Form->checkbox_field('sAdminIT', 'Administration, Support and IT', '1', isset($details['sAdminIT'])?$details['sAdminIT']:false);
      echo $Form->checkbox_field('sArbo', 'Arboriculture', '1', isset($details['sArbo'])?$details['sArbo']:false);
      echo $Form->checkbox_field('sComVol', 'Community and Volunteers', '1', isset($details['sComVol'])?$details['sComVol']:false);
      echo $Form->checkbox_field('sCountry', 'Countryside', '1', isset($details['sCountry'])?$details['sCountry']:false);
      echo $Form->checkbox_field('sEco', 'Ecology', '1', isset($details['sEco'])?$details['sEco']:false);
      echo $Form->checkbox_field('sEnvOE', 'Environmental and Outdoor Education', '1', isset($details['sEnvOE'])?$details['sEnvOE']:false);
      echo $Form->checkbox_field('sHorti', 'Horticulture', '1', isset($details['sHorti'])?$details['sHorti']:false);
      echo $Form->checkbox_field('sPubF', 'Publicity and Fundraising', '1', isset($details['sPubF'])?$details['sPubF']:false);
      echo $Form->checkbox_field('sRoW', 'Rights of Way', '1', isset($details['sRoW'])?$details['sRoW']:false);
      echo $Form->checkbox_field('sVisMan', 'Visitor Management', '1', isset($details['sVisMan'])?$details['sVisMan']:false);
      echo $Form->checkbox_field('sWiWoAnC', 'Wildlife Work and Animal Care', '1', isset($details['sWiWoAnC'])?$details['sWiWoAnC']:false); 
      echo $Form->checkbox_field('sSustainability', 'Sustainability', '1', isset($details['sSustainability'])?$details['sSustainability']:false); 
      echo $Form->checkbox_field('sPolicy', 'Policy and Advice', '1', isset($details['sPolicy'])?$details['sPolicy']:false); 
      echo $Form->checkbox_field('sFieldwork', 'Fieldwork and Research', '1', isset($details['sFieldwork'])?$details['sFieldwork']:false); 
      echo $Form->checkbox_field('sPractical', 'Practical Land Management', '1', isset($details['sPractical'])?$details['sPractical']:false); 
      echo $Form->checkbox_field('sForestry', 'Forestry', '1', isset($details['sForestry'])?$details['sForestry']:false);     
      
      echo $HTML->heading2('Contract');
      echo $Form->select_field('grade', 'Grade', array(
        array('label'=>'Volunteer', 'value'=>'Volunteer'), array('label'=>'Apprentice/Intern', 'value'=>'Apprentice/Intern'), array('label'=>'Entry/Graduate', 'value'=>'Entry/Graduate'), array('label'=>'Main', 'value'=>'Main'), array('label'=>'Senior', 'value'=>'Senior'), array('label'=>'Management', 'value'=>'Management')
      ), isset($details['grade'])?$details['grade']:'active');
      echo $Form->checkbox_field('cfullTime', 'Full Time', '1', isset($details['cfullTime'])?$details['cfullTime']:false);  
      echo $Form->checkbox_field('cpartTime', 'Part Time', '1', isset($details['cpartTime'])?$details['cpartTime']:false);  
      echo $Form->checkbox_field('cpermanent', 'Permanent', '1', isset($details['cpermanent'])?$details['cpermanent']:false);  
      echo $Form->checkbox_field('cTempFixed', 'Temporary/Fixed Term', '1', isset($details['cTempFixed'])?$details['cTempFixed']:false);  
      echo $Form->checkbox_field('cseasonal', 'Seasonal', '1', isset($details['cseasonal'])?$details['cseasonal']:false);  
      echo $Form->text_field('contractLength', 'Contract Length', isset($details['contractLength'])?$details['contractLength']:false, 'm input-simple');
      echo $Form->text_field('baseSalary', 'Base Salary (if available)', isset($details['baseSalary'])?$details['baseSalary']:false, 'm input-simple');
      echo $Form->select_field('salaryType', 'Salary Type', array(
        array('label'=>'HOUR', 'value'=>'HOUR'), array('label'=>'DAY', 'value'=>'DAY'), array('label'=>'WEEK', 'value'=>'WEEK'), array('label'=>'MONTH', 'value'=>'MONTH'), array('label'=>'YEAR', 'value'=>'YEAR')
      ), isset($details['salaryType'])?$details['salaryType']:'active');     
      
      echo '</div><div class="right">';
      
      echo $HTML->heading2('Admin');
      echo $Form->select_field('advertStatus', 'Advert Status', 
                               [['label'=>'Pending','value'=>'pen'],['label'=>'Overwrite','value'=>'overwrite']], 
                               isset($details['advertStatus'])?$details['advertStatus']:'active');
      echo $Form->text_field('jobReference', 'Employer Job Reference (if available)', isset($details['jobReference'])?$details['jobReference']:false, 'm input-simple');
      
      echo '</div></div>';

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/daily/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();