<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
//     include('_subnav.php');

     // DISPLAY THE HEADING (EDIT/NEW)
    echo $HTML->heading1($heading1);

     // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }else{
      $organisations = $Organisations_wd->all();
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
      echo $Form->text_field('Title', 'Title', isset($details['Title'])?$details['Title']:false, 'm input-simple');

      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Survey Details');
      
// ***********AQUÍ VOY! ESTOY HACIENDO LA FORMA DE SURVEYS Y ESTOS SON LOS CAMPOS*********** //      
// array('surveyID', 'Title', 'region', 'section', 'provider', 'advertEmail', 'advertWebsite', 'advertDisplay', 'logo', 'logoAlt', 'displayAd', 'bannerAd',
//                                 'bannerAdPos', 'organisationID', 'organisationName', 'contactName', 'contactEmail', 'firstOnline', 'checkDate', 'displayUntil'
//                                 'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus', 'surveysDynamicFields');	
      
      // MORE FIELDS
      echo $Form->select_field('region', 'Region', array(array('label'=>'East Anglia', 'value'=>'East Anglia'), array('label'=>'Midlands', 'value'=>'Midlands'), array('label'=>'North East', 'value'=>'North East'), array('label'=>'North West', 'value'=>'North West'), array('label'=>'South', 'value'=>'South'), array('label'=>'South West', 'value'=>'South West'),  array('label'=>'Scotland', 'value'=>'Scotland'), array('label'=>'Wales', 'value'=>'Wales'), array('label'=>'Northern Ireland', 'value'=>'Northern Ireland'), array('label'=>'National', 'value'=>'National'), array('label'=>'International', 'value'=>'International') ), 
                               isset($details['region'])?$details['region']:'active');
      echo $Form->select_field('section', 'Section', array(array('label'=>'General', 'value'=>'general'), array('label'=>'Birds', 'value'=>'birds'), array('label'=>'Mammals', 'value'=>'mammals'), array('label'=>'Herpetology', 'value'=>'herpetology'), array('label'=>'Plants', 'value'=>'plants'), array('label'=>'Fish/Marine', 'value'=>'fish'),  array('label'=>'Invertebrates', 'value'=>'invertebrates'), array('label'=>'Research', 'value'=>'research') ), 
                               isset($details['section'])?$details['section']:'section');
      echo $Form->text_field('provider', 'Provider', isset($details['provider'])?$details['provider']:false, 'm input-simple'); 
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Surveys->static_fields);   
      echo $Form->text_field('advertEmail', 'Advert Email', isset($details['advertEmail'])?$details['advertEmail']:false, 'm input-simple');
      echo $Form->text_field('advertWebsite', 'Advert Website (Long)', isset($details['advertWebsite'])?$details['advertWebsite']:false, 'm input-simple');
      echo $Form->text_field('advertWebsiteShort', 'Advert Website (Short)', isset($details['advertWebsiteShort'])?$details['advertWebsiteShort']:false, 'm input-simple');      
      echo $Form->select_field('advertDisplay', 'Advert Link Display', array(
                array('label'=>'Long', 'value'=>'l'), array('label'=>'Short', 'value'=>'s')
      ), isset($details['advertDisplay'])?$details['advertDisplay']:'active');
      echo $Form->text_field('logo', 'Logo URL', isset($details['logo'])?$details['logo']:false, 'm input-simple');
      echo $Form->text_field('logoAlt', 'Logo Alt', isset($details['logoAlt'])?$details['logoAlt']:false, 'm input-simple');
/*
      echo $Form->select_field('displayAd', 'Display Advert', [
        ['label'=>'Yes', 'value'=>'on'], 
        ['label'=>'No', 'value'=>'off']
      ], isset($details['displayAd'])?$details['displayAd']:'active');
      echo $Form->select_field('bannerAd','Banner Advert', [
        ['label'=>'Yes', 'value'=>'on'], 
        ['label'=>'No', 'value'=>'off']
      ], isset($details['bannerAd'])?$details['bannerAd']:'active');
      echo $Form->select_field('bannerAdPos', 'Banner Ad Position', array( array('label'=>'Top', 'value'=>'Top'),array('label'=>'Bottom', 'value'=>'Bottom')), 
                               isset($details['bannerAdPos'])?$details['bannerAdPos']:'active');
*/
            
      echo $HTML->heading2('Office Admin Details');
      echo $Form->select_field('organisationID', 'Office', $OrgOptions, isset($details['organisationID'])?$details['organisationID']:'active'); 
      echo $Form->text_field('organisationName', 'Office Contact Name', isset($details['organisationName'])?$details['organisationName']:false, 'm input-simple');
      echo $Form->text_field('contactName', 'Contact Name', isset($details['contactName'])?$details['contactName']:false, 'm input-simple');
      echo $Form->text_field('contactEmail', 'Contact Email', isset($details['contactEmail'])?$details['contactEmail']:false, 'm input-simple');
      
      echo $HTML->heading2('Advert Admin Details');
      echo $Form->text_field('firstOnline', 'First Online', switchDate($details['firstOnline']), 'm input-simple datepicker');
      echo $Form->text_field('dateUpdated', 'Date Updated', switchDate($details['dateUpdated']), 'm input-simple datepicker');
      echo $Form->text_field('checkDate', 'Check Date', switchDate($details['checkDate']), 'm input-simple datepicker');
      echo $Form->text_field('displayUntil', 'Display Until', switchDate($details['displayUntil']), 'm input-simple datepicker');      
/*
      echo $Form->text_field('advertType', 'Advert Type', isset($details['advertType'])?$details['advertType']:false, 'm input-simple');
      echo $Form->select_field('advertPaid', 'Advert Paid', array(
                array('label'=>'Yes', 'value'=>'Yes'), array('label'=>'No', 'value'=>'No')
           ), isset($details['advertPaid'])?$details['advertPaid']:'active');
      echo $Form->text_field('advertCost', 'Advert Cost', isset($details['advertCost'])?$details['advertCost']:false, 'm input-simple');
      echo $Form->select_field('freeAd', 'Free Advert', [
        ['label'=>'Yes', 'value'=>'on'], 
        ['label'=>'No', 'value'=>'off']
      ], isset($details['freeAd'])?$details['freeAd']:'active');
*/
      
      
      
      echo '</div><div class="right">';
      
      echo $HTML->heading2('Admin');
      echo $Form->select_field('advertStatus', 'Advert Status', 
                               [['label'=>'Pending','value'=>'pen'],['label'=>'Published','value'=>'pub'],['label'=>'Suspended','value'=>'sus']], 
                               isset($details['advertStatus'])?$details['advertStatus']:'active');
                               
      echo $HTML->heading2('Delete');
      echo '<p style="margin-left:36px"><a href="/perch/addons/apps/cjs_workdays/surveys/delete/?id='.$_GET['id'].'">Click Here to Delete</a></p>';
      
      echo '</div></div>';

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/surveys/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();