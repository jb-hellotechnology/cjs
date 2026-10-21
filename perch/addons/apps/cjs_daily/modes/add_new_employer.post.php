<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
//     include('_subnav.php');

     // DISPLAY THE HEADING (EDIT/NEW)
    echo $HTML->heading1($heading1.' - #'.$_GET['id']);

     // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }else{
		  
		  $organisations = $Organisations->all();
      $OrgOptions = array();
      $OrgOptions[] = ['label'=>'Please Select','value'=>''];
      foreach ($organisations as $Organisation){
        $OrgOptions[] = ['label'=>$Organisation->name()." (".$Organisation->organisationID().")",'value'=>$Organisation->organisationID()];              
      }  

	    echo $Form->form_start();

      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Employer Details Details');
      
      // THIS IS A FIELD CREATED HERE
      echo $Form->text_field('name', 'Employer Name', isset($details['name'])?$details['name']:false, 'm input-simple');

      // MORE FIELDS
      echo $Form->text_field('slug', 'Employer Slug', isset($details['slug'])?$details['slug']:false, 'm input-simple');  
      echo $Form->select_field('cjsOrganisationID', 'Organisation', $OrgOptions, isset($details['organisationID'])?$details['organisationID']:'active');  
      echo $Form->text_field('logo', 'Image/Logo', isset($details['logo'])?$details['logo']:false, 'm input-simple');
      echo $Form->text_field('contactName', 'Contact Name', isset($details['contactName'])?$details['contactName']:false, 'm input-simple');
      echo $Form->text_field('contactJobTitle', 'Contact Job Title', isset($details['contactJobTitle'])?$details['contactJobTitle']:false, 'm input-simple');
      echo $Form->text_field('contactPhone', 'Contact Telephone', isset($details['contactPhone'])?$details['contactPhone']:false, 'm input-simple');
      echo $Form->text_field('contactEmail', 'Contact Email', isset($details['contactEmail'])?$details['contactEmail']:false, 'm input-simple');  
      echo $Form->checkbox_field('doNotContact', 'Do Not Contact', '1', isset($details['doNotContact'])?$details['doNotContact']:'1');
      
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Employers->static_fields);

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/employers/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();