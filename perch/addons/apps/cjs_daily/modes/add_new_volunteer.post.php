<?php    

    # Main panel
    echo $HTML->main_panel_start();

//print_r($details);
    
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
      
      echo $HTML->heading2('Volunteer Details');
      echo $Form->text_field('title', 'Title', isset($details['title'])?$details['title']:false, 'm input-simple');
      echo $Form->text_field('advertRef', 'Advert Ref (Do not change)', isset($details['advertRef'])?$details['advertRef']:false, 'm input-simple');
      echo $Form->text_field('memberID', 'Member ID', isset($details['memberID'])?$details['memberID']:false, 'm input-simple');
      echo $Form->text_field('employerDisplay', 'Employer Display', isset($details['employerDisplay'])?$details['employerDisplay']:false, 'm input-simple');
      echo $Form->select_field('employerID', 'Employer', $EmpOptions, isset($details['employerID'])?$details['employerID']:'active');      
      echo $Form->text_field('closingDate', 'Closing Date', isset($details['closingDate'])?$details['closingDate']:false, 'm input-simple datepicker');
      echo $Form->text_field('closingDateT', 'Closing Date Text', isset($details['closingDateT'])?$details['closingDateT']:false, 'm input-simple');
      echo $Form->text_field('intDate', 'Interview Date', isset($details['intDate'])?$details['intDate']:false, 'm input-simple datepicker');
      echo $Form->text_field('location', 'Location', isset($details['location'])?$details['location']:false, 'm input-simple');      
      echo $Form->text_field('payBenefits', 'Pay/Benefits', isset($details['payBenefits'])?$details['payBenefits']:false, 'm input-simple');    
      echo $Form->checkbox_field('accommodation', 'Accommodation', '1', isset($details['Accommodation'])?$details['Accommodation']:false);
      echo $Form->checkbox_field('expenses', 'Expenses', '1', isset($details['expenses'])?$details['expenses']:false);
      echo $Form->checkbox_field('fTraining', 'Formal Training', '1', isset($details['fTraining'])?$details['fTraining']:false);      
      
      echo $Form->textarea_field('advertText','Advert Text',isset($details['advertText'])?$details['advertText']:false, 'm input-simple');
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Volunteers->static_fields);    

      echo $HTML->heading2('Time Commitment');
      echo $Form->checkbox_field('tcOccasional', 'Occasional', '1', isset($details['tcOccasional'])?$details['tcOccasional']:false);
      echo $Form->checkbox_field('tcOnceorTwiceWeek', 'Once or Twice a Week', '1', isset($details['tcOnceorTwiceWeek'])?$details['tcOnceorTwiceWeek']:false);
      echo $Form->checkbox_field('tcPartTime', 'Part Time', '1', isset($details['tcPartTime'])?$details['tcPartTime']:false);
      echo $Form->checkbox_field('tcFullTime', 'Full Time', '1', isset($details['tcFullTime'])?$details['tcFullTime']:false);
      echo $Form->checkbox_field('tcSixMonths', 'Up To Six Months', '1', isset($details['tcSixMonths'])?$details['tcSixMonths']:false);
      echo $Form->checkbox_field('tcSixMonthYear', 'Six Months to One Year', '1', isset($details['tcSixMonthYear'])?$details['tcSixMonthYear']:false);
      echo $Form->checkbox_field('tcOneYearMore', 'One Year or More', '1', isset($details['tcOneYearMore'])?$details['tcOneYearMore']:false);

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
      
      echo $HTML->heading2('Region');
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
      echo $Form->checkbox_field('rFromHome', 'From Home', '1', isset($details['rFromHome'])?$details['rFromHome']:false); 
      
      $counties = explode(",","Please Select,Bedfordshire,Berkshire,Bristol,Buckinghamshire,Cambridgeshire,Cheshire,Cornwall,County Durham,Cumbria,Derbyshire,Devon,Dorset,East Riding of Yorkshire,East Sussex,Essex,Gloucestershire,Greater London,Greater Manchester,Hampshire,Herefordshire,Hertfordshire,Humberside,Isle of Wight,Isles of Scilly,Kent,Lancashire,Leicestershire,Lincolnshire,Merseyside,Norfolk,North Somerset,North Yorkshire,Northamptonshire,Northumberland,Nottinghamshire,Oxfordshire,Rutland,Shropshire,Somerset,South Gloucestershire,South Yorkshire,Staffordshire,Suffolk,Surrey,Tyne & Wear,Warwickshire,West Midlands,West Sussex,West Yorkshire,Wiltshire,Worcestershire,Aberdeenshire,Angus,Argyll & Bute,Ayrshire,Banffshire,Berwickshire,Borders,Caithness,Clackmannanshire,Dumfries & Galloway,Dunbartonshire,East Ayrshire,East Dunbartonshire,East Lothian,East Renfrewshire,Fife,Highland,Inverclyde,Kincardineshire,Lanarkshire,Midlothian,Moray,North Ayrshire,North Lanarkshire,Orkney,Perth & Kinross,Renfrewshire,Shetland,South Ayrshire,South Lanarkshire,Stirlingshire,West Dunbartonshire,West Lothian,Western Isles,Blaenau Gwent,Bridgend,Caerphilly,Cardiff,Carmarthenshire,Ceredigion,Conwy,Denbighshire,Flintshire,Gwynedd,Isle of Anglesey,Merthyr Tydfil,Monmouthshire,Neath Port Talbot,Newport,Pembrokeshire,Powys,Rhondda Cynon Taff,Swansea,Torfaen,Vale of Glamorgan,Wrexham,Antrim,Armagh,Down,Fermanagh,Londonderry,Tyrone,National,International,Home Based");
      $Counties = array();
      foreach($counties as $county){
        $Counties[] = array("label"=>$county, "value"=>$county);
      }
      echo $Form->select_field('rCounty','County', $Counties, isset($details['rCounty'])?$details['rCounty']:'active', true, true);      
      
      echo $HTML->heading2('Additional Details');
      echo $Form->select_field('typeRole', 'Type of Role', array(
                array('label'=>'Placement', 'value'=>'Placement'), array('label'=>'Apprentice', 'value'=>'Apprentice')
      ), isset($details['typeRole'])?$details['typeRole']:'active');
      echo $Form->text_field('imageLogo', 'Image/Logo', isset($details['imageLogo'])?$details['imageLogo']:false, 'm input-simple');
      echo $Form->text_field('imageAlt', 'Image Alt Text', isset($details['imageAlt'])?$details['imageAlt']:false, 'm input-simple');
      echo $Form->text_field('imageLogo2', 'Image/Logo 2', isset($details['imageLogo2'])?$details['imageLogo2']:false, 'm input-simple');
      echo $Form->text_field('imageAlt2', 'Image Alt Text 2', isset($details['imageAlt2'])?$details['imageAlt2']:false, 'm input-simple');
      echo $Form->text_field('vidEmbedC', 'Video Embed Code', isset($details['vidEmbedC'])?$details['vidEmbedC']:false, 'm input-simple');
      echo $Form->checkbox_field('longRun', 'Long Running', '1', isset($details['longRun'])?$details['longRun']:false);
      echo $Form->text_field('deleteDate', 'Delete Date', isset($details['deleteDate'])?$details['deleteDate']:false, 'm input-simple datepicker');
      echo $Form->text_field('displayUntil', 'Display Until', isset($details['displayUntil'])?$details['displayUntil']:false, 'm input-simple datepicker');
      echo $Form->checkbox_field('freeLin', 'Free Linage?', '1', isset($details['freeLin'])?$details['freeLin']:false);
      echo $Form->text_field('displayFrom', 'Display From', isset($details['displayFrom'])?$details['displayFrom']:false, 'm input-simple datepicker');      
      echo $Form->checkbox_field('archive', 'Archive', '1', isset($details['archive'])?$details['archive']:false);
      
      echo '</div><div class="right">';
      
      echo $HTML->heading2('Admin');

	  $draft = $Daily_Jobs->get_vol_advert_draft_by_job_ref($details['advertRef']);

      if($Daily_Jobs->draft_vol_exists($details['advertRef']) AND $draft[0]['volunteerID']==$_GET['id']){
	  		echo $Form->select_field('advertStatus', 'Advert Status', 
                               [['label'=>'Pending','value'=>'pen'],['label'=>'Published','value'=>'pub'],['label'=>'Suspended','value'=>'sus'],['label'=>'Pending Overwrite','value'=>'over'],['label'=>'Confirm Overwrite','value'=>'overwrite']], isset($details['advertStatus'])?$details['advertStatus']:'active');   
      }else{
      		echo $Form->select_field('advertStatus', 'Advert Status', 
                               [['label'=>'Pending','value'=>'pen'],['label'=>'Published','value'=>'pub'],['label'=>'Suspended','value'=>'sus']], 
                               isset($details['advertStatus'])?$details['advertStatus']:'active');
      }
      
      if($_GET['id']){                    
	      echo $HTML->heading2('Delete');
	      echo '<p style="margin-left:36px"><a href="/perch/addons/apps/cjs_daily/volunteers/delete/?id='.$_GET['id'].'">Click Here to Delete</a></p>';
	      //IS THERE A DRAFT?
	      if($Daily_Jobs->draft_vol_exists($details['advertRef'])){
		      if($draft[0]['volunteerID']<>$_GET['id']){
			      echo $HTML->heading2('Draft Awaiting Approval');
			      echo '<p style="margin-left:36px"><a href="/perch/addons/apps/cjs_daily/volunteers/add_new/?id='.$draft[0]['volunteerID'].'">Click Here to View</a></p>';
		      }
	      }
	  }

      echo '</div></div>';

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/volunteers/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();
    
    echo "<script>$(function() {
    $('textarea').redactor({
	    linkNewTab: true,
	    linkTarget: '_blank'
	});
    })
    </script>";