<?php    

	echo $HTML->title_panel([
    'heading' => $Lang->get('Menu Item'),
    ], $CurrentUser);

    # Main panel
    echo $HTML->main_panel_start();
    
//    include('_subnav.php');

    // DISPLAY THE HEADING (EDIT/NEW)
    echo $HTML->heading1($heading1);

    // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }else{
 
      // NO MESSAGE - DISPLAY FORM
	    echo $Form->form_start();
		
		$parentID = '';
		if(isset($details['menuID'])){$menuID = $details['menuID'];}else{$menuID = $_GET['menu'];}
		if(isset($details['parentItem'])){$parentID = $details['parentItem'];}else{$parentID = $_GET['parentID'];}
		

      // CREATE ALL STATIC FIELDS
      echo $Form->select_field('pagePath', 'Page', $pages, isset($details['pagePath'])?$details['pagePath']:'active', 'm input-simple');
      echo $Form->text_field('altURL', 'Alternate (External) URL', isset($details['altURL'])?$details['altURL']:false, 'm input-simple');
      echo $Form->checkbox_field('linkActive', 'Show Active?', '1', isset($details['linkActive'])?$details['linkActive']:false);
      echo $Form->checkbox_field('openNewTab', 'Open in New Tab?', '1', isset($details['openNewTab'])?$details['openNewTab']:false);
      echo $Form->checkbox_field('alignLeft', 'Align Left', '1', isset($details['alignLeft'])?$details['alignLeft']:false);
      echo $Form->checkbox_field('alignRight', 'Align Right', '1', isset($details['alignRight'])?$details['alignRight']:false);
      echo $Form->checkbox_field('bold', 'Bold?', '1', isset($details['bold'])?$details['bold']:false);
      echo $Form->checkbox_field('italic', 'Italic?', '1', isset($details['italic'])?$details['italic']:false);
      echo $Form->checkbox_field('lineBreak', 'Line Break After?', '1', isset($details['lineBreak'])?$details['lineBreak']:false);
      echo $Form->text_field('pageTitle', 'Link Text (Page Name)', isset($details['pageTitle'])?$details['pageTitle']:false, 'm input-simple');
      echo $Form->hidden('menuID', $menuID);
      echo $Form->hidden('parentItem', $parentID);
      
      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/");

      echo $Form->form_end();	    
    
    }
    
    echo $HTML->main_panel_end();