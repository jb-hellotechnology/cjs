<?php 
perch_layout('header', array(
    'og_title' => 'CJS \''.ucwords(str_replace("-", " ", perch_get('tag'))).'\' Archive',
    'og_description' => 'Archive of all features and news items tagged \''.ucwords(str_replace("-", " ", perch_get('tag'))).'\' from the Countryside Jobs Service website.'
)); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <div class="textarea flow">
        <h1>Items Tagged '<?php echo ucwords(str_replace("-", " ", perch_get('tag'))); ?>'</h1>
        <?php perch_content('Content'); ?>
        <h2>Features</h2>
        <?php
            perch_blog_custom([
            'tag'   	 => perch_get('tag'),
            'blog'      => 'focus-articles',
            'template' => 'post_in_list_archive.html',
            'count'      => 1000,
            'sort'       => 'postDateTime',
            'sort-order' => 'ASC',
            ]);
                
        ?>
        <h2>News</h2>
        <?php
    
            perch_blog_custom([
            'tag'   	 => perch_get('tag'),
            'blog'      => 'news',
            'template' => 'post_in_list_archive.html',
            'count'      => 1000,
            'sort'       => 'postDateTime',
            'sort-order' => 'ASC',
            ]);
        ?>
        
        <p>Links to many of the articles on these pages are no longer current, please proceed with caution. </p>
        <p>CJS is not responsible for content of external sites. Details believed correct but given without prejudice.<br />
        Disclaimer: the views expressed in these news pages do not necessarily reflect the views or policies of CJS.</p>
    </div>
</main>

<?php perch_layout('footer'); ?>