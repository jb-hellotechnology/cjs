<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Recent Additions and Updates'),

    ], $CurrentUser);
    
  # Call Smartbar to create the tabs
  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Recent Additions',
      'link'  => '/addons/apps/cjs_training/updates_additions/',
  ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Recent Updates',
        'link'  => '/addons/apps/cjs_training/updates_additions/updates/',
    ]);

  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

  echo $Form->form_start();

  echo $Form->date_field('startDate', 'From Date', false, true);

  echo $Form->date_field('endDate', 'To Date', false, true);

  echo $Form->submit_field('btnSubmit', 'Search', '');

  echo $Form->form_end();

  if($show==1){
    echo "<h3>Short Courses</h3>
        <table>
          <tr><th>Title</th><th>Record</th><th>Start Date</th><th>Provider</th></tr>";

      foreach($shortCourseResults as $ShortCourse){
	      $dates = explode("-",$ShortCourse['startDate']);
	    $start = "$dates[2]/$dates[1]/$dates[0]";
        echo "<tr><td>$ShortCourse[shortTitle]</td><td>S$ShortCourse[shortID]</td><td>$start</td><td>$ShortCourse[provider]</td></tr>";
      }

    echo "</table>";

    echo "<h3>Long Courses</h3>
        <table>
          <tr><th>Section</th><th>Title</th><th>Provider</th></tr>";

    foreach($longCourseResults as $LongCourse){
        echo "<tr><td>$LongCourse[categoryOne]</td><td>$LongCourse[longTitle]</td><td>$LongCourse[provider]</td></tr>";
      }

    echo "</table>";

    echo "<h3>Providers</h3>";

    foreach($providerResults as $Provider){
        echo "<p>$Provider[providerName]</p>";
      }

    echo "<h3>Events</h3>
        <table>
          <tr><th>Title</th><th>Start Date</th><th>Provider</th></tr>";

    foreach($eventResults as $Event){
	    $dates = explode("-",$Event['startDate']);
	    $start = "$dates[2]/$dates[1]/$dates[0]";
        echo "<tr><td>$Event[eventTitle]</td><td>$start</td><td>$Event[provider]</td></tr>";
      }

    echo "</table>";
    
  }
    
 	$HTML->main_panel_end();