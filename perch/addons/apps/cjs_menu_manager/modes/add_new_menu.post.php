<?php    

	echo $HTML->title_panel([
    'heading' => $Lang->get('Edit Menu - '.$details['name']),
     'button'  => [
          'text' => $Lang->get('Add New Item'),
          'link' => $API->app_nav().'/add_new/add_item/?menu='.$_GET['id'],
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);

    # Main panel
    echo $HTML->main_panel_start();
    
//    include('_subnav.php');


    // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }else{
		  
		  
 
	    if (PerchUtil::count($cjsMenuItemsDetails)) {
		?>
		    <div id="sortable">
		<?php
			$i = 1;
		    foreach($cjsMenuItemsDetails as $cjsMenuItem) {
		?>
		            <div data-order="<?php echo $i; ?>" data-itemid="<?php echo $cjsMenuItem['menuItemID']; ?>" class="top">
		                <p class="move">&#8597;</p>
		                <a href="/perch/addons/apps/cjs_menu_manager/add_new/add_item/?id=<?php echo $cjsMenuItem['menuItemID']; ?>"><?php echo $cjsMenuItem['pageTitle']; ?></a> <a class="subitem" href="/perch/addons/apps/cjs_menu_manager/add_new/add_item/?menu=<?php echo $_GET['id']; ?>&parentID=<?php echo $cjsMenuItem['menuItemID']; ?>">Add Sub Item</a>
		                
		                <a href="/perch/addons/apps/cjs_menu_manager/add_new/add_item/delete_item/index.php?id=<?php echo $cjsMenuItem['menuItemID']; ?>" class="button button-small action-alert">Delete</a>
		            <div class="children">
		            <?php
			        	$i++;    
			            //GET CHILDREN
			            
			            $childrenItems = $cjsMenusItems->get_children($cjsMenuItem['menuItemID']);
			            
			            foreach($childrenItems as $cjsChildMenuItem) {
		?>
			            <div data-order="<?php echo $i; ?>" data-itemid="<?php echo $cjsChildMenuItem['menuItemID']; ?>" class="second">
			                <p class="move-child">&#8597;</p>
			                <a href="/perch/addons/apps/cjs_menu_manager/add_new/add_item/?id=<?php echo $cjsChildMenuItem['menuItemID']; ?>"><?php echo $cjsChildMenuItem['pageTitle']; ?></a>
			                <a href="/perch/addons/apps/cjs_menu_manager/add_new/add_item/delete_item/index.php?id=<?php echo $cjsChildMenuItem['menuItemID']; ?>" class="button button-small action-alert">Delete</a>
			            </div>
		            
		            <?php
			            $i++;
			            }
			        ?>
		            </div>
		            </div>
		<?php   
		    }
		?>
		    </div>
		<?php     
		
		    } // if things
		    
		    // NO MESSAGE - DISPLAY FORM
	    echo $Form->form_start();

      // CREATE ALL STATIC FIELDS
      echo $Form->text_field('name', 'Name', isset($details['name'])?$details['name']:false, 'm input-simple');
      echo $Form->text_field('slug', 'Slug', isset($details['slug'])?$details['slug']:false, 'm input-simple');
      
      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/");

      echo $Form->form_end();
			    
    
    }
    
    echo $HTML->main_panel_end();
    ?>