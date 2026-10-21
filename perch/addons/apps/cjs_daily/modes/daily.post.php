<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Daily Jobs'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/daily/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_daily/daily/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_daily/daily/published',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_daily/daily/suspended',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Export',
      'link' => '/addons/apps/cjs_daily/daily/export/',
  ]);


  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here

  //if (PerchUtil::count($djobs)) {

  echo $Form->form_start();

  function switchDate($input){
      $dates = explode("-",$input);
      return "$dates[2]-$dates[1]-$dates[0]";
    }

?>
    <table class="d">
        <thead>
            <tr>  
                <th class="first"><a href="/perch/addons/apps/cjs_daily/daily/?order=daily_jobID">ID</a></th>
                <th><a href="/perch/addons/apps/cjs_daily/daily/?order=title"><?php echo $Lang->get('Title'); ?></a></th>
                <th><a href="/perch/addons/apps/cjs_daily/daily/?order=employerDisplay"><?php echo $Lang->get('Employer'); ?></a></th>
                <th><a href="/perch/addons/apps/cjs_daily/daily/?order=postedDate"><?php echo $Lang->get('Posted Date'); ?></a></th>
                <th><a href="/perch/addons/apps/cjs_daily/daily/?order=closingDate"><?php echo $Lang->get('Closing Date'); ?></a></th> 
                <th>View/Edit</th>
                <th>Status</th>
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Delete Bulk Select')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($djobs as $Djob) {
?>
            <tr>
                <td>J<?php echo $HTML->encode(urlencode($Djob['daily_jobID'])); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/daily/add_new?id=<?php echo $HTML->encode(urlencode($Djob['daily_jobID'])); ?>"><?php echo $HTML->encode($Djob['title']); ?></a></td>
                <td><?php echo $HTML->encode($Djob['employerDisplay']); ?></td>
                <td><?php echo $HTML->encode(switchDate($Djob['postedDate'])); ?></td>
                <td><?php echo $HTML->encode(switchDate($Djob['closingDate'])); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/daily/add_new?id=<?php echo $HTML->encode(urlencode($Djob['daily_jobID'])); ?>" class="button button-small action-success">View/Edit</a></td>
                <td class="pending"><a href="<?php echo $HTML->encode($API->app_path()); ?>/daily/add_new?id=<?php echo $HTML->encode(urlencode($Djob['daily_jobID'])); ?>">
	                <?php
		                if($Djob['advertStatus']=='pen'){
			                echo 'PENDING';
		                }else{
			                echo 'OVERWRITE';
		                }
		            ?>
                </a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/daily/delete/?id=<?php echo $HTML->encode(urlencode($Djob['daily_jobID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Djob['daily_jobID'], '', '1', false); ?></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
   
    <!-- Submit Button  -->
     <?php  echo $Form->submit_field('btnSubmit', 'Delete All Selected', '');?>


<?php    
    echo $Form->form_end();	       

    //} // if things

  #Main Panel End

  $HTML->main_panel_end();