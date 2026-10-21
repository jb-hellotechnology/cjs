<?php
    
	  $Daily_Jobs = new CJS_Daily_Jobs($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Daily_Job = $Daily_Jobs->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($Daily_Job)) {
            $Daily_Job->delete();

              if ($Form->submitted_via_ajax) {
                  echo $API->app_path().'/daily/';
                  exit;
              }else{
                 PerchUtil::redirect($API->app_path().'/daily/');
              }

          }else{
              $message = $HTML->failure_message('Sorry, that job could not be deleted.');
          }
      }

      $details = $Daily_Job->to_array();
