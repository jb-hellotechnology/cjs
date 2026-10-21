<?php
	  echo $HTML->title_panel([
    'heading' => $Lang->get('Home'),
    ], $CurrentUser);
    
    $HTML->main_panel_start();
    
    echo $HTML->heading1('Voluntary Organisations');
    
    ?>
    
        <table class="d">
        <thead>
            <tr>                
                <th><?php echo $Lang->get('ID'); ?></th>
                <th class="first"><?php echo $Lang->get('Provider'); ?></th>
                <th><?php echo $Lang->get('Advert'); ?></th>
                <th>View/Edit</th>
                <th><?php echo $Lang->get('Status'); ?></th>              
                <th> <?php echo $Lang->get('Delete')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($vorganisations_wd as $VOrganisation_wd) {
?>
            <tr>
                <td>V<?php echo $HTML->encode($VOrganisation_wd['vorganisationID']); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/add_new?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd['vorganisationID'])); ?>" class="primary"><?php echo $HTML->encode($VOrganisation_wd['vorgName']); ?></a></td>
                <?php $description = PerchUtil::json_safe_decode($VOrganisation_wd['vorganisationDynamicFields'], true);?>
                <td><?php echo $description['description']['processed'] ; ?></td>              
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/add_new?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd['vorganisationID'])); ?>" 
	                class="button button-small action-success">View/Edit</a></td>
                <td class="pending"><a href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/add_new?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd['vorganisationID'])); ?>">PENDING</a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/delete/?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd['vorganisationID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
    <?php
	    
	    echo $HTML->heading1('Surveys');
	    ?>
    
        <table class="d">
        <thead>
            <tr>                
                <th class="first"><?php echo $Lang->get('ID'); ?></th>
                <th><?php echo $Lang->get('Org. ID'); ?></th>
                <th><?php echo $Lang->get('Title'); ?></th>
                <th><?php echo $Lang->get('Provider'); ?></th>
                <th><?php echo $Lang->get('Category'); ?></th>
                <th><?php echo $Lang->get('Check Date'); ?></th>
                <th>View/Edit</th>              
                <th><?php echo $Lang->get('Status'); ?></th>              
                <th> <?php echo $Lang->get('Delete')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($surveys as $Survey) {
?>
            <tr>
                <td>S<?php echo $HTML->encode($Survey['surveyID']); ?></td>
                <td><?php echo $HTML->encode($Survey['organisationID']); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/add_new?id=<?php echo $HTML->encode(urlencode($Survey['surveyID'])); ?>"><?php echo $HTML->encode($Survey['Title']); ?></a></td>
                <td><?php echo $HTML->encode($Survey['provider']);?></td>
                <td><?php echo $HTML->encode($Survey['section']); ?></td>
                <td><?php echo $HTML->encode(switchDate($Survey['checkDate'])); ?></td>  
                <td><a class="button button-small action-success" href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/add_new?id=<?php echo $HTML->encode(urlencode($Survey['surveyID'])); ?>">View/Edit</a></td>            
                <td class="pending">PENDING</td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/delete/?id=<?php echo $HTML->encode(urlencode($Survey['surveyID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
    <?php
    
	$HTML->main_panel_end();