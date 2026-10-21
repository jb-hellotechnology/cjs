<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Voluntary Organisations'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/voluntary_organisations/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/published/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/archive/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_workdays/voluntary_organisations/upload_csv/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here

  //if (PerchUtil::count($vorganisations_wd)) {

      echo $Form->form_start();
?>
    <table class="d">
        <thead>
            <tr>                
                <th class="first"><?php echo $Lang->get('Provider'); ?></th>
                <th><?php echo $Lang->get('Org. ID'); ?></th>
                <th><?php echo $Lang->get('Advert'); ?></th>
                <th><?php echo $Lang->get('ID'); ?></th>
                <th><?php echo $Lang->get('Status'); ?></th>              
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Update Bulk Select')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($vorganisations_wd as $VOrganisation_wd) {
?>
            <tr>
                <td class="primary"><a href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/add_new?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd->id())); ?>"><?php echo $HTML->encode($VOrganisation_wd->vorgName()); ?></a></td>              
                <td><?php echo $HTML->encode($VOrganisation_wd->organisationID()); ?></td>
                <td><?php echo $HTML->encode($VOrganisation_wd->description()); ?></td>
                <td><?php echo $HTML->encode($VOrganisation_wd->vorganisationID()); ?></td>
                <td><?php echo $HTML->encode($VOrganisation_wd->advertStatus()); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/voluntary_organisations/delete/?id=<?php echo $HTML->encode(urlencode($VOrganisation_wd->id())); ?>" class="delete inline-delete"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$VOrganisation_wd->vorganisationID(), '', '1', false); ?></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
   
<?php    

    //Submit Button, date field and select field
    
    echo $Form->date_field('checkDate', 'New Check Date', false, true);
    echo $Form->select_field('advertStatus', 'New Advert Status', array(
        array('label'=>'Published', 'value'=>'Published'), array('label'=>'Pending', 'value'=>'Pending'), array('label'=>'Suspended', 'value'=>'Suspended')),
      'active');
    echo $Form->submit_field('btnSubmit', 'Update All Selected', '');

    echo $Form->form_end();	       

    //} // if things

  #Main Panel End

  $HTML->main_panel_end();