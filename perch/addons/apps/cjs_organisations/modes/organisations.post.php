<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);  
*/

	echo $HTML->title_panel([
    'heading' => $Lang->get('Organisations'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/organisations/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_organisations/organisations/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Merge',
      'link'  => '/addons/apps/cjs_organisations/organisations/merge/',
  ]);
  
  $Smartbar->add_item([
      'active' => false,
      'title' => 'Contacts CSV',
      'link'  => '/addons/apps/cjs_organisations/organisations/csv/',
  ]);


  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

    if (PerchUtil::count($organisations)) {

      echo $Form->form_start();
?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('ID'); ?></th>  
                <th><?php echo $Lang->get('Organisation Name'); ?></th> 
                <th>View/Edit</th>           
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Delete Bulk Select')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($organisations as $Organisation) {
?>
            <tr>
                <td>O<?php echo $HTML->encode($Organisation->organisationID()); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/organisations/add_new?id=<?php echo $HTML->encode(urlencode($Organisation->id())); ?>"><?php echo $HTML->encode($Organisation->name()); ?></a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/organisations/add_new/?id=<?php echo $HTML->encode(urlencode($Organisation->id())); ?>" class="button button-small action-success">View/Edit</a></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/organisations/delete/?id=<?php echo $HTML->encode(urlencode($Organisation->id())); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Organisation->organisationID(), '', '1', false); ?></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
   
    <!-- Submit Button  -->
     <?php  echo $Form->submit_field('btnSubmit', 'Delete All Selected', '');?>


<?php    
    echo $Form->form_end();	       

    } // if things

  $HTML->main_panel_end();