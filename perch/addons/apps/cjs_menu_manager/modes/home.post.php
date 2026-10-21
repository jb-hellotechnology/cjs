<?php 

	echo $HTML->title_panel([
    'heading' => $Lang->get('Menus'),
     'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    

    # Main panel
  $HTML->main_panel_start();

    if (PerchUtil::count($cjsmenus)) {
?>
    <table class="d">
        <thead>
            <tr> 
                <th><?php echo $Lang->get('Name'); ?></th>            
                <th> <?php echo $Lang->get('Slug')?></th>
                <th> <?php echo $Lang->get('Delete')?></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($cjsmenus as $cjsMenu) {
?>
            <tr>
                <td><a href="/perch/addons/apps/cjs_menu_manager/add_new/index.php?id=<?php echo $cjsMenu->menuID(); ?>" class="primary"><?php echo $HTML->encode($cjsMenu->name()); ?></a></td>
                <td><?php echo $HTML->encode($cjsMenu->slug()); ?></td>
                <td><a href="/perch/addons/apps/cjs_menu_manager/delete/index.php?id=<?php echo $cjsMenu->menuID(); ?>" class="button button-small action-alert">Delete</a></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
<?php     

    } // if things

  $HTML->main_panel_end();