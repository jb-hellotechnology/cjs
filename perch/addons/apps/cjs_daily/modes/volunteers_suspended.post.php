<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Volunteer'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/volunteers/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_daily/volunteers/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_daily/volunteers/published/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_daily/volunteers/suspended/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_daily/volunteers/archive/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_daily/volunteers/upload_csv/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_daily/volunteers/export/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here
  //if (PerchUtil::count($volunteers)) {

      echo $Form->form_start();
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
                <th class="action last"><?php echo $Lang->get('Update Bulk Select')?></th>
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
                <td class="suspended"><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/add_new?id=<?php echo $HTML->encode(urlencode($Volunteer['volunteerID'])); ?>">SUSPENDED</a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/delete/?id=<?php echo $HTML->encode(urlencode($Volunteer['volunteerID'])); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Volunteer['volunteerID'], '', '1', false); ?></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>

<?php    

    //Submit Button, date field and select field
    
//    echo $Form->date_field('checkDate', 'New Check Date', false, true);
    echo $Form->select_field('advertStatus', 'New Advert Status', array(
        array('label'=>'Pending', 'value'=>'pen'), array('label'=>'Published', 'value'=>'pub'), array('label'=>'Suspended', 'value'=>'sus'), array('label'=>'Delete', 'value'=>'Delete')),
      'active');
    echo $Form->submit_field('btnSubmit', 'Update All Selected', '');

    echo $Form->form_end();	       

    //} // if things

echo "<br /><br /><br /><h3>Bulk Delete</h3>";

echo $Form->form_start();
echo $Form->date_field('deleteDate', 'Delete all volunteer adverts with a closing date older than:', false, true);
echo $Form->submit_field('btnSubmit', 'Delete Adverts', '');
echo $Form->form_end();

echo "<br /><br /><br /><h3>Bulk Archive</h3>";

echo $Form->form_start();
echo $Form->date_field('archiveDate', 'Archive all volunteer adverts with a closing date older than:', false, true);
echo $Form->submit_field('btnSubmit', 'Archive Adverts', '');
echo $Form->form_end();


  #Main Panel End

  $HTML->main_panel_end();