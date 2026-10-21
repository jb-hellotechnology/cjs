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
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_workdays/surveys/suspended/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_workdays/surveys/archive/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_workdays/surveys/upload_csv/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_workdays/surveys/export/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here
  echo $Form->form_start();

  // Export Recent Additions or Recent Updates
  echo $HTML->heading2('Export Surveys');

  echo $Form->date_field('checkDateGreater', 'From', false, true);

  echo $Form->date_field('checkDateLess', 'To', false, true);

  echo $Form->submit_field('searchButton', 'Search');

  echo $Form->form_end();

  if($_POST){
    
    $From = $Form->get_date('checkDateGreater');
    $To = $Form->get_date('checkDateLess');
    
    $array = ['general','birds','mammals','herpetology','plants','fish','invertebrates'];
    
    foreach($array as $section){
      
      echo "<h2>".ucwords($section)."</h2>";
      
      $query = "SELECT * FROM perch3_surveys WHERE dateUpdated>='$From' AND dateUpdated<='$To' AND section='$section' AND advertStatus='pub' ORDER BY dateUpdated DESC";
      $Surveys->export_surveys($query);
      
    }
    
  }
  

  #Main Panel End

  $HTML->main_panel_end();