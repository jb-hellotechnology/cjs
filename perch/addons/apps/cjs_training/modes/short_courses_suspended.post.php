<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Short Courses'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/short_courses/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_training/short_courses/',
  ]);
  
  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_training/short_courses/published',
  ]);
  
  $Smartbar->add_item([
      'active' => true,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_training/short_courses/suspended',
  ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Upload CSV',
        'link'  => '/addons/apps/cjs_training/short_courses/upload_csv/',
    ]);

  echo $Smartbar->render();

    # Main panel

  $HTML->main_panel_start();

  if ($message) echo $message;    
  elseif (PerchUtil::count($scourses)) {

    echo $Form->form_start();

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
                <th class="action last"><?php echo $Lang->get('Bulk Update')?></th>
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
                <td class="suspended">SUSPENDED</td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/short_courses/delete/?id=<?php echo $HTML->encode(urlencode($Scourse['shortID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Scourse['shortID'], '', '1', false); ?></td>
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