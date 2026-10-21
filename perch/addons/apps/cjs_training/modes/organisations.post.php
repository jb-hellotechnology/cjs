<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);  
*/

	echo $HTML->title_panel([
    'heading' => $Lang->get('Offices'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/offices/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_training/offices/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Merge',
      'link'  => '/addons/apps/cjs_training/offices/merge/',
  ]);
  
  $Smartbar->add_item([
		'active' => false,
		'type'   => 'search',
		'title'  => 'Search',
		'arg'    => 'q',
		'icon'   => 'core/search',
		'position' => 'end',
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
                <th><?php echo $Lang->get('Office Name'); ?></th>
                <th><?php echo $Lang->get('Contact Name'); ?></th>
                <th><?php echo $Lang->get('Contact Telephone'); ?></th>
                <th><?php echo $Lang->get('Contact Email'); ?></th>              
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Delete Bulk Select')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($organisations as $Organisation) {
	    
	    $q = $_GET['q'];
	    
	    $match = 0;

	    if($q<>''){
			
			$pos = strpos($Organisation->name(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    $pos = strpos($Organisation->contactName(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    $pos = strpos($Organisation->contactPhone(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    $pos = strpos($Organisation->contactEmail(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    
	    }
	
		if($q=='' OR ($q<>'' AND $match==1)){
	    
?>
            <tr>
                <td><?php echo $HTML->encode($Organisation->organisationID()); ?></td>
                <td ><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/offices/add_new?id=<?php echo $HTML->encode(urlencode($Organisation->id())); ?>"><?php echo $HTML->encode($Organisation->name()); ?></a></td>
                <td><?php echo $HTML->encode($Organisation->contactName()); ?></td>
                <td><?php echo $HTML->encode($Organisation->contactPhone()); ?></td>
                <td><?php echo $HTML->encode($Organisation->contactEmail()); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/offices/delete/?id=<?php echo $HTML->encode(urlencode($Organisation->id())); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Organisation->organisationID(), '', '1', false); ?></td>
            </tr>
<?php   
		}
		
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