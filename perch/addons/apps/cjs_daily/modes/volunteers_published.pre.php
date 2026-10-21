<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Volunteers = new CJS_Daily_Volunteers($API);

  $volunteers = array();

  $volunteers = $Volunteers->get_published();

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvars = array('advertStatus','deleteDate','archiveDate');
    $data = $Form->receive($postvars);
    
    $data['deleteDate'] = $Form->get_date('deleteDate');
    $data['archiveDate'] = $Form->get_date('archiveDate');
    
    if($data['archiveDate']<>''){
      
      $archive = $Volunteers->archive($data['archiveDate']);
      $volunteers = $Volunteers->get_pending();
      $message = $HTML->success_message('Volunteer ads have been archived. Return to %sVolunteers%s', '<a href="'.$API->app_path() .'/volunteers/">', '</a>'); 
      
    }elseif($data['deleteDate']<>''){
      
      $archive = $Volunteers->deleteOld($data['deleteDate']);
      $volunteers = $Volunteers->get_published();
      $message = $HTML->success_message('Volunteer ads have been deleted. Return to %sVolunteers%s', '<a href="'.$API->app_path() .'/volunteers/">', '</a>');
      
    }else{

      //GETS PROPER DATE STRUCTURE FOR DATE FIELDS      	
      //$data['checkDate'] = $Form->get_date('checkDate');
      foreach ($volunteers as $Volunteer){
        if ($_POST['select_'.$Volunteer['volunteerID']]=='1'){
          $Volunteers->update_status($Volunteer['volunteerID'],$_POST['advertStatus']);
          if ($Volunteer['advertStatus']=='Delete'){
            $Volunteers->delete_volunteer($Volunteer['volunteerID']);
            //break
          }
        }
      }
      $volunteers = $Volunteers->get_pending();
      $message = $HTML->success_message('Your Volunteers have been successfully updated. Return to %sVolunteers%s', '<a href="'.$API->app_path() .'/volunteers/">', '</a>'); 
      
    }
  }