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
      'active' => false,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_daily/volunteers/suspended/',
  ]);

  $Smartbar->add_item([
      'active' => true,
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
                <th class="first"><?php echo $Lang->get('ID'); ?></th>
                <th><?php echo $Lang->get('Title'); ?></th>
                <th><?php echo $Lang->get('Employer'); ?></th>
                <th><?php echo $Lang->get('Location'); ?></th>
                <th><?php echo $Lang->get('Closing Date'); ?></th>              
                <th><?php echo $Lang->get('Status'); ?></th>              
                <th class="action last"><?php echo $Lang->get('Delete')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($volunteers as $Volunteer) {
      if ($Volunteer->archive()=='1'){
?>
            <tr>
                <td class="primary"><?php echo $HTML->encode($Volunteer->volunteerID()); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/add_new?id=<?php echo $HTML->encode(urlencode($Volunteer->id())); ?>"><?php echo $HTML->encode($Volunteer->title()); ?></a></td>
                <td><?php echo $HTML->encode($Volunteer->employerDisplay()); ?></td>
                <td><?php echo $HTML->encode($Volunteer->location()); ?></td>                
                <td><?php echo $HTML->encode($Volunteer->closingDate());?></td>
                <td><?php echo $HTML->encode($Volunteer->advertStatus()); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/volunteers/delete/?id=<?php echo $HTML->encode(urlencode($Volunteer->id())); ?>" class="delete inline-delete"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php
      }
    }
?>
        </tbody>
    </table>

<?php    

    //Submit Button, date field and select field
    
//    echo $Form->date_field('checkDate', 'New Check Date', false, true);
//     echo $Form->select_field('advertStatus', 'New Advert Status', array(
//         array('label'=>'Publish', 'value'=>'Published'), array('label'=>'Suspend', 'value'=>'Pending'), array('label'=>'Delete', 'value'=>'Delete')),
//       'active');
//     echo $Form->submit_field('btnSubmit', 'Update All Selected', '');

//     echo $Form->form_end();	       

    //} // if things


  #Main Panel End

  $HTML->main_panel_end();