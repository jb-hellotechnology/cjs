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
      'active' => false,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_workdays/work_days/upload_csv/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_workdays/work_days/export/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

	echo $Form->form_start();

  // Export Work Days by Month
  echo $HTML->heading2('Export Work Days by Month or Export Recently Added');

  echo $Form->select_field('MonthOrRecently', 'Montly Adverts or Recently Added ', array(
            array('label'=>'Monthly Adverts', 'value'=>'monthly'), array('label'=>'Recently Added', 'value'=>'recently')
  ), 'active');

  echo $Form->date_field('checkDateGreater', 'From', false, true);

  echo $Form->date_field('checkDateLess', 'To', false, true);

  echo $Form->select_field('region', 'Region', array(array('label'=>'East Anglia', 'value'=>'East Anglia'), array('label'=>'Midlands', 'value'=>'Midlands'), array('label'=>'North East', 'value'=>'North East'), array('label'=>'North West', 'value'=>'North West'), array('label'=>'South', 'value'=>'South'), array('label'=>'South West', 'value'=>'South West'),  array('label'=>'Scotland', 'value'=>'Scotland'), array('label'=>'Wales', 'value'=>'Wales'), array('label'=>'Northern Ireland', 'value'=>'Northern Ireland'), array('label'=>'National', 'value'=>'National'), array('label'=>'International', 'value'=>'International') ), 
                            'active');

  echo $Form->submit_field('searchButton', 'Search');

  echo $Form->form_end();

  echo $Form->form_start();

  // Export Undated Adverts
  echo $HTML->heading2('Export Undated Work Days');

  echo $Form->select_field('regionUndated', 'Region', array(array('label'=>'East Anglia', 'value'=>'East Anglia'), array('label'=>'Midlands', 'value'=>'Midlands'), array('label'=>'North East', 'value'=>'North East'), array('label'=>'North West', 'value'=>'North West'), array('label'=>'South', 'value'=>'South'), array('label'=>'South West', 'value'=>'South West'),  array('label'=>'Scotland', 'value'=>'Scotland'), array('label'=>'Wales', 'value'=>'Wales'), array('label'=>'Northern Ireland', 'value'=>'Northern Ireland'), array('label'=>'National', 'value'=>'National'), array('label'=>'International', 'value'=>'International') ), 
                            'active');

  echo $Form->submit_field('searchButton', 'Search');

  echo $Form->form_end();


  if($_POST['MonthOrRecently']=='monthly'){
    
    $From = $Form->get_date('checkDateGreater');
    $To = $Form->get_date('checkDateLess');
    $region = $_POST['region'];

    echo "<h2>Results</h2>";

    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startDate>='$From' AND startDate<='$To' ORDER BY startDate ASC";
    $Workdays->export_monthly_workdays($query);

    echo "<h2>Regular Days</h2>";

    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Monday' ORDER BY startDate ASC";
    echo "<h2>Mondays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Tuesday' ORDER BY startDate ASC";
    echo "<h2>Tuesdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Wednesday' ORDER BY startDate ASC";
    echo "<h2>Wednesdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Thursday' ORDER BY startDate ASC";
    echo "<h2>Thursdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Friday' ORDER BY startDate ASC";
    echo "<h2>Fridays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Saturday' ORDER BY startDate ASC";
    echo "<h2>Saturdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Sunday' ORDER BY startDate ASC";
    echo "<h2>Sundays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Various' ORDER BY startDate ASC";
    echo "<h2>Various</h2>";
    $Workdays->export_monthly_workdays($query);
    
  }elseif($_POST['MonthOrRecently']=='recently'){
    
    $From = $Form->get_date('checkDateGreater');
    $To = $Form->get_date('checkDateLess');
    $region = $_POST['region'];
    
    echo "<h2>Results</h2>";

    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    $Workdays->export_monthly_workdays($query);

    echo "<h2>Regular Days</h2>";

    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Monday' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Mondays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Tuesday' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Tuesdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Wednesday' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Wednesdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Thursday' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Thursdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Friday' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Fridays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Saturday' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Saturdays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Sunday' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Sundays</h2>";
    $Workdays->export_monthly_workdays($query);
    
    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startText='Various' AND firstOnline>='$From' AND firstOnline<='$To' ORDER BY startDate ASC";
    echo "<h2>Various</h2>";
    $Workdays->export_monthly_workdays($query);
    
  }elseif($_POST['regionUndated']<>''){
    
    $region = $_POST['regionUndated'];
    
    echo "<h2>Results</h2>";

    $query = "SELECT * FROM perch3_workdays WHERE region='$region' AND advertStatus='Published' AND startDate='0000-00-00' ORDER BY workdayID ASC";
    $Workdays->export_monthly_workdays($query);
    
  }

  #Main Panel End

  $HTML->main_panel_end();