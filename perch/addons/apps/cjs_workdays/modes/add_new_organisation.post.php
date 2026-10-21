<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
    include('_subnav.php');

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
      echo $Form->text_field('contactJobTitle', 'Contact Job Title', isset($details['contactJobTitle'])?$details['contactJobTitle']:false, 'm input-simple');
      echo $Form->text_field('contactPhone', 'Contact Telephone', isset($details['contactPhone'])?$details['contactPhone']:false, 'm input-simple');
      echo $Form->text_field('contactEmail', 'Contact Email', isset($details['contactEmail'])?$details['contactEmail']:false, 'm input-simple');
      echo $Form->select_field('doNotContact', 'Do Not Contact', array(
        array('label'=>'No', 'value'=>'No'), array('label'=>'Yes', 'value'=>'Yes')
      ), isset($details['doNotContact'])?$details['doNotContact']:'active');
      
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Organisations_wd->static_fields);

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/offices/");

      echo $Form->form_end();	 
      
      echo '<br /><br /><p><a class="button button-small action-info" href="/perch/addons/apps/cjs_workdays/offices/merge/?id='.$_GET['id'].'">Merge this Office</a></p><br /><br />';
    
    }

  if($organisationID AND !$message){
?>
  <h2>Voluntary Organisations</h2>
  <table class="d">
          <thead>
              <tr>                
                  <th class="first"><?php echo $Lang->get('Provider'); ?></th>
                  <th><?php echo $Lang->get('Org. ID'); ?></th>
                  <th><?php echo $Lang->get('Advert'); ?></th>
                  <th><?php echo $Lang->get('ID'); ?></th>
                  <th>View/Edit</th> 
                  <th><?php echo $Lang->get('Status'); ?></th>              
                  <th> <?php echo $Lang->get('Delete')?></th>
              </tr>
          </thead>
          <tbody>
  <?php
      foreach($vorgs as $VOrganisation_wd) {
  ?>
        <tr>
            <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/add_new?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd['vorganisationID'])); ?>"><?php echo $HTML->encode($VOrganisation_wd['vorgName']); ?></a></td>
            <td><?php echo $HTML->encode($VOrganisation_wd['organisationID']); ?></td>
            <?php 
                  $description = PerchUtil::json_safe_decode($VOrganisation_wd['vorganisationDynamicFields'], true);
            ?>
            <td><?php echo $description['description']['processed']; ?></td>              
            <td><?php echo $HTML->encode($VOrganisation_wd['vorganisationID']); ?></td>
            <td><a class="button button-small action-success" href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/add_new?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd['vorganisationID'])); ?>">View/Edit</a></td>
            <td <?php if($Survey['advertStatus']=='sus'){echo 'class="suspended"';}elseif($Survey['advertStatus']=='class="published"'){echo 'PUBLISHED';}else{echo 'class="pending"';}; ?>><?php if($VOrganisation_wd['advertStatus']=='sus'){echo 'SUSPENDED';}elseif($VOrganisation_wd['advertStatus']=='pub'){echo 'PUBLISHED';}else{echo 'PENDING';}; ?></td>
            <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/delete/?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd['vorganisationID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
        </tr>
  <?php   
      }
  ?>
      </tbody>
  </table>
  <h2>Surveys</h2>
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
                  <td><?php echo $HTML->encode($Survey['surveyID']); ?></td>
                  <td><?php echo $HTML->encode($Survey['organisationID']); ?></td>
                  <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/add_new?id=<?php echo $HTML->encode(urlencode($Survey['surveyID'])); ?>"><?php echo $HTML->encode($Survey['Title']); ?></a></td>
                  <td><?php echo $HTML->encode($Survey['provider']);?></td>
                  <td><?php echo $HTML->encode($Survey['section']); ?></td>
                  <td><?php echo $HTML->encode($Survey['checkDate']); ?></td>  
                  <td><a class="button button-small action-success" href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/add_new?id=<?php echo $HTML->encode(urlencode($Survey['surveyID'])); ?>">View/Edit</a></td>            
                  <td <?php if($Survey['advertStatus']=='sus'){echo 'class="suspended"';}elseif($Survey['advertStatus']=='class="published"'){echo 'PUBLISHED';}else{echo 'class="pending"';}; ?>><?php if($Survey['advertStatus']=='sus'){echo 'SUSPENDED';}elseif($Survey['advertStatus']=='pub'){echo 'PUBLISHED';}else{echo 'PENDING';}; ?></td>
                  <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/delete/?id=<?php echo $HTML->encode(urlencode($Survey['surveyID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
              </tr>
  <?php
      }
  ?>
        </tbody>
    </table>
<?php
    }
      
      
    echo $HTML->main_panel_end();