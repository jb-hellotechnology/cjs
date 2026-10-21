<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Emails'),
    // 'button'  => [
    //       'text' => $Lang->get('New Email'),
    //       'link' => $API->app_nav().'/email/add_new/',
    //       'icon' => 'core/plus',
    //   ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Emails',
      'link'  => '/addons/apps/cjs_daily/email/',
  ]);
  
  $Smartbar->add_item([
      'active' => false,
      'title' => 'Templates',
      'link'  => '/addons/apps/cjs_daily/email/templates/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

?>
    <table class="d">
        <thead>
            <tr>                
                <th class="first"><?php echo $Lang->get('Email Subject'); ?></th>   
                <th>Status</th>
                <th>Scheduled</th>                    
                <th>View/Edit</th>
                <th class="last">Delete</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($emails as $Email) {
     $data = json_decode($Email->emailDynamicFields(), true);
     	$q = $_GET['q'];
?>
          
            <tr>
                <td><?php echo $HTML->encode($Email->subject()); ?></td>
                <td><?php echo ucwords($HTML->encode($Email->status())); ?></td>
                <td><?php echo ucwords($HTML->encode($Email->scheduledDate())); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/email/add_new?id=<?php echo $HTML->encode(urlencode($Email->id())); ?>">View/Edit</a></td>      
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/email/delete/?id=<?php echo $HTML->encode(urlencode($Email->id())); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>                           
            </tr>
<?php   
    }
?>
        </tbody>
    </table>

<?php    

  #Main Panel End

  $HTML->main_panel_end();