<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Work Days'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/work_days/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_workdays/work_days/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_workdays/work_days/published/',
  ]);
  
  $Smartbar->add_item([
      'active' => false,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_workdays/work_days/suspended/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_workdays/work_days/archive/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_workdays/work_days/upload_csv/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_workdays/work_days/export/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  if($message){
    echo $message;
  }else{

    //Form Starts
    echo $Form->form_start();

    //Choose File Button
    echo $Form->file_field('fileToUpload','Upload CSV');

    // Submit Button
    echo $Form->submit_field('btnSubmit', 'Upload CSV', '');

    //Form Ends
    echo $Form->form_end();
    
  }


  #Main Panel End

  $HTML->main_panel_end();