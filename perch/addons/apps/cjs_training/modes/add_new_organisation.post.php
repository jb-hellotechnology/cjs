<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
//    include('_subnav.php');

    // DISPLAY THE HEADING (EDIT/NEW)
    echo $HTML->heading1($heading1);

    // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }else{
		  
		   $organisations = $master_Organisations->all();
      $OrgOptions = array();
      $OrgOptions[] = ['label'=>'Please Select','value'=>''];
      foreach ($organisations as $Organisation){
        $OrgOptions[] = ['label'=>$Organisation->name()." (".$Organisation->organisationID().")",'value'=>$Organisation->organisationID()];            
      }  

    
      // NO MESSAGE - DISPLAY FORM
	    echo $Form->form_start();

      // CREATE ALL STATIC FIELDS
      echo $Form->text_field('name', 'Name', isset($details['name'])?$details['name']:false, 'm input-simple');
      echo $Form->select_field('master_organisationID', 'Master Organisation', $OrgOptions, isset($details['master_organisationID'])?$details['master_organisationID']:'active'); 
      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Contact Details');
      // MORE FIELDS
      echo $Form->text_field('contactName', 'Contact Name', isset($details['contactName'])?$details['contactName']:false, 'm input-simple');
      echo $Form->text_field('contactPhone', 'Contact Telephone', isset($details['contactPhone'])?$details['contactPhone']:false, 'm input-simple');
      echo $Form->text_field('contactEmail', 'Contact Email', isset($details['contactEmail'])?$details['contactEmail']:false, 'm input-simple');
      echo $Form->checkbox_field('doNotContact', 'Do Not Contact', '1', isset($details['doNotContact'])?$details['doNotContact']:false);

      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Organisations->static_fields);

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/office/");

      echo $Form->form_end();	
      
        if($organisationID AND !$message){

          // Tick the rows to remove, then Delete Selected. One form per table, so a button
          // only ever acts on the rows above it.
          $BulkForms = array();
          foreach (array('providers','scourses','events','lcourses','dcourses') as $bulk_key) {
            $BulkForms[$bulk_key] = $API->get('Form');
            $BulkForms[$bulk_key]->set_name('bulk_delete_'.$bulk_key);
          }

          if ($bulk_delete_message) echo $bulk_delete_message;
	?>
		<h2>Providers</h2>
		<?php echo $BulkForms['providers']->form_start(false, 'bulk-delete-form'); ?>
		<table class="d">
		<thead>
		<tr>
		    <th class="first"><?php echo $Lang->get('ID'); ?></th>
		    <th><?php echo $Lang->get('Provider Name'); ?></th>
		    <th><?php echo $Lang->get('Description'); ?></th>
		    <th>View/Edit</th>
		    <th><?php echo $Lang->get('Status'); ?></th>
		    <th> <?php echo $Lang->get('Delete')?></th>
		    <th class="action last"><?php echo $Lang->get('Bulk Delete')?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($providers as $Provider) {
		?>
		<tr>                
		    <td>P<?php echo $HTML->encode($Provider['providerID']); ?></td>
		    <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/providers/add_new/?id=<?php echo $HTML->encode(urlencode($Provider['providerID'])); ?>"><?php echo $HTML->encode($Provider['providerName']); ?></a></td>
		    <?php $description = PerchUtil::json_safe_decode($Provider['providerDynamicFields'], true);?>
		    <td><?php echo strip_tags($description['description']['processed']); ?></td>
		    <td><a class="button button-small action-success" href="<?php echo $HTML->encode($API->app_path()); ?>/providers/add_new/?id=<?php echo $HTML->encode(urlencode($Provider['providerID'])); ?>">View/Edit</a></td>
		    <td class="<?php if($Provider['advertStatus']=='pub'){echo 'published';}elseif($Provider['advertStatus']=='sus'){echo 'suspended';}else{echo 'pending';} ?>"><?php if($Provider['advertStatus']=='pub'){echo 'PUBLISHED';}elseif($Provider['advertStatus']=='sus'){echo 'SUSPENDED';}else{echo 'PENDING';} ?></td>
		    <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/providers/delete/?id=<?php echo $HTML->encode(urlencode($Provider['providerID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
		    <td><?php echo $BulkForms['providers']->checkbox_field('select_providers_'.$Provider['providerID'], '', '1', false); ?></td>
		</tr>
		<?php
		}
		?>
		</tbody>
		</table>
		<?php
		echo $BulkForms['providers']->submit_field('btnBulkDeleteProviders', 'Delete Selected Providers', false, 'button action-alert');
		echo $BulkForms['providers']->form_end();
		?>

		<h2>Short Courses</h2>
		<?php echo $BulkForms['scourses']->form_start(false, 'bulk-delete-form'); ?>
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
		        <th class="action last"><?php echo $Lang->get('Bulk Delete')?></th>
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
		        <td class="<?php if($Scourse['advertStatus']=='pub'){echo 'published';}elseif($Scourse['advertStatus']=='sus'){echo 'suspended';}else{echo 'pending';} ?>"><?php if($Scourse['advertStatus']=='pub'){echo 'PUBLISHED';}elseif($Scourse['advertStatus']=='sus'){echo 'SUSPENDED';}else{echo 'PENDING';} ?></td>
		        <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/short_courses/delete/?id=<?php echo $HTML->encode(urlencode($Scourse['shortID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
		        <td><?php echo $BulkForms['scourses']->checkbox_field('select_scourses_'.$Scourse['shortID'], '', '1', false); ?></td>
		    </tr>
		<?php
		}
		?>
		</tbody>
		</table>
		<?php
		echo $BulkForms['scourses']->submit_field('btnBulkDeleteScourses', 'Delete Selected Short Courses', false, 'button action-alert');
		echo $BulkForms['scourses']->form_end();
		?>

		<h2>Events</h2>
		<?php echo $BulkForms['events']->form_start(false, 'bulk-delete-form'); ?>
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
	                <th class="action last"><?php echo $Lang->get('Bulk Delete')?></th>
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
	                <td class="<?php if($Event['advertStatus']=='pub'){echo 'published';}elseif($Event['advertStatus']=='sus'){echo 'suspended';}else{echo 'pending';} ?>"><?php if($Event['advertStatus']=='pub'){echo 'PUBLISHED';}elseif($Event['advertStatus']=='sus'){echo 'SUSPENDED';}else{echo 'PENDING';} ?></td>
	                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/events/delete/?id=<?php echo $HTML->encode(urlencode($Event['eventID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
	                <td><?php echo $BulkForms['events']->checkbox_field('select_events_'.$Event['eventID'], '', '1', false); ?></td>
	            </tr>
	<?php
	    }
	?>
	        </tbody>
	    </table>
	    <?php
	    echo $BulkForms['events']->submit_field('btnBulkDeleteEvents', 'Delete Selected Events', false, 'button action-alert');
	    echo $BulkForms['events']->form_end();
	    ?>

		<h2>Long Courses</h2>
		<?php echo $BulkForms['lcourses']->form_start(false, 'bulk-delete-form'); ?>
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
	                <th class="action last"><?php echo $Lang->get('Bulk Delete')?></th>                
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
	                <td class="<?php if($Lcourse['advertStatus']=='pub'){echo 'published';}elseif($Lcourse['advertStatus']=='sus'){echo 'suspended';}else{echo 'pending';} ?>"><?php if($Lcourse['advertStatus']=='pub'){echo 'PUBLISHED';}elseif($Lcourse['advertStatus']=='sus'){echo 'SUSPENDED';}else{echo 'PENDING';} ?></td>
	                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/delete/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
	                <td><?php echo $BulkForms['lcourses']->checkbox_field('select_lcourses_'.$Lcourse['longID'], '', '1', false); ?></td>
	            </tr>
	<?php   
	    }
	?>
	        </tbody>
	    </table>
	    <?php
	    echo $BulkForms['lcourses']->submit_field('btnBulkDeleteLcourses', 'Delete Selected Long Courses', false, 'button action-alert');
	    echo $BulkForms['lcourses']->form_end();
	    ?>
		
		<h2>Distance Courses</h2>
		<?php echo $BulkForms['dcourses']->form_start(false, 'bulk-delete-form'); ?>
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
	                <th class="action last"><?php echo $Lang->get('Bulk Delete')?></th>                
	            </tr>
	        </thead>
	        <tbody>
	<?php
	    foreach($dcourses as $Lcourse) {
	?>
	            <tr>
	                <td>D<?php echo $HTML->encode($Lcourse['longID']); ?></td>
	                <td><?php echo $HTML->encode($Lcourse['organisationID']); ?></td>
	                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>"><?php echo $HTML->encode($Lcourse['longTitle']); ?></a></td>
	                <td><?php echo $HTML->encode($Lcourse['provider']); ?></td>
	                <td><?php echo $HTML->encode($Lcourse['location']); ?></td>
	                <td><?php echo $HTML->encode($Lcourse['checkDate']); ?></td>
	                <th><a href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/add_new/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>" class="button button-small action-success">View/Edit</a>
	                <td class="<?php if($Lcourse['advertStatus']=='pub'){echo 'published';}elseif($Lcourse['advertStatus']=='sus'){echo 'suspended';}else{echo 'pending';} ?>"><?php if($Lcourse['advertStatus']=='pub'){echo 'PUBLISHED';}elseif($Lcourse['advertStatus']=='sus'){echo 'SUSPENDED';}else{echo 'PENDING';} ?></td>
	                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/long_courses/delete/?id=<?php echo $HTML->encode(urlencode($Lcourse['longID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
	                <td><?php echo $BulkForms['dcourses']->checkbox_field('select_dcourses_'.$Lcourse['longID'], '', '1', false); ?></td>
	            </tr>
	<?php   
	    }
	?>
	        </tbody>
	    </table>
	    <?php
	    echo $BulkForms['dcourses']->submit_field('btnBulkDeleteDcourses', 'Delete Selected Distance Courses', false, 'button action-alert');
	    echo $BulkForms['dcourses']->form_end();
	    ?>
		  
		<?php
		    }    
    
    }

    echo $HTML->main_panel_end();

    /*
     * Bulk delete can't be undone and the button sits right under a long table, so make the
     * admin confirm, and say how many rows are going. Submitting with nothing ticked is
     * stopped here too - the pre file reports it, but there's no point in the round trip.
     */
    ?>
    <script>
    document.querySelectorAll('form.bulk-delete-form').forEach(function(form){
      form.addEventListener('submit', function(e){
        var ticked = form.querySelectorAll('input[type=checkbox]:checked').length;
        if (!ticked) {
          e.preventDefault();
          alert('Tick the rows you want to delete first.');
          return;
        }
        if (!confirm('Delete ' + ticked + (ticked === 1 ? ' item' : ' items') + '? This cannot be undone.')) {
          e.preventDefault();
        }
      });
    });
    </script>
    <?php