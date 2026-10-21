<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Short Courses'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/short_courses/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_training/short_courses/',
  ]);

  $Smartbar->add_item([
        'active' => true,
        'title' => 'Upload CSV',
        'link'  => '/addons/apps/cjs_training/short_courses/upload_csv/',
    ]);

  echo $Smartbar->render();

  # Main panel
  $HTML->main_panel_start();
  
  echo $Form->form_start();
  
  //Choose File Button
  echo $Form->file_field('fileToUpload','Upload CSV');

  // Submit Button
  echo $Form->submit_field('btnSubmit', 'Upload CSV', '');

  echo $Form->form_end();


	$HTML->main_panel_end();