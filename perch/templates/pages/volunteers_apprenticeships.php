<?php perch_layout('header'); ?>
<?php perch_content('Hero'); ?>

<main class="flow">
    <?php
      perch_content('Content');
    ?>
    <?php
      apps_ints();
      vol_apps_ints();
    ?>
</main>

<?php perch_layout('footer'); ?>