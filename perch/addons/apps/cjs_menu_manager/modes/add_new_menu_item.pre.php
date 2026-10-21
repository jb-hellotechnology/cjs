<?php

	$cjsMenus = new CJS_Menu_Manager_Menus($API);
	$cjsMenusItems = new CJS_Menu_Manager_Menus_Items($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_menus/menu.html','cjs');
    
    $message = '';
    
    
    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	// GET THE ITEM AND MAKE IT INTO AN ARRAY
        $menuItemID = (int) $_GET['id'];    
        $cjsMenuItem = $cjsMenusItems->find($menuItemID, true);
        $details = $cjsMenuItem->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Menu Item';
      
        $buttonText = 'Update Menu Item';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $cjsMenu = false;
        $menuID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Menu Item';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Menu Item';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);
    
    $Pages = new PerchContent_Pages;
	$allpages = $Pages->get_by('pageHidden', '', 'pageTreePosition');
	$pages = array();

	$pages[] = array("label" => "", "value" => "");
	
	foreach($allpages as $Page) {
		$page = $Page->to_array();
		$pages[] = array("label" => $Page->pageTitle()." (".$Page->pagePath().")", "value" => $Page->pagePath());
	}
	
	sort($pages);
    
	
	if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
 
        $postvars = array('menuID', 'parentItem', 'pagePath', 'altURL', 'pageTitle', 'linkActive', 'openNewTab', 'alignLeft', 'alignRight', 'bold', 'italic', 'lineBreak');
    	$data = $Form->receive($postvars);
    	
    	if(!isset($data['linkActive'])){$data['linkActive']  = '0';}
    	if(!isset($data['openNewTab'])){$data['openNewTab']  = '0';}
    	if(!isset($data['alignLeft'])){$data['alignLeft']  = '0';}
    	if(!isset($data['alignRight'])){$data['alignRight']  = '0';}
    	if(!isset($data['bold'])){$data['bold']  = '0';}
    	if(!isset($data['italic'])){$data['italic']  = '0';}
    	if(!isset($data['lineBreak'])){$data['lineBreak']  = '0';}

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['menuitemDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['menuitemDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $cjsMenusItems, $cjsMenuItem);
        $data['menuitemDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($cjsMenuItem)) {
            $result = $cjsMenuItem->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['menuItemID'])) unset($data['menuItemID']);

            $new_menu_item = $cjsMenusItems->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_menu_item) {
                PerchUtil::redirect($API->app_path() .'/add_new/add_item/?id='.$new_menu_item->menuItemID().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Menu Item could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Menu Item has been successfully updated. Return to %sMenus%s', '<a href="'.$API->app_path() .'/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Menu Item could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($cjsMenuItem)) {
            $details = $cjsMenuItem->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Menu Item has been successfully created. Return to %sMenus%s', '<a href="'.$API->app_path() .'/">', '</a>'); 
    }