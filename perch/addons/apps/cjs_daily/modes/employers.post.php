<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Employers'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/employers/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'View All',
      'link'  => '/addons/apps/cjs_daily/employers/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_daily/employers/export/',
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
  //if (PerchUtil::count($employers)) {

      echo $Form->form_start();
?>
    <table class="d">
        <thead>
            <tr>                
                <th class="first"><?php echo $Lang->get('ID'); ?></th>
                <th><?php echo $Lang->get('Employer Name'); ?></th>
                <th><?php echo $Lang->get('Contact Job Title'); ?></th>
                <th><?php echo $Lang->get('Contact Name'); ?></th>                            
                <th>View/Edit</th>
                <th> <?php echo $Lang->get('Delete')?></th>
                <th class="action last"><?php echo $Lang->get('Bulk Select')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($employers as $Employer) {
     $dato = json_decode($Employer->employerDynamicFields(), true);
     
     	$q = $_GET['q'];
	    
	    $match = 0;

	    if($q<>''){

			$pos = strpos($Employer->name(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    $pos = strpos($Employer->contactName(), $q);
		    if($pos !== false){
			    $match = 1;
		    }
		    
	    }
	
		if($q=='' OR ($q<>'' AND $match==1)){
     
?>
          
            <tr>
                <td>E<?php echo $HTML->encode($Employer->employerID()); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/employers/add_new?id=<?php echo $HTML->encode(urlencode($Employer->id())); ?>"><?php echo $HTML->encode($Employer->name()); ?></a></td>                            
                <td><?php echo $HTML->encode($Employer->contactJobTitle()); ?></td>
                <td><?php echo $HTML->encode($Employer->contactName()); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/employers/add_new?id=<?php echo $HTML->encode(urlencode($Employer->id())); ?>" class="button button-small action-success">View/Edit</a></td>       
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/employers/delete/?id=<?php echo $HTML->encode(urlencode($Employer->id())); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>
                <td><?php echo $Form->checkbox_field('select_'.$Employer->employerID(), '', '1', false); ?></td>
            </tr>
<?php   
		}
    }
?>
        </tbody>
    </table>

<?php    

    //Submit Button, date field and select field
    
    echo $Form->submit_field('btnSubmit', 'Delete All Selected', '');

    echo $Form->form_end();	       

    //} // if things


  #Main Panel End

  $HTML->main_panel_end();