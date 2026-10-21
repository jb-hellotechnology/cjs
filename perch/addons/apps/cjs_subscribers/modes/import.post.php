<?php
echo $HTML->title_panel([
'heading' => $Lang->get('Import Subscriber CSV'),
], $CurrentUser);

# Main panel
$HTML->main_panel_start();

if($message){
	echo $message; 
}

echo $Form->form_start();

//Choose File Button
echo $Form->file_field('fileToUpload','Upload CSV');

// Submit Button
echo $Form->submit_field('btnSubmit', 'Upload CSV', '');

echo $Form->form_end();

$HTML->main_panel_end();