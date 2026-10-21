<?php
    
    $Organisations = new CJS_Workdays_Organisations($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Organisation = $Organisations->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($Organisation)) {
            $Organisation->delete();

              if ($Form->submitted_via_ajax) {
                  echo $API->app_path().'/offices/';
                  exit;
              }else{
                 PerchUtil::redirect($API->app_path().'/offices/');
              }

          }else{
              $message = $HTML->failure_message('Sorry, that organisation could not be deleted.');
          }
      }

      $details = $Organisation->to_array();
