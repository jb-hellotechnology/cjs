<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Month to Month Events and Short Courses'),

    ], $CurrentUser);
        
  $MonthSelect = array();
  $MonthSelect[] = ['label'=>'Undated', 'value'=>'undated'];
  
  $months_advanced = 11; # without counting the current month - so they are 12 in total (one full year)
  for ($i = 0; $i <= $months_advanced; $i++) {
    $Month = date('F Y', mktime(0, 0, 0, date('m')+$i, date('d'), date('Y')));
    $MonthValue = date('Y-m-d', mktime(0, 0, 0, date('m')+$i, date('d'), date('Y')));
    $MonthSelect[] = ['label'=>$Month, 'value'=>$MonthValue];
  }
 // print_r($MonthSelect);
  
  # start main panel

  $HTML->main_panel_start();  

  echo $Form->form_start();

  echo $Form->select_field('startMonth', 'Start Month', $MonthSelect, 'active');

  echo $Form->select_field('endMonth', 'End Month', $MonthSelect, 'active');

  echo $Form->submit_field('showResults', 'Show Results', '');

  echo $Form->form_end();

  if($Form->submitted()) {
    
    $postvars = array('startMonth','endMonth');
    $data = $Form->receive($postvars);
    $eventResults = $Events->monthsEvents($data['startMonth'],$data['endMonth']);

  }



    echo "\n\n<!--Kerryn - copy from here-->\n\n";

    echo "<h2>Events</h2>";

    foreach($eventResults as $Event){
      $dates = explode("-",$Event['startDate']);
      $date = "$dates[2]/$dates[1]/$dates[0]";
      
      if($date=='00/00/0000'){
	      $date = $Event['startText'];
      }
      
      if($Event['numberDays']>1){
        $plural = 's';
      }else{
	      $plural = '';
      }
      if($Event['advertLink']==1){
        $Website = $Event['advertWebsiteShort'];
      }else{
        $Website = $Event['advertWebsite'];
      }
      echo '<p>'.$date.' <strong>'.$Event['eventTitle'].'</strong> '.$Event['numberDays'].' Day'.$plural.'<br />';
      echo ''.$Event['location'].', '.$Event['provider'].'. Contact: '.$Event['advertTelephone'].' <a href="mailto:'.$Event['advertEmail'].'">'.$Event['advertEmail'].'</a> <a href="'.$Website.'">'.$Website.'</a></br />';
      $json = json_decode($Event['eventDynamicFields'],true);
      echo strip_tags($json['description']['raw']);
	  echo '</p>';
    }

    $Categories = [
     ['label'=>'Access and Rights of Way','value'=>'row'],
     ['label'=>'Administrative and Office Skills','value'=>'ad'],
     ['label'=>'Community Engagement and Environmental Education','value'=>'ed'],
     ['label'=>'Country Craft Courses','value'=>'craft'],
     ['label'=>'Countryside Management Techniques','value'=>'cons'],
     ['label'=>'First Aid, Risk Assessment and other Health & Safety Related Courses','value'=>'1aid'],
     ['label'=>'Horticulture and Small Holding','value'=>'hort'],
     ['label'=>'Identification and Field Survey Skills - Herpetology, Fish and Invertebrates','value'=>'invert'],
     ['label'=>'Identification and Field Survey Skills - Mammals','value'=>'mammals'],
     ['label'=>'Identification and Field Survey Skills - Ornithology','value'=>'birds'],
     ['label'=>'Identification and Field Survey Skills - Plants and Habitats','value'=>'plants'],
     ['label'=>'Photography','value'=>'photo'],
     ['label'=>'Practical Countryside Skills','value'=>'skills'],
     ['label'=>'Practical Countryside Skills - Machinery','value'=>'mach']
    ];

    echo "<h2>Short Courses</h2>";

    foreach($Categories as $Category){

      echo "<h4>".$Category['label']."</h4>";
      $shortCourseResults = $ShortCourses->monthsShortCourses($data['startMonth'],$data['endMonth'],$Category['value']);

      foreach($shortCourseResults as $Course){
        $dates = explode("-",$Course['startDate']);
        $date = "$dates[2]/$dates[1]/$dates[0]";
        
        if($date=='00/00/0000'){
		      $date = $Course['startText'];
	      }
        
        if($Course['numberDays']>1){
          $plural = 's';
        }else{
	        $plural = '';
        }
        if($Course['advertLink']==1){
	        $Website = $Course['advertWebsiteShort'];
	      }else{
	        $Website = $Course['advertWebsite'];
	      }
        echo '<p>'.$date.' <strong>'.$Course['shortTitle'].'</strong> '.$Course['numberDays'].' Day'.$plural.'<br />';
        echo $Course['location'].', '.$Course['provider'].'. Contact: '.$Course['advertTelephone'].' <a href="mailto:'.$Course['advertEmail'].'">'.$Course['advertEmail'].'</a> <a href="'.$Website.'">'.$Website.'</a><br />';
        $json = json_decode($Course['shortDynamicFields'],true);
		echo strip_tags($json['description']['raw']);
		echo '</p>';
      }

    }


    echo "\n\n <!--To Here-->\n\n";
    
  
  
	$HTML->main_panel_end();