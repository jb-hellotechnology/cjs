<?php
	  echo $HTML->title_panel([
    'heading' => $Lang->get('Home'),
    ], $CurrentUser);
    
    $HTML->main_panel_start();

    echo $Form->form_start();
    
    echo $HTML->heading1('Short Courses');

?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('ID'); ?></th>  
                <th><?php echo $Lang->get('Org. ID'); ?></th>                
                <th><?php echo $Lang->get('Title'); ?></th>
                <th><?php echo $Lang->get('Provider'); ?></th>
                <th><?php echo $Lang->get('Start'); ?></th>
                <th><?php echo $Lang->get('Category'); ?></th>
                <th><?php echo $Lang->get('Location'); ?></th>
                <th>View/Edit</th>
                <th><?php echo $Lang->get('Status'); ?></th>
                <th> <?php echo $Lang->get('Delete')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($scourses as $Scourse) {
?>
            <tr>
                <td><?php echo $HTML->encode($Scourse['shortID']); ?></td>
                <td><?php echo $HTML->encode($Scourse['organisationID']); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/short_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Scourse['shortID'])); ?>"><?php echo $HTML->encode($Scourse['shortTitle']); ?></a></td>
                <td><?php echo $HTML->encode($Scourse['provider']); ?></td>
                <td><?php if($Scourse['startText']<>''){echo $HTML->encode($Scourse['startText']);}else{echo $HTML->encode($Scourse['startDate']);} ?></td>
                <td><?php echo $HTML->encode($Scourse['categoryOne']); ?></td>
                <td><?php echo $HTML->encode($Scourse['location']); ?></td>
                <td><a class="button button-small action-success" href="<?php echo $HTML->encode($API->app_path()); ?>/short_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Scourse['shortID'])); ?>">View/Edit</a></td>
                <td class="pending">PENDING</td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/short_courses/delete/?id=<?php echo $HTML->encode(urlencode($Scourse['shortID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
<?php
	echo $HTML->heading1('Long Courses');
	?>
	
	    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('ID'); ?></th>  
                <th><?php echo $Lang->get('Org. ID'); ?></th>                             
                <th><?php echo $Lang->get('Title'); ?></th>
                <th><?php echo $Lang->get('Provider'); ?></th>
                <th><?php echo $Lang->get('Location'); ?></th>
                <th><?php echo $Lang->get('Check Date'); ?></th>
                <th>View/Edit</th>
                <th><?php echo $Lang->get('Status'); ?></th>
                <th> <?php echo $Lang->get('Delete')?></th>              
            </tr>
        </thead>
        <tbody>
<?php
    foreach($lcourses as $Lcourse) {
?>
            <tr>
                <td>L<?php echo $HTML->encode($Lcourse['longID']); ?></td>
                <td><?php echo $HTML->encode($Lcourse['organisationID']); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>"><?php echo $HTML->encode($Lcourse['longTitle']); ?></a></td>
                <td><?php echo $HTML->encode($Lcourse['provider']); ?></td>
                <td><?php echo $HTML->encode($Lcourse['location']); ?></td>
                <td><?php echo $HTML->encode($Lcourse['checkDate']); ?></td>
                <th><a href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>" class="button button-small action-success">View/Edit</a>
                <td class="pending">PENDING</td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/delete/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
<?php
	echo $HTML->heading1('Distance Courses');
	?>
	
	    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('ID'); ?></th>  
                <th><?php echo $Lang->get('Org. ID'); ?></th>                             
                <th><?php echo $Lang->get('Title'); ?></th>
                <th><?php echo $Lang->get('Provider'); ?></th>
                <th><?php echo $Lang->get('Location'); ?></th>
                <th><?php echo $Lang->get('Check Date'); ?></th>
                <th>View/Edit</th>
                <th><?php echo $Lang->get('Status'); ?></th>
                <th> <?php echo $Lang->get('Delete')?></th>              
            </tr>
        </thead>
        <tbody>
<?php
    foreach($dlcourses as $Lcourse) {
?>
            <tr>
                <td>L<?php echo $HTML->encode($Lcourse['longID']); ?></td>
                <td><?php echo $HTML->encode($Lcourse['organisationID']); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>"><?php echo $HTML->encode($Lcourse['longTitle']); ?></a></td>
                <td><?php echo $HTML->encode($Lcourse['provider']); ?></td>
                <td><?php echo $HTML->encode($Lcourse['location']); ?></td>
                <td><?php echo $HTML->encode($Lcourse['checkDate']); ?></td>
                <th><a href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>" class="button button-small action-success">View/Edit</a>
                <td class="pending">PENDING</td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/delete/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
    
    
    <?php
	    echo $HTML->heading1('Events');
	    ?>
	   
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('ID'); ?></th>  
                <th><?php echo $Lang->get('Org. ID'); ?></th>                
                <th><?php echo $Lang->get('Title'); ?></th>
                <th><?php echo $Lang->get('Provider'); ?></th>          
                <th><?php echo $Lang->get('Start'); ?></th>
                <th><?php echo $Lang->get('Location'); ?></th>
                <th>View/Edit</th>
                <th><?php echo $Lang->get('Status'); ?></th>
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Bulk Update')?></th>              
            </tr>
        </thead>
        <tbody>
<?php
    foreach($events as $Event) {
	    $dates = explode("-",$Event['startDate']);
		$startDate = "$dates[2]-$dates[1]-$dates[0]";
?>
            <tr>
                <td>E<?php echo $HTML->encode($Event['eventID']); ?></td>
                <td><?php echo $HTML->encode($Event['organisationID']); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/events/add_new/?id=<?php echo $HTML->encode(urlencode($Event['eventID'])); ?>"><?php echo $HTML->encode($Event['eventTitle']); ?></a></td>
                <td><?php echo $HTML->encode($Event['provider']); ?></td>  
                <td><?php echo $startDate; ?></td>           
                <td><?php echo $HTML->encode($Event['location']); ?></td>
                <td><a class="button button-small action-success" href="<?php echo $HTML->encode($API->app_path()); ?>/events/add_new/?id=<?php echo $HTML->encode(urlencode($Event['eventID'])); ?>">View/Edit</a></td>
                <td class="pending">PENDING</td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/events/delete/?id=<?php echo $HTML->encode(urlencode($Event['eventID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Event['eventID'], '', '1', false); ?></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>

    <?php
	  $HTML->main_panel_end();
	  
	  ?>