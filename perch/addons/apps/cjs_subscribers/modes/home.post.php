<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);  

	echo $HTML->title_panel([
    'heading' => $Lang->get('Subscribers'),
    ], $CurrentUser);
    

    # Main panel
  $HTML->main_panel_start();

    if (PerchUtil::count($subscribers)) {
?>
    <table class="d">
        <thead>
            <tr> 
                <th><?php echo $Lang->get('Username'); ?></th>            
                <th> <?php echo $Lang->get('Password')?></th>
                <th> <?php echo $Lang->get('Password2')?></th>
                <th>Logins In Last Week</th>
                <th>Logins In Last Month</th>
                <th>Logins In Last Year</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($subscribers as $Subscriber) {
	    $loginsWeek = $Subscribers->logins($Subscriber->subscriberID(),'week');
	    $loginsMonth = $Subscribers->logins($Subscriber->subscriberID(),'month');
	    $loginsYear = $Subscribers->logins($Subscriber->subscriberID(),'year');
?>
            <tr>
                <td><?php echo $HTML->encode($Subscriber->username()); ?></td>
                <td><?php echo $HTML->encode($Subscriber->password()); ?></td>
                <td><?php echo $HTML->encode($Subscriber->password2()); ?></td>
                <td><?php echo $loginsWeek; ?></td>
                <td><?php echo $loginsMonth; ?></td>
                <td><?php echo $loginsYear; ?></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
<?php     

    } // if things

  $HTML->main_panel_end();