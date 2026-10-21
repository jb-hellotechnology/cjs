<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
//     include('_subnav.php');

 // DISPLAY THE HEADING (EDIT/NEW)
echo $HTML->title_panel([
 'heading' => $Lang->get($heading1),
 'button'  => [
       'text' => $Lang->get('New Email'),
       'link' => $API->app_nav().'/email/add_new/?templateID='.$_GET['id'],
       'icon' => 'core/plus',
   ],
 ], $CurrentUser);

 $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Emails',
      'link'  => '/addons/apps/cjs_daily/email?id='.$_GET['id'],
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Templates',
      'link'  => '/addons/apps/cjs_daily/email/templates/',
  ]);


  echo $Smartbar->render();

     // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }

	    echo $Form->form_start();

      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Template Details');
      
      // THIS IS A FIELD CREATED HERE
      echo $Form->text_field('name', 'Name', isset($details['name'])?$details['name']:false, 'm input-simple');
      
      echo "<br /><br /><h2>Template Content</h2>";
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Templates->static_fields);

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/email/templates/");

      echo $Form->form_end();	    
    
    
    
    echo $HTML->main_panel_end();