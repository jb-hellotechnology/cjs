<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
    <?php perch_content('Form'); ?>
    <div class="textarea flow">
    <h2>
      Volunteer Articles
    </h2>
    </div>
    <div class="textarea flow">
    <?php
      perch_blog_custom([
        'blog'       => 'focus-articles',
        'tag'   	 => 'volunteers',
        'template'   => 'focus_archive_list.html',
        'count'      => '100',
        'sort'       => 'postFeedDate',
        'sort-order' => 'desc',
        'filter'     => 'postFeedDate',
        'match'      => 'lte',
        'value'      => date('Y-m-d')
      ]);
    ?>
    <?php perch_content('Additional Content'); ?>
    </div>
</main>

<?php perch_layout('footer'); ?>