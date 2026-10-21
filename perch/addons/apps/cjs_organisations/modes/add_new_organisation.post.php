<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
//    include('_subnav.php');

    // DISPLAY THE HEADING (EDIT/NEW)
    echo $HTML->heading1($heading1);

    // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }else{
 
      // NO MESSAGE - DISPLAY FORM
	    echo $Form->form_start();

      // CREATE ALL STATIC FIELDS
      echo $Form->text_field('name', 'Name', isset($details['name'])?$details['name']:false, 'm input-simple');
      echo $Form->text_field('slug', 'Slug', isset($details['slug'])?$details['slug']:false, 'm input-simple');
      // MORE FIELDS
      echo $Form->text_field('nameTag', 'Name Tag (like NYMNP)', isset($details['nameTag'])?$details['nameTag']:false, 'm input-simple');
      echo $Form->text_field('websiteURL', 'Website URL', isset($details['websiteURL'])?$details['websiteURL']:false, 'm input-simple');
      echo $Form->text_field('logoURL', 'Logo URL', isset($details['logoURL'])?$details['logoURL']:false, 'm input-simple');
	  echo $Form->checkbox_field('doNotDisplay', 'Do Not Display', '1', isset($details['doNotDisplay'])?$details['doNotDisplay']:false);
	  
	  // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Organisations->static_fields);

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/organisations/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();