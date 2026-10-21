<?php
    
    $Workdays = new CJS_Workdays_Work_Days($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Workday = $Workdays->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($Workday)) {
            $Workday->delete();

              if ($Form->submitted_via_ajax) {
                  echo $API->app_path().'/work_days/';
                  exit;
              }else{
                 PerchUtil::redirect($API->app_path().'/work_days/');
              }

          }else{
              $message = $HTML->failure_message('Sorry, that workday could not be deleted.');
          }
      }

      $details = $Workday->to_array();
