<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <div class="textarea flow">
        <h1><?php echo ucwords(str_replace("-", " ", perch_get('type'))); ?>s</h1>
        <?php
    
            perch_blog_custom([
            'template' => 'post_in_list_archive.html',
            'section'   => perch_get('type'),
            'count'      => 1000,
            'sort'       => 'postDateTime',
            'sort-order' => 'ASC',
            ]);
                
        ?>
        <p>&nbsp;</p>
        <p>Links to many of the articles on these pages are no longer current, please proceed with caution. </p>
        <p>CJS is not responsible for content of external sites. Details believed correct but given without prejudice.<br />
        Disclaimer: the views expressed in these news pages do not necessarily reflect the views or policies of CJS.</p>
    </div>
</main>

<?php perch_layout('footer'); ?>