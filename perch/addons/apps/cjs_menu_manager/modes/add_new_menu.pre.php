<?php

	$cjsMenus = new CJS_Menu_Manager_Menus($API);
	$cjsMenusItems = new CJS_Menu_Manager_Menus_Items($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_menus/menu.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	// GET THE ITEM AND MAKE IT INTO AN ARRAY
        $menuID = (int) $_GET['id'];    
        $cjsMenu = $cjsMenus->find($menuID, true);
        $details = $cjsMenu->to_array();
        
        $cjsMenuItemsDetails = $cjsMenusItems->get_by_menu($menuID);
        
        // SET THE HEADING
        $heading1 = 'Edit Menu';
      
        $buttonText = 'Update Menu';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $cjsMenu = false;
        $menuID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Menu';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Menu';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
 
        $postvars = array('name', 'slug');
    	$data = $Form->receive($postvars);

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['menuDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['menuDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $cjsMenus, $cjsMenu);
        $data['menuDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($cjsMenu)) {
            $result = $cjsMenu->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['menuID'])) unset($data['menuID']);

            $new_menu = $cjsMenus->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_menu) {
                PerchUtil::redirect($API->app_path() .'/add_new/?id='.$new_menu->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Menu could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Menu has been successfully updated. Return to %sMenus%s', '<a href="'.$API->app_path() .'/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Menu could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($cjsMenu)) {
            $details = $cjsMenu->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Menu has been successfully created. Return to %sMenus%s', '<a href="'.$API->app_path() .'/">', '</a>'); 
    }