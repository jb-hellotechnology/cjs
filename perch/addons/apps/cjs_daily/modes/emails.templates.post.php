<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Email Templates'),
    'button'  => [
          'text' => $Lang->get('New Template'),
          'link' => $API->app_nav().'/email/templates/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Emails',
      'link'  => '/addons/apps/cjs_daily/email/',
  ]);
  
  $Smartbar->add_item([
      'active' => true,
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
                <th class="first"><?php echo $Lang->get('Name'); ?></th>                      
                <th>View/Edit</th>
                <th class="last">Delete</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($templates as $Template) {
?>
          
            <tr>
                <td><?php echo $HTML->encode($Template->name()); ?></td>
                <td><a class="primary" href="<?php echo $HTML->encode($API->app_path()); ?>/email/templates/add_new?id=<?php echo $HTML->encode(urlencode($Template->templateID())); ?>">View/Edit</a></td>      
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/email/templates/delete/?id=<?php echo $HTML->encode(urlencode($Template->templateID())); ?>" class="button button-small action-alert"><?php echo $Lang->get('Delete'); ?></a></td>                           
            </tr>
<?php   
    }
?>
        </tbody>
    </table>

<?php    

  #Main Panel End

  $HTML->main_panel_end();