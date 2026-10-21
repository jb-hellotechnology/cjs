<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Surveys'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/surveys/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_workdays/surveys/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_workdays/surveys/published/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_workdays/surveys/archive/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_workdays/surveys/upload_csv/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_workdays/surveys/export/',
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