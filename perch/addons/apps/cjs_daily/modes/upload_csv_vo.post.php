<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Voluntary Organisations'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/voluntary_organisations/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/published/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/archive/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/upload_csv/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  //Form Starts
  echo $Form->form_start();
  
  //Choose File Button
  echo $Form->file_field('fileToUpload','Upload CSV');

  // Submit Button
  echo $Form->submit_field('btnSubmit', 'Upload CSV', '');

  //Form Ends
  echo $Form->form_end();

  #Main Panel End

  $HTML->main_panel_end();