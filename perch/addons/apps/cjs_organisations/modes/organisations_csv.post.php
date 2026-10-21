<?php

	echo $HTML->title_panel([
    'heading' => $Lang->get('Organisations'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/organisations/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_organisations/organisations/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Merge',
      'link'  => '/addons/apps/cjs_organisations/organisations/merge/',
  ]);
  
  $Smartbar->add_item([
      'active' => true,
      'title' => 'Contacts CSV',
      'link'  => '/addons/apps/cjs_organisations/organisations/csv/',
  ]);


  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

    if($message){
      
      echo $message;
      
    }else{

      echo $Form->form_start();

      echo $Form->submit_field('btnSubmit', 'Download CSV', '');

      echo $Form->form_end();	
      
    }


  $HTML->main_panel_end();