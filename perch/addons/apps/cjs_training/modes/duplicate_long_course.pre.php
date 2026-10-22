<?php

    $Lcourses = new CJS_Training_Long_Courses($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');

    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Lcourse = $Lcourses->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of duplicate has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($Lcourse)) {

            $duplicate = $Lcourses->duplicate($_GET['id']);
			PerchUtil::redirect($API->app_path().'/long_courses/');

          }else{
              $message = $HTML->failure_message('Sorry, that long course could not be duplicated.');
          }
      }

      $details = $Lcourse->to_array();
