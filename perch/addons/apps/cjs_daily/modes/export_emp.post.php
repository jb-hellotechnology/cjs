<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Employers'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/employers/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_daily/employers/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_daily/employers/export/',
  ]);


  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here
  echo $Form->form_start();

  // Export Recent Additions or Recent Updates

  echo $Form->submit_field('searchButton', 'Export CSV');

  echo $Form->form_end();


  #Main Panel End

  $HTML->main_panel_end();