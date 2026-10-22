<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Long Courses'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/long_courses/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_training/long_courses/',
  ]);

  $Smartbar->add_item([
        'active' => true,
        'title' => 'Upload CSV',
        'link'  => '/addons/apps/cjs_training/long_courses/upload_csv/',
    ]);

  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

  if($message){
    echo $message;
  }else{

    echo '<p style="margin-left:36px">Your CSV needs its columns in the order the importer expects. <a href="'.$API->app_path().'/long_courses/upload_csv/template.php">Download a blank CSV template</a> to start from - fill in the rows underneath and leave its header row in place.</p>';

    echo $Form->form_start();

    //Choose File Button
    echo $Form->file_field('fileToUpload','Upload CSV');

    // Submit Button
    echo $Form->submit_field('btnSubmit', 'Upload CSV', '');

    echo $Form->form_end();
    
  }

	$HTML->main_panel_end();