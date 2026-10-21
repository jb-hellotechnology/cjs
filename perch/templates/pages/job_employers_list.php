<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
  <?php perch_content('Content'); ?>
  <div class="textarea flow">
    <h2 id="search">Search by Name</h2>
    <p>Enter keywords in the box below to be offered suggestions:</p>
    <form method="get" action="#">
      <section class="input">
        <input type="text" id="q" name="q" onkeyup="javascript:get_employers();" placeholder="CJS" />
      </section>
    </form>
    <?php
        list_advertisers_jobs_vols();
    ?>
  </div>
</main>

<?php perch_layout('footer'); ?>