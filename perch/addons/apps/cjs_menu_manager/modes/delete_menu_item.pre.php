<?php
    
    $cjsMenusItems = new CJS_Menu_Manager_Menus_Items($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $cjsMenuItem = $cjsMenusItems->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($cjsMenuItem)) {
            $cjsMenuItem->delete();

              if ($Form->submitted_via_ajax) {
                  echo $API->app_path().'/';
                  exit;
              }else{
                 PerchUtil::redirect($API->app_path().'/');
              }

          }else{
              $message = $HTML->failure_message('Sorry, that Menu could not be deleted.');
          }
      }

      $details = $cjsMenuItem->to_array();
