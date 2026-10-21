<?php
    
	  $Employers = new CJS_Daily_Employers($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Employer = $Employers->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($Employer)) {
            $Employer->delete();

              if ($Form->submitted_via_ajax) {
                  echo $API->app_path().'/employers/';
                  exit;
              }else{
                 PerchUtil::redirect($API->app_path().'/employers/');
              }

          }else{
              $message = $HTML->failure_message('Sorry, that employer could not be deleted.');
          }
      }

      $details = $Employer->to_array();
