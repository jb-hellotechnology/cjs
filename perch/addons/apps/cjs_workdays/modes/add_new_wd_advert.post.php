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
      foreach ($organisations as $Organisation){
        $OrgOptions[] = ['label'=>$Organisation->name()." (".$Organisation->organisationID().")",'value'=>$Organisation->organisationID()];
      }  
      
	    echo $Form->form_start();
	    
	    echo '<div class="form-grid"><div class="left">';

      // THIS IS A FIELD CREATED HERE
      echo $Form->text_field('Title', 'Title', isset($details['Title'])?$details['Title']:false, 'm input-simple');

      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Work Day Details');	
      
      // MORE FIELDS
      echo $Form->text_field('startDate', 'Start Date', switchDate($details['startDate']), 'm input-simple datepicker');
      echo $Form->text_field('startText', 'Start Text', isset($details['startText'])?$details['startText']:false, 'm input-simple');
      
      echo '<p>Or click: <a href="javascript:setStartText(\'Various\');">Various</a> &bull; <a href="javascript:setStartText(\'Monday\');">Monday</a> &bull; 
      <a href="javascript:setStartText(\'Tuesday\');">Tuesday</a> &bull; <a href="javascript:setStartText(\'Wednesday\');">Wednesday</a> &bull; <a href="javascript:setStartText(\'Thursday\');">Thursday</a> &bull; 
      <a href="javascript:setStartText(\'Friday\');">Friday</a> &bull; <a href="javascript:setStartText(\'Saturday\');">Saturday</a> &bull; <a href="javascript:setStartText(\'Sunday\');">Sunday</a></p>
      <script>
      function setStartText(word){
        $("#startText").val(word);
      }
      </script>';
      
      echo $Form->text_field('displayDate', 'Display Date', isset($details['displayDate'])?$details['displayDate']:false, 'm input-simple');
      echo $Form->select_field('region', 'Region', array(array('label'=>'East Anglia', 'value'=>'East Anglia'), array('label'=>'Midlands', 'value'=>'Midlands'), array('label'=>'North East', 'value'=>'North East'), array('label'=>'North West', 'value'=>'North West'), array('label'=>'South', 'value'=>'South'), array('label'=>'South West', 'value'=>'South West'),  array('label'=>'Scotland', 'value'=>'Scotland'), array('label'=>'Wales', 'value'=>'Wales'), array('label'=>'Northern Ireland', 'value'=>'Northern Ireland'), array('label'=>'National', 'value'=>'National'), array('label'=>'International', 'value'=>'International') ), 
                               isset($details['region'])?$details['region']:'active');
      echo $Form->text_field('location', 'Location', isset($details['location'])?$details['location']:false, 'm input-simple');
      echo $Form->text_field('provider', 'Provider', isset($details['provider'])?$details['provider']:false, 'm input-simple');
      echo $Form->text_field('advertTelephone', 'Advert Telephone', isset($details['advertTelephone'])?$details['advertTelephone']:false, 'm input-simple');
      echo $Form->text_field('advertEmail', 'Advert Email', isset($details['advertEmail'])?$details['advertEmail']:false, 'm input-simple');
      echo $Form->text_field('advertEmailSub', 'Advert Email Subject', isset($details['advertEmailSub'])?$details['advertEmailSub']:false, 'm input-simple');
      echo $Form->text_field('advertWebsite', 'Advert Website (Long)', isset($details['advertWebsite'])?$details['advertWebsite']:false, 'm input-simple');
      echo $Form->text_field('advertWebsiteShort', 'Advert Website (Short)', isset($details['advertWebsiteShort'])?$details['advertWebsiteShort']:false, 'm input-simple');      
      echo $Form->select_field('advertDisplay', 'Advert Link Display', array(
                array('label'=>'Long', 'value'=>'l'), array('label'=>'Short', 'value'=>'s')
      ), isset($details['advertDisplay'])?$details['advertDisplay']:'active');
      echo $Form->text_field('logo', 'Logo URL', isset($details['logo'])?$details['logo']:false, 'm input-simple');
      echo $Form->text_field('logoAlt', 'Logo Alt', isset($details['logoAlt'])?$details['logoAlt']:false, 'm input-simple');
      echo $Form->checkbox_field('displayAd', 'Display Advert', '1', isset($details['displayAd'])?$details['displayAd']:false);
      echo $Form->checkbox_field('bannerAd', 'Banner Advert', '1', isset($details['bannerAd'])?$details['bannerAd']:false); 
      echo $Form->select_field('bannerAdPos', 'Banner Ad Position', array( array('label'=>'Top', 'value'=>'Top'),array('label'=>'Bottom', 'value'=>'Bottom')), 
                               isset($details['bannerAdPos'])?$details['bannerAdPos']:'active');
            
      echo $HTML->heading2('Organisation Admin Details');
      echo $Form->select_field('organisationID', 'Organisation', $OrgOptions, isset($details['organisationID'])?$details['organisationID']:'active');
      echo $Form->text_field('organisationName', 'Organisation Contact Name', isset($details['organisationName'])?$details['organisationName']:false, 'm input-simple');
      echo $Form->text_field('contactName', 'Contact Name', isset($details['contactName'])?$details['contactName']:false, 'm input-simple');
      echo $Form->text_field('contactTelephone', 'Contact Telephone', isset($details['contactTelephone'])?$details['contactTelephone']:false, 'm input-simple');
      echo $Form->text_field('contactEmail', 'Contact Email', isset($details['contactEmail'])?$details['contactEmail']:false, 'm input-simple');
      
      echo $HTML->heading2('Advert Admin Details');
      echo $Form->text_field('firstOnline', 'First Online', switchDate($details['firstOnline']), 'm input-simple datepicker');
      echo $Form->text_field('checkDate', 'Check Date', switchDate($details['checkDate']), 'm input-simple datepicker');
      echo $Form->text_field('dateUpdated', 'Date Updated', switchDate($details['dateUpdated']), 'm input-simple datepicker');            
      echo $Form->text_field('advertType', 'Advert Type', isset($details['advertType'])?$details['advertType']:false, 'm input-simple');
      echo $Form->checkbox_field('advertPaid', 'Advert Paid?', '1', isset($details['advertPaid'])?$details['advertPaid']:false);
      echo $Form->text_field('advertCost', 'Advert Cost', isset($details['advertCost'])?$details['advertCost']:false, 'm input-simple');

      
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Workdays->static_fields);
      
      echo '</div><div class="right">';
      
      echo $HTML->heading2('Admin');
      echo $Form->select_field('advertStatus', 'Advert Status', 
                               [['label'=>'Pending','value'=>'pen'],['label'=>'Published','value'=>'pub'],['label'=>'Suspended','value'=>'sus']], 
                               isset($details['advertStatus'])?$details['advertStatus']:'active');
      
      echo '</div></div>';

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/work_days/");

      echo $Form->form_end();	 
      
      if($_GET['id']){
        echo $HTML->heading2('Duplicate Record');
        echo $Form->form_start();
        
        echo $Form->hidden('duplicate', 'yes');
        echo $Form->submit_field('btnSubmit', 'Duplicate Record', $API->app_path()."/work_days/");

        echo $Form->form_end();	
      }
    
    }
    
    echo $HTML->main_panel_end();