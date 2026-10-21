<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
    <div class="textarea flow">
        <h2 id="search">Search by Tag</h2>
        <p>Enter keywords in the box below to be offered suggestions:</p>
        <form method="get" action="#" onkeydown="return event.key != 'Enter';">
            <section class="input">
                <input type="text" id="q" name="q" onkeyup="javascript:get_tags();" placeholder="countryside" />
            </section>
        </form>
        <?php perch_blog_tags(); ?>
    </div>
    <?php perch_content('Form'); ?>
    <?php perch_content('Additional Content'); ?>
</main>
<?php perch_layout('footer'); ?>