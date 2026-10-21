<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Providers'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/providers/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_training/providers/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_training/providers/published/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_training/providers/suspended/',
  ]);

  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();
  if ($message) echo $message;    
  elseif (PerchUtil::count($providers)) {
     
      echo $Form->form_start();

?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('ID'); ?></th>  
                <th><?php echo $Lang->get('Provider Name'); ?></th>  
                <th><?php echo $Lang->get('Description'); ?></th>
                <th>View/Edit</th>
                <th><?php echo $Lang->get('Status'); ?></th>              
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Bulk Update')?></th>
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
                <td class="pending"><a href="<?php echo $HTML->encode($API->app_path()); ?>/providers/add_new/?id=<?php echo $HTML->encode(urlencode($Provider['providerID'])); ?>">PENDING</a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/providers/delete/?id=<?php echo $HTML->encode(urlencode($Provider['providerID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Provider['providerID'], '', '1', false); ?></td>
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
        array('label'=>'Published', 'value'=>'pub'), array('label'=>'Pending', 'value'=>'pen'), array('label'=>'Suspended', 'value'=>'sus')
    ), 'active');
    echo $Form->submit_field('btnSubmit', 'Update All Selected', '');
     
    echo $Form->form_end();
    } // if things

	$HTML->main_panel_end();