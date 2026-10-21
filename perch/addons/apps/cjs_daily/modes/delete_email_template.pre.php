<?php
    
	$Emails = new CJS_Daily_Emails($API);
    $Templates = new CJS_Daily_Emails_Templates($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $eTemplate = $Templates->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        if (is_object($eTemplate)) {

            $eTemplate->delete();
            
            $message = $HTML->success_message('Email template deleted.');

          }else{
              $message = $HTML->failure_message('Sorry, that email template could not be deleted.');
          }
          
          
      }

      $details = $eTemplate->to_array();
