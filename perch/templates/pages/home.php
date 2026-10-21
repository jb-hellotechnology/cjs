<?php perch_layout('header.home'); ?>

  <main class="flow">

  <?php perch_content('Hero'); ?>
  
  <?php
  
    $date = date('Y-m-d');
  
  ?>
  
  <div class="home-grid">
    
    <div class="flow">
  
      <form id="form2_" action="/jobs/search/results" method="post" role="form" class="compact">
        <h2>Search Jobs</h2>
        <div class="input">
          <label for="form2_textSearch">Find Your Dream Job</label>
          <input id="form2_textSearch" name="textSearch" type="text">
        </div>
        <div class="input">
          <input value="Search" type="submit">
        </div>
        <input id="form2_type" name="type" value="text" type="hidden">
      </form>
      
      <h2><a href="/jobs/areas/">Browse Sectors</a></h2>
      <ul class="pills">
        <?php perch_content('Sectors'); ?>
      </ul>
      
      <p>By <a href="http://www.countryside-jobs.com/jobs/areas">Sector</a>, <a href="http://www.countryside-jobs.com/jobs/browse-by-region">Location</a> or <a href="http://www.countryside-jobs.com/jobs/employers">Employer</a></p><br />
      
      <?php perch_content('CJS Free'); ?>
      
      
        <h2><a href="/news/">Most Popular Articles</a></h2>
        <div class="feed">
      <?php blog_log(); ?>
      </div>

        </div>
        
        <div class="flow">
      
          <h2><a href="/jobs">Jobs</a> and <a href="/volunteers/">Volunteers</a></h2>
          
          <div class="feed auto-height">
            <ul class="plain">
            <?php
            echo home_list();
            ?>
            </ul>
          </div>
      
        </div>
        
        <div class="flow news">
      
      <h2><a href="/news/">Latest News</a></h2>
      <div class="feed">
        <?php
          perch_blog_custom(array(
            'count'      => 1,
            'template'   => 'post_in_list_home.html',
            'blog'       => 'news',
            'sort'       => 'postDateTime',
            'sort-order' => 'DESC',
            'filter'     => 'postDateTime',
            'match'      => 'lte',
            'value'      => "$date"
          ));
        ?>
        <p><strong>More</strong></p>
        <div class="more">
        <?php
          PerchSystem::set_var('class', 'slim');
          perch_blog_custom(array(
            'count'      => 4,
            'template'   => 'post_in_list_home.html',
            'blog'      => 'news',
            'sort'       => 'postDateTime',
            'sort-order' => 'DESC',
            'filter'  => 'postDateTime',
            'match' => 'lte',
            'value' => "$date"
          ));
        ?>
        </div>
          
      </div>
      <h2><a href="/in-depth">Features</a></h2>
      <div class="feed">
        <?php
          PerchSystem::set_var('class', 'panels');
          perch_blog_custom(array(
            'count'      => 4,
            'template'   => 'post_in_list_home.html',
            'blog'      => 'focus-articles',
            'sort'       => 'postFeedDate',
            'sort-order' => 'DESC',
            'filter'  => 'postFeedDate',
            'match' => 'lte',
            'value' => "$date"
          ));
        ?>
      </div>
      <h2><a href="/training/">Training</a></h2>
      <div class="feed">
        <?php
          PerchSystem::set_var('class', 'panels');
          perch_collection('Training Feed Items', [
              'template'   => 'training_feed_item_home.html',
              'sort'       => 'date',
              'sort-order' => 'DESC',
              'count'      => '10'
          ]);
        ?>
      </div>
        
          
      
        </div>
      </div>
      
      <div class="cards home border-top">
        
        <div class="card about flow">
          <?php perch_content('About CJS'); ?>
        </div>
        
        <div class="card flow">
          <h3><a href="http://www.countryside-jobs.com/jobs/cjs-newsletters">CJS Newsletters</a></h3>
          <?php perch_content('Mailing List Form'); ?>
        </div>
        
        <div class="card flow">
          <?php perch_content('Useful Information'); ?>
        </div>
        
        <div class="card flow">
          <?php perch_content('CJS Weekly'); ?>
        </div>
        
        <div class="card flow">
          <?php perch_content('Advertisers'); ?>
        </div>
        
      </div>

  </main>

  <aside class="flow">
    <div class="restrict">
      <div>
        <h3>CJS is endorsed by CMA and SCRA</h3>
        <ul class="inline logos">
          <?php perch_content('Endorsements'); ?>
        </ul>
      </div>
      <div>
        <h3><a href="/information/endorsements">CJS Supports</a></h3>
        <ul class="inline logos">
          <?php perch_content('Supports'); ?>
        </ul>
      </div>
      <div>
        <h3><a href="/charities">Featured Charity</a></h3>
        <ul class="inline logos">
          <?php perch_content('Featured Charity'); ?>
        </ul>
      </div>
    </div>
  </aside>

<?php perch_layout('footer'); ?>