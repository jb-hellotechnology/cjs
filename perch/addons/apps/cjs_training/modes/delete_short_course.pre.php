<?php
    
    $Scourses = new CJS_Training_Short_Courses($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Scourse = $Scourses->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($Scourse)) {
            $Scourse->delete();

              if ($Form->submitted_via_ajax) {
                  echo $API->app_path().'/short_courses/';
                  exit;
              }else{
                 PerchUtil::redirect($API->app_path().'/short_courses/');
              }

          }else{
              $message = $HTML->failure_message('Sorry, that short course could not be deleted.');
          }
      }

      $details = $Scourse->to_array();
