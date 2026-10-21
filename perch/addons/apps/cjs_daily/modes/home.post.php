<?php
	  echo $HTML->title_panel([
    'heading' => $Lang->get('Home'),
    ], $CurrentUser);
    
    $HTML->main_panel_start();
    
    echo $HTML->heading1("Daily Jobs");
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
                <td class="pending"><a href="<?php echo $HTML->encode($API->app_path()); ?>/daily/add_new?id=<?php echo $HTML->encode(urlencode($Djob['daily_jobID'])); ?>">PENDING</a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/daily/delete/?id=<?php echo $HTML->encode(urlencode($Djob['daily_jobID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
    <?php
    echo $HTML->heading1("Volunteer Roles");
    
    ?>
    
        <table class="d">
        <thead>
            <tr>                
                <th>ID</th>
                <th><?php echo $Lang->get('Title'); ?></th>
                <th><?php echo $Lang->get('Employer'); ?></th>
                <th><?php echo $Lang->get('Location'); ?></th>
                <th><?php echo $Lang->get('Closing Date'); ?></th> 
                <th>View/Edit</th>             
                <th><?php echo $Lang->get('Status'); ?></th>              
                <th> <?php echo $Lang->get('Delete')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($volunteers as $Volunteer) {
?>
            <tr>
                <td>V<?php echo $HTML->encode(urlencode($Volunteer['volunteerID'])); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/add_new?id=<?php echo $HTML->encode(urlencode($Volunteer['volunteerID'])); ?>" class="primary"><?php echo $HTML->encode($Volunteer['title']); ?></a></td>
                <td><?php echo $HTML->encode($Volunteer['employerDisplay']); ?></td>
                <td><?php echo $HTML->encode($Volunteer['location']); ?></td>                
                <td><?php echo $HTML->encode($Volunteer['closingDate']);?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/add_new?id=<?php echo $HTML->encode(urlencode($Volunteer['volunteerID'])); ?>" class="button button-small action-success">View/Edit</a></td>
                <td class="pending"><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/add_new?id=<?php echo $HTML->encode(urlencode($Volunteer['volunteerID'])); ?>">PENDING</a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/delete/?id=<?php echo $HTML->encode(urlencode($Volunteer['volunteerID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
    
    <?php
	  echo $HTML->heading1("Job Advert Stats");
	  $i = 12;
	  while($i>=0){
		  $month = strtolower(date("My", mktime(0, 0, 0, date('m')-$i, date('d'), date('Y'))));
		  $count = $Djobs->get_count_month($month);
		  echo "job/$month -> $count<br />";
		  $i--;
	  }
	  
	  $HTML->main_panel_end();