<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Events'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/events/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_training/events/',
  ]);
  
  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_training/events/published',
  ]);
  
  $Smartbar->add_item([
      'active' => true,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_training/events/suspended',
  ]);

  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();
  if ($message) echo $message;

  elseif (PerchUtil::count($events)) {
     
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
                <td class="suspended">SUSPENDED</td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/events/delete/?id=<?php echo $HTML->encode(urlencode($Event['eventID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Event['eventID'], '', '1', false); ?></td>
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
        array('label'=>'Published', 'value'=>'pub'), array('label'=>'Pending', 'value'=>'pub'), array('label'=>'Suspended', 'value'=>'sus')),
      'active');
    echo $Form->submit_field('btnSubmit', 'Update All Selected', '');
     
    echo $Form->form_end();         

    } // if things

	$HTML->main_panel_end();