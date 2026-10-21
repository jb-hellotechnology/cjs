<?php

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
      'link'  => '/addons/apps/cjs_workdays/offices/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Merge',
      'link'  => '/addons/apps/cjs_workdays/offices/merge/',
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

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here

  //if (PerchUtil::count($organisations_wd)) {

      echo $Form->form_start();
?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('ID'); ?></th>  
                <th><?php echo $Lang->get('Office Name'); ?></th>
                <th><?php echo $Lang->get('Contact Name'); ?></th>
                <th><?php echo $Lang->get('Telephone'); ?></th>
                <th><?php echo $Lang->get('Email'); ?></th>                      
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Delete Bulk Select')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($organisations_wd as $Organisation_wd) {
	    
	    $q = $_GET['q'];
	    
	    $match = 0;

	    if($q<>''){
			
			$pos = strpos($Organisation_wd->name(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    $pos = strpos($Organisation_wd->contactName(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    $pos = strpos($Organisation_wd->contactPhone(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    $pos = strpos($Organisation_wd->contactEmail(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    
	    }
	
		if($q=='' OR ($q<>'' AND $match==1)){
			
?>
            <tr>
                <td>O<?php echo $HTML->encode($Organisation_wd->organisationID()); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/offices/add_new?id=<?php echo $HTML->encode(urlencode($Organisation_wd->id())); ?>"><?php echo $HTML->encode($Organisation_wd->name()); ?></a></td>
                <td><?php echo $HTML->encode($Organisation_wd->contactName()); ?></td>
                <td><?php echo $HTML->encode($Organisation_wd->contactPhone()); ?></td>
                <td><?php echo $HTML->encode($Organisation_wd->contactEmail()); ?></td>             
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/offices/delete/?id=<?php echo $HTML->encode(urlencode($Organisation_wd->id())); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Organisation_wd->organisationID(), '', '1', false); ?></td>
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

    //} // if things
  #Main Panel End

  $HTML->main_panel_end();