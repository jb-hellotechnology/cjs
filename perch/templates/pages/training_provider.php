<?php perch_layout('header'); ?>
<?php
  perch_content('Hero');
?>

<main>
  <div class="textarea flow">
    <?php 
        $provider = perch_get('providerID');
        $data = get_provider($provider);
        $json = json_decode($data[0]['providerDynamicFields'],true);
        echo "<h1>".$data[0]['providerName']."</h1>";
        echo $json['description']['raw'];
        //GET ORGANISATION_T
        $organisation = organisation_t($data['0']['organisationID']);
    ?>
  </div>
  <?php long_courses_provider($organisation['organisationID']); ?>
  <?php short_courses_provider($organisation['organisationID']); ?>
  <?php events_provider($organisation['organisationID']); ?>
  <?php training_map(); ?>
</main>

<?php perch_layout('footer'); ?>