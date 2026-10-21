<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);  
*/

	echo $HTML->title_panel([
    'heading' => $Lang->get('Offices'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/offices/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_workdays/offices/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Merge',
      'link'  => '/addons/apps/cjs_workdays/offices/merge/',
  ]);


  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

    if($message){
      
      echo $message;
      
    }else{

      echo $Form->form_start();

      foreach ($organisations as $Organisation){
	      $label = $Organisation->organisationID()." ".$Organisation->name();
        $OrgOptions[] = ['label'=>$label,'value'=>$Organisation->organisationID()];
      } 

      echo $Form->select_field('organisationID', 'Merge This Office', $OrgOptions, isset($_GET['id'])?$_GET['id']:'active');

      echo $Form->select_field('organisationID_2', 'With This One', $OrgOptions);

      echo $Form->submit_field('btnSubmit', 'Merge Selected', '');

      echo $Form->form_end();	
      
    }


  $HTML->main_panel_end();