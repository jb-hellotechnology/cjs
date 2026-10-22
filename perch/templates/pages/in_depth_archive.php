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
        <?php
            // Standard content pages carry their keywords in the "Archive Tags" field
            // in Page Details. The helpers live in an add-on that config/apps.php
            // can't register, because that file is server-specific and never
            // deployed, so load it here instead.
            $cjsPageTagsRuntime = PERCH_PATH.'/addons/apps/cjs_page_tags/runtime.php';
            if(!function_exists('cjs_page_tags') && file_exists($cjsPageTagsRuntime)){
                include_once($cjsPageTagsRuntime);
            }
        
            // Only show the heading if something is tagged.
            if(function_exists('cjs_page_tags')){
                $taggedPages = cjs_page_tags(perch_get('tag'), array(), true);
                if($taggedPages){
                    echo '<h2>Information Pages</h2>';
                    echo $taggedPages;
                }
            }
        ?>
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