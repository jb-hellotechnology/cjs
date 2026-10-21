<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

session_start();

include('CJS_Subscribers.Subscriber.class.php');
include('CJS_Subscribers.Subscribers.class.php');

function cjs_subscribers_form_handler($SubmittedForm)
{

  $CJS_Subscribers = new CJS_Subscribers_Subscribers;

  if ($SubmittedForm->validate()) {

    $API  = new PerchAPI(1.0, 'cjs_subscribers');

    switch($SubmittedForm->formID) {

      case 'subscriber_signin':
	    $return = $CJS_Subscribers->handle_login($SubmittedForm);
		if ($return=='error') {
            $_SESSION['error'] = 1;
        }else{
	        $_SESSION['subscriber'] = 1;
        }
      break;

    }            

  }
}