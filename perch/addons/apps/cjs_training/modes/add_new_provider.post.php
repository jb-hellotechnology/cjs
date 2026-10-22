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

	    echo $Form->form_start();
	    
	    echo '<div class="form-grid"><div class="left">';

      // THIS IS A FIELD CREATED HERE
      echo $Form->text_field('providerName', 'Provider Name/Center Name (Ad Title)', isset($details['providerName'])?$details['providerName']:false, 'm input-simple');
	  
	  // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Providers->static_fields);

      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Provider Details');
      // MORE FIELDS
      echo $Form->text_field('advertTelephone', 'Advert Telephone', isset($details['advertTelephone'])?$details['advertTelephone']:false, 'm input-simple');      
      echo $Form->text_field('advertEmail', 'Advert Email', isset($details['advertEmail'])?$details['advertEmail']:false, 'm input-simple');
      echo $Form->text_field('advertWebsite', 'Advert Website (Long)', isset($details['advertWebsite'])?$details['advertWebsite']:false, 'm input-simple');
      echo $Form->text_field('advertWebsiteShort', 'Advert Website (Short)', isset($details['advertWebsiteShort'])?$details['advertWebsiteShort']:false, 'm input-simple');
      echo $Form->select_field('advertLink', 'Advert Link Display', array(
                      array('label'=>'Long', 'value'=>'l'), array('label'=>'Short', 'value'=>'s')
                  ), isset($details['advertLink'])?$details['advertLink']:'active');      
      echo $Form->text_field('logo', 'Logo URL', isset($details['logo'])?$details['logo']:false, 'm input-simple');
      echo $Form->text_field('logoAlt', 'Logo Alt', isset($details['logoAlt'])?$details['logoAlt']:false, 'm input-simple');

      echo $Form->checkbox_field('displayAd', 'Display Advert?', '1', isset($details['displayAd'])?$details['displayAd']:false);

      echo $Form->text_field('location', 'Location', isset($details['location'])?$details['location']:false, 'm input-simple');
      echo $Form->select_field('county', 'County', array(
        array('label'=>'Bedfordshire', 'value'=>'Bedfordshire'), array('label'=>'Berkshire', 'value'=>'Berkshire'),
        array('label'=>'Bristol', 'value'=>'Bristol'), array('label'=>'Buckinghamshire', 'value'=>'Buckinghamshire'),
        array('label'=>'Cambridgeshire', 'value'=>'Cambridgeshire'), array('label'=>'Cheshire', 'value'=>'Cheshire'),
        array('label'=>'Cornwall', 'value'=>'Cornwall'), array('label'=>'County Durham', 'value'=>'County Durham'),
        array('label'=>'Cumbria', 'value'=>'Cumbria'), array('label'=>'Derbyshire', 'value'=>'Derbyshire'),
        array('label'=>'Devon', 'value'=>'Devon'), array('label'=>'Dorset', 'value'=>'Dorset'),
        array('label'=>'East Riding of Yorkshire', 'value'=>'East Riding of Yorkshire'), array('label'=>'East Sussex', 'value'=>'East Sussex'),
        array('label'=>'Essex', 'value'=>'Essex'), array('label'=>'Gloucestershire', 'value'=>'Gloucestershire'),
        array('label'=>'Greater London', 'value'=>'Greater London'), array('label'=>'Greater Manchester', 'value'=>'Greater Manchester'),
        array('label'=>'Hampshire', 'value'=>'Hampshire'), array('label'=>'Herefordshire', 'value'=>'Herefordshire'),
        array('label'=>'Hertfordshire', 'value'=>'Hertfordshire'), array('label'=>'Humberside', 'value'=>'Humberside'),
        array('label'=>'Isle of Wight', 'value'=>'Isle of Wight'), array('label'=>'Isles of Scilly', 'value'=>'Isles of Scilly'),
        array('label'=>'Kent', 'value'=>'Kent'), array('label'=>'Lancashire', 'value'=>'Lancashire'),
        array('label'=>'Leicestershire', 'value'=>'Leicestershire'), array('label'=>'Lincolnshire', 'value'=>'Lincolnshire'),
        array('label'=>'Merseyside', 'value'=>'Merseyside'), array('label'=>'Norfolk', 'value'=>'Norfolk'),
        array('label'=>'North Somerset', 'value'=>'North Somerset'), array('label'=>'North Yorkshire', 'value'=>'North Yorkshire'),
        array('label'=>'Northamptonshire', 'value'=>'Northamptonshire'), array('label'=>'Northumberland', 'value'=>'Northumberland'),
        array('label'=>'Nottinghamshire', 'value'=>'Nottinghamshire'), array('label'=>'Oxfordshire', 'value'=>'Oxfordshire'),
        array('label'=>'Rutland', 'value'=>'Rutland'), array('label'=>'Shropshire', 'value'=>'Shropshire'),
        array('label'=>'Somerset', 'value'=>'Somerset'), array('label'=>'South Gloucestershire', 'value'=>'South Gloucestershire'),
        array('label'=>'South Yorkshire', 'value'=>'South Yorkshire'), array('label'=>'Staffordshire', 'value'=>'Staffordshire'),
        array('label'=>'Suffolk', 'value'=>'Suffolk'), array('label'=>'Surrey', 'value'=>'Surrey'),
        array('label'=>'Tyne & Wear', 'value'=>'Tyne & Wear'), array('label'=>'Warwickshire', 'value'=>'Warwickshire'),
        array('label'=>'West Midlands', 'value'=>'West Midlands'), array('label'=>'West Sussex', 'value'=>'West Sussex'),
        array('label'=>'West Yorkshire', 'value'=>'West Yorkshire'), array('label'=>'Wiltshire', 'value'=>'Wiltshire'),
        array('label'=>'Worcestershire', 'value'=>'Worcestershire'), array('label'=>'Aberdeenshire', 'value'=>'Aberdeenshire'),
        array('label'=>'Angus', 'value'=>'Angus'), array('label'=>'Argyll & Bute', 'value'=>'Argyll & Bute'),
        array('label'=>'Ayrshire', 'value'=>'Ayrshire'), array('label'=>'Banffshire', 'value'=>'Banffshire'),
        array('label'=>'Berwickshire', 'value'=>'Berwickshire'), array('label'=>'Borders', 'value'=>'Borders'),
        array('label'=>'Caithness', 'value'=>'Caithness'), array('label'=>'Clackmannanshire', 'value'=>'Clackmannanshire'),
        array('label'=>'Dumfries & Galloway', 'value'=>'Dumfries & Galloway'), array('label'=>'Dunbartonshire', 'value'=>'Dunbartonshire'),
        array('label'=>'East Ayrshire', 'value'=>'East Ayrshire'), array('label'=>'East Dunbartonshire', 'value'=>'East Dunbartonshire'),
        array('label'=>'East Lothian', 'value'=>'East Lothian'), array('label'=>'East Renfrewshire', 'value'=>'East Renfrewshire'),
        array('label'=>'Fife', 'value'=>'Fife'), array('label'=>'Highland', 'value'=>'Highland'),
        array('label'=>'Inverclyde', 'value'=>'Inverclyde'), array('label'=>'Kincardineshire', 'value'=>'Kincardineshire'),
        array('label'=>'Lanarkshire', 'value'=>'Lanarkshire'), array('label'=>'Midlothian', 'value'=>'Midlothian'),
        array('label'=>'Moray', 'value'=>'Moray'), array('label'=>'North Ayrshire', 'value'=>'North Ayrshire'),
        array('label'=>'North Lanarkshire', 'value'=>'North Lanarkshire'), array('label'=>'Orkney', 'value'=>'Orkney'),
        array('label'=>'Perth & Kinross', 'value'=>'Perth & Kinross'), array('label'=>'Renfrewshire', 'value'=>'Renfrewshire'),
        array('label'=>'Shetland', 'value'=>'Shetland'), array('label'=>'South Ayrshire', 'value'=>'South Ayrshire'),
        array('label'=>'South Lanarkshire', 'value'=>'South Lanarkshire'), array('label'=>'Stirlingshire', 'value'=>'Stirlingshire'),
        array('label'=>'West Dunbartonshire', 'value'=>'West Dunbartonshire'), array('label'=>'West Lothian', 'value'=>'West Lothian'),
        array('label'=>'Western Isles', 'value'=>'Western Isles'), array('label'=>'Blaenau Gwent', 'value'=>'Blaenau Gwent'),
        array('label'=>'Bridgend', 'value'=>'Bridgend'), array('label'=>'Caerphilly', 'value'=>'Caerphilly'),
        array('label'=>'Cardiff', 'value'=>'Cardiff'), array('label'=>'Carmarthenshire', 'value'=>'Carmarthenshire'),
        array('label'=>'Ceredigion', 'value'=>'Ceredigion'), array('label'=>'Conwy', 'value'=>'Conwy'),
        array('label'=>'Denbighshire', 'value'=>'Denbighshire'), array('label'=>'Flintshire', 'value'=>'Flintshire'),
        array('label'=>'Gwynedd', 'value'=>'Gwynedd'), array('label'=>'Isle of Anglesey', 'value'=>'Isle of Anglesey'),
        array('label'=>'Merthyr Tydfil', 'value'=>'Merthyr Tydfil'), array('label'=>'Monmouthshire', 'value'=>'Monmouthshire'),
        array('label'=>'Neath Port Talbot', 'value'=>'Neath Port Talbot'), array('label'=>'Newport', 'value'=>'Newport'),
        array('label'=>'Pembrokeshire', 'value'=>'Pembrokeshire'), array('label'=>'Powys', 'value'=>'Powys'),
        array('label'=>'Rhondda Cynon Taff', 'value'=>'Rhondda Cynon Taff'), array('label'=>'Swansea', 'value'=>'Swansea'),
        array('label'=>'Torfaen', 'value'=>'Torfaen'), array('label'=>'Vale of Glamorgan', 'value'=>'Vale of Glamorgan'),
        array('label'=>'Wrexham', 'value'=>'Wrexham'), array('label'=>'Antrim', 'value'=>'Antrim'),
        array('label'=>'Armagh', 'value'=>'Armagh'), array('label'=>'Down', 'value'=>'Down'),
        array('label'=>'Fermanagh', 'value'=>'Fermanagh'), array('label'=>'Londonderry', 'value'=>'Londonderry'),
        array('label'=>'Tyrone', 'value'=>'Tyrone'), array('label'=>'UK Wide', 'value'=>'UK Wide'),
        array('label'=>'England', 'value'=>'England'), array('label'=>'Scotland', 'value'=>'Scotland'),
        array('label'=>'Wales', 'value'=>'Wales'), array('label'=>'Northern Ireland', 'value'=>'Northern Ireland'),
        array('label'=>'Jersey', 'value'=>'Jersey'), array('label'=>'Ireland', 'value'=>'Ireland'),
        array('label'=>'International', 'value'=>'International')
      ), isset($details['county'])?$details['county']:'active');
      
      echo $HTML->heading2('Courses Offered');
      echo $Form->checkbox_field('cShort', 'Short', '1', isset($details['cShort'])?$details['cShort']:false);
      echo $Form->checkbox_field('cHnd', 'HND', '1', isset($details['cHnd'])?$details['cHnd']:false);
      echo $Form->checkbox_field('cDiploma', 'Diploma', '1', isset($details['cDiploma'])?$details['cDiploma']:false);
      echo $Form->checkbox_field('cNvq', 'NVQ', '1', isset($details['cNvq'])?$details['cNvq']:false);
      echo $Form->checkbox_field('cHnc', 'HNC', '1', isset($details['cHnc'])?$details['cHnc']:false);
      echo $Form->checkbox_field('cMsc', 'MSC', '1', isset($details['cMsc'])?$details['cMsc']:false);
      echo $Form->checkbox_field('cDegree', 'Degree', '1', isset($details['cDegree'])?$details['cDegree']:false);
      echo $Form->checkbox_field('cBtec', 'BTEC', '1', isset($details['cBtec'])?$details['cBtec']:false);
      echo $Form->checkbox_field('cFoundation', 'Foundation', '1', isset($details['cFoundation'])?$details['cFoundation']:false);
      echo $Form->checkbox_field('cPhd', 'PHD', '1', isset($details['cPhd'])?$details['cPhd']:false);
      echo $Form->checkbox_field('cApprenticeship', 'Apprenticeship', '1', isset($details['cApprenticeship'])?$details['cApprenticeship']:false);
      
      echo $HTML->heading2('Office Admin Details');
      echo $Form->select_field('tOrganisationID', 'Office', $OrgOptions, isset($details['organisationID'])?$details['organisationID']:'active'); 
      echo $Form->text_field('organisationName', 'Office Name', isset($details['organisationName'])?$details['organisationName']:false, 'm input-simple');
      echo $Form->text_field('contactName', 'Contact Name', isset($details['contactName'])?$details['contactName']:false, 'm input-simple');
      echo $Form->text_field('contactTelephone', 'Contact Telephone', isset($details['contactPhone'])?$details['contactPhone']:false, 'm input-simple');
      echo $Form->text_field('contactEmail', 'Contact Email', isset($details['contactEmail'])?$details['contactEmail']:false, 'm input-simple');
      
      echo $HTML->heading2('Advert Admin Details');
      echo $Form->text_field('firstOnline', 'First Online', switchDate($details['firstOnline']), 'm input-simple datepicker');
      echo $Form->text_field('checkDate', 'Check Date', switchDate($details['checkDate']), 'm input-simple datepicker');
      echo $Form->text_field('displayUntil', 'Display Until', switchDate($details['displayUntil']), 'm input-simple datepicker');
      echo $Form->text_field('advertType', 'Advert Type', isset($details['advertType'])?$details['advertType']:false, 'm input-simple');
      
      echo $Form->checkbox_field('advertPaid', 'Advert Paid?', '1', isset($details['advertPaid'])?$details['advertPaid']:false);
	  echo $Form->checkbox_field('freeAd', 'Free Advert?', '1', isset($details['freeAd'])?$details['freeAd']:false);
      echo $Form->text_field('advertCost', 'Advert Cost', isset($details['advertCost'])?$details['advertCost']:false, 'm input-simple');
      
      echo '</div><div class="right">';
      
      echo $HTML->heading2('Admin');
      echo $Form->select_field('advertStatus', 'Advert Status', array(
        array('label'=>'Published', 'value'=>'pub'), array('label'=>'Pending', 'value'=>'pen'), array('label'=>'Suspended', 'value'=>'sus')),
      isset($details['advertStatus'])?$details['advertStatus']:'active');
      
      if($_GET['id']){
	      echo $HTML->heading2('Delete');
	      echo '<p style="margin-left:36px"><a href="/perch/addons/apps/cjs_training/providers/delete/?id='.$_GET['id'].'">Click Here to Delete</a></p>';
	  }
      
      echo '</div></div>';
      
      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/providers/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();