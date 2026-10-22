<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Long Courses'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/long_courses/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_training/long_courses/',
  ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Published',
        'link'  => '/addons/apps/cjs_training/long_courses/published',
    ]);
    
     $Smartbar->add_item([
        'active' => false,
        'title' => 'Suspended',
        'link'  => '/addons/apps/cjs_training/long_courses/suspended',
    ]);
  
  $Smartbar->add_item([
        'active' => false,
        'title' => 'Upload CSV',
        'link'  => '/addons/apps/cjs_training/long_courses/upload_csv/',
    ]);

  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

  if ($message) echo $message;    
  elseif (PerchUtil::count($lcourses)) {
    
    echo $Form->form_start();


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
                <th class="action last"><?php echo $Lang->get('Bulk Update')?></th>                
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
                <td><?php echo $Form->checkbox_field('select_'.$Lcourse['longID'], '', '1', false); ?></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
<?php    
    //Submit Button, date field and select field
    
    echo $Form->text_field('checkDate', 'Check Date', false, 'm input-simple datepicker');
    echo $Form->select_field('useCheckDate', 'Change Check Date', [['label'=>'Yes', 'value'=>'Yes'], ['label'=>'No', 'value'=>'No']], 'active');    
    echo $Form->select_field('advertStatus', 'Advert Status', array(
        array('label'=>'Published', 'value'=>'pub'), array('label'=>'Pending', 'value'=>'pen'), array('label'=>'Suspended', 'value'=>'sus')),
      'active');
    echo $Form->submit_field('btnSubmit', 'Update All Selected', '');
     
    echo $Form->form_end();        

    } // if things

	$HTML->main_panel_end();