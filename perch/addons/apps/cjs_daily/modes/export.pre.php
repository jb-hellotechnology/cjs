<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  if($_POST){
    
    ob_end_clean();

    $postvars = array('MonthOrRecently', 'checkDateGreater', 'checkDateLess', 'longRunning');	 
    $data = $Form->receive($postvars);

    $data['checkDateGreater'] = $Form->get_date('checkDateGreater');
    $data['checkDateLess'] = $Form->get_date('checkDateLess');
    
    $Volunteers = new CJS_Daily_Volunteers($API);
    $volunteers = array();
    $volunteers = $Volunteers->all();

    // CREATE CSV

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=volunteers.csv');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // output the column headings
    fputcsv($output, array('volunteerID','title','employerDisplay','employerID','closingDate','closingDateT','intDate','location','payBenefits',
                          'tcOccasional','tcOnceorTwiceWeek','tcPartTime','tcFullTime','tcSixMonths','tcSixMonthsYear','tcOneYearMore',
                          'vidEmbedC','howToAp','typeRole','sAdminIT','sArbo','sComVol','sCountry','sEco','sEnvOE','sHorti','sPubF','sRoW',
                          'sVisMan','sWiWoAnC','rNational','rInternational','rScotland','rWales','rNIreland','rEngland','rAnglia','rMidlands',
                          'rNEast','rNWest','rSouth','rSWest','accommodation','expenses','fTraining','imageLogo','imageAlt','longRun','deleteDate',
                          'displayUntil','freeLin','displayFrom','archive','advertStatus','volunteersDynamicFields'));

    // EXPORT DATA!
    foreach($volunteers AS $Volunteer){
      
      if($data['MonthOrRecently']=='From'){
        
        if(($Volunteer->displayFrom()>=$data['checkDateGreater'] AND $Volunteer->displayFrom()<=$data['checkDateLess']) OR $Volunteer->longRun()=='1'){
          fputcsv($output, array($Volunteer->volunteerID(),$Volunteer->title(),$Volunteer->employerDisplay(),$Volunteer->employerID(),$Volunteer->closingDate(),$Volunteer->closingDateT(),$Volunteer->intDate(),
                               $Volunteer->location(),$Volunteer->payBenefits(),$Volunteer->tcOccasional(),$Volunteer->tcOnceorTwiceWeek(),$Volunteer->tcPartTime(),$Volunteer->tcFullTime(),$Volunteer->tcSixMonths(),
                               $Volunteer->tcSixMonthsYear(),$Volunteer->tcOneYearMore(),$Volunteer->vidEmbedC(),$Volunteer->howToAp(),$Volunteer->typeRole(),$Volunteer->sAdminIT(),$Volunteer->sArbo(),$Volunteer->sComVol(),
                               $Volunteer->sCountry(),$Volunteer->sEco(),$Volunteer->sEnvOE(),$Volunteer->sHorti(),$Volunteer->sPubF(),$Volunteer->sRoW(),$Volunteer->sVisMan(),$Volunteer->sWiWoAnC(),$Volunteer->rNational(),
                               $Volunteer->rInternational(),$Volunteer->rScotland(),$Volunteer->rWales(),$Volunteer->rNIreland(),$Volunteer->rEngland(),$Volunteer->rAnglia(),$Volunteer->rMidlands(),$Volunteer->rNEast(),
                               $Volunteer->rNWest(),$Volunteer->rSouth(),$Volunteer->rSWest(),$Volunteer->accommodation(),$Volunteer->expenses(),$Volunteer->fTraining(),$Volunteer->imageLogo(),$Volunteer->imageAlt(),
                               $Volunteer->longRun(),$Volunteer->deleteDate(),$Volunteer->displayUntil(),$Volunteer->freeLin(),$Volunteer->displayFrom(),$Volunteer->archive(),$Volunteer->advertStatus(),
                               $Volunteer->volunteersDynamicFields()));  
        }
       
      }else{
        
        if(($Volunteer->displayUntil()>=$data['checkDateGreater'] AND $Volunteer->displayUntil()<=$data['checkDateLess']) OR $Volunteer->longRun()=='1'){
          fputcsv($output, array($Volunteer->volunteerID(),$Volunteer->title(),$Volunteer->employerDisplay(),$Volunteer->employerID(),$Volunteer->closingDate(),$Volunteer->closingDateT(),$Volunteer->intDate(),
                               $Volunteer->location(),$Volunteer->payBenefits(),$Volunteer->tcOccasional(),$Volunteer->tcOnceorTwiceWeek(),$Volunteer->tcPartTime(),$Volunteer->tcFullTime(),$Volunteer->tcSixMonths(),
                               $Volunteer->tcSixMonthsYear(),$Volunteer->tcOneYearMore(),$Volunteer->vidEmbedC(),$Volunteer->howToAp(),$Volunteer->typeRole(),$Volunteer->sAdminIT(),$Volunteer->sArbo(),$Volunteer->sComVol(),
                               $Volunteer->sCountry(),$Volunteer->sEco(),$Volunteer->sEnvOE(),$Volunteer->sHorti(),$Volunteer->sPubF(),$Volunteer->sRoW(),$Volunteer->sVisMan(),$Volunteer->sWiWoAnC(),$Volunteer->rNational(),
                               $Volunteer->rInternational(),$Volunteer->rScotland(),$Volunteer->rWales(),$Volunteer->rNIreland(),$Volunteer->rEngland(),$Volunteer->rAnglia(),$Volunteer->rMidlands(),$Volunteer->rNEast(),
                               $Volunteer->rNWest(),$Volunteer->rSouth(),$Volunteer->rSWest(),$Volunteer->accommodation(),$Volunteer->expenses(),$Volunteer->fTraining(),$Volunteer->imageLogo(),$Volunteer->imageAlt(),
                               $Volunteer->longRun(),$Volunteer->deleteDate(),$Volunteer->displayUntil(),$Volunteer->freeLin(),$Volunteer->displayFrom(),$Volunteer->archive(),$Volunteer->advertStatus(),
                               $Volunteer->volunteersDynamicFields()));  
        }
        
      }
      
    }
    
    exit;
    
  }