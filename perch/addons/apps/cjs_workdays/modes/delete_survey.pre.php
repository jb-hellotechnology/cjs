<?php
    
    $Surveys = new CJS_Workdays_Surveys($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Survey = $Surveys->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($Survey)) {
            $Survey->delete();

              if ($Form->submitted_via_ajax) {
                  echo $API->app_path().'/surveys/';
                  exit;
              }else{
                 PerchUtil::redirect($API->app_path().'/surveys/');
              }

          }else{
              $message = $HTML->failure_message('Sorry, that survey could not be deleted.');
          }
      }

      $details = $Survey->to_array();
