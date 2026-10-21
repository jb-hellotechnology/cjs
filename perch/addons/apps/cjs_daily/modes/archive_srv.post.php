<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Surveys'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/surveys/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_workdays/surveys/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_workdays/surveys/published/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_workdays/surveys/archive/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_workdays/surveys/upload_csv/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_workdays/surveys/export/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here
  //if (PerchUtil::count($surveys)) {

      echo $Form->form_start();
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
                <th><?php echo $Lang->get('Status'); ?></th>              
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Update Bulk Select')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($surveys as $Survey) {
      if ($Survey->advertStatus()=='Suspended'){
?>
            <tr>
                <td class="primary"><?php echo $HTML->encode($Survey->surveyID()); ?></td>
                <td><?php echo $HTML->encode($Survey->organisationID()); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/add_new?id=<?php echo $HTML->encode(urlencode($Survey->id())); ?>"><?php echo $HTML->encode($Survey->title()); ?></a></td>
                <td><?php echo $HTML->encode($Survey->provider());?></td>
                <td><?php echo $HTML->encode($Survey->section()); ?></td>
                <td><?php echo $HTML->encode($Survey->checkDate()); ?></td>              
                <td><?php echo $HTML->encode($Survey->advertStatus()); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/surveys/delete/?id=<?php echo $HTML->encode(urlencode($Survey->id())); ?>" class="delete inline-delete"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Survey->surveyID(), '', '1', false); ?></td>
            </tr>
<?php
      }
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