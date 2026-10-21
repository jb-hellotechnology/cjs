<?php //session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>
    <?php 
      if (perch_layout_has('og_title')) {
        perch_layout_var('og_title');
      }else{
        perch_pages_title();
      } 
    ?>
  </title>

  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

  <link rel="preconnect" href="https://www.google-analytics.com" crossorigin />
  <link rel="preconnect" href="https://use.typekit.net" crossorigin />
  
  <link rel="stylesheet" href="https://use.typekit.net/igp6ehm.css">
  
  <link href="/assets/css/reset.css?v=<?php echo rand(); ?>"  media="screen" rel="stylesheet" type="text/css" />
  <link href="/assets/css/type.css?v=<?php echo rand(); ?>"  media="screen" rel="stylesheet" type="text/css" />
  <link href="/assets/css/layout.css?v=<?php echo rand(); ?>"  media="screen" rel="stylesheet" type="text/css" />
  <link href="/assets/css/styles.css?v=<?php echo rand(); ?>"  media="screen" rel="stylesheet" type="text/css" />
  
  
  <link href="/assets/css/print.css"  media="print" rel="stylesheet" type="text/css" />

  <?php
    $domain        = 'https://'.$_SERVER["HTTP_HOST"];
    $url           = $domain.$_SERVER["REQUEST_URI"];
    $sitename      = "Countryside Jobs Service";
    $twittername   = "@CountrysideJobs";
    $sharing_image = '/assets/images/og.jpg';

    PerchSystem::set_var('domain',$domain);
    PerchSystem::set_var('url',$url);
    PerchSystem::set_var('sharing_image',$sharing_image);
    PerchSystem::set_var('twittername',$twittername);
  
    perch_page_attributes(array(        
      'template' => 'default.html'    
    ));

    cjs_canonical_tag($domain);
  ?>
    
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-DGBECVMGKJ"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
  
    gtag('config', 'G-DGBECVMGKJ');
  </script>

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  
  ga('create', 'UA-25107716-1', 'auto');
  ga('send', 'pageview');
  </script>
  <!-- End Google Analytics --> 
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body class="<?php
    perch_page_attribute('bodyclass', array(
        'template' => 'body_attributes.html'
    ));
    ?>">
    
  <header class="primary flow">
    <div class="masthead">
      <a href="/"><img src="https://www.countryside-jobs.com/assets/images/CJS_LOGO_2022_BLACK.png" alt="Countryside Jobs Service Icon" class="logo" /></a>
      <div>
        <ul class="inline">
          <li><a href="/jobs">Jobs</a></li>
          <li><a href="/advertise">Advertise</a></li>
        </ul>
        <form id="form1_search" action="/search" method="get">
          <div>
          <label for="form1_q">Search</label>
          <input id="form1_q" name="q" placeholder="Search" type="text">
          <input value="Go" type="submit">
          </div>
        </form> 	
      </div>
      <button id="menu"><i class="fa fa-bars"></i><span> Menu</span></button>
    </div>
    <nav class="mobile border-top border-bottom">
      <div class="">
      <?php 
        $navgroup = perch_page_attribute('navgroup', array(
            'template' => 'body_attributes.html'
        ),true);
        
        if($navgroup==''){
          $navgroup = 'main';
        }
        
        cjs_menu_manager_menu($navgroup);
        cjs_menu_manager_sub_menu($navgroup);
      ?>
      </div>
    </nav>
    <div class="restrict">
      <img src="https://www.countryside-jobs.com/assets/images/cjs_heading.svg" alt="Countryside Jobs Service Heading" class="logo main" />
      <p>CJS is an <a href="https://www.countryside-jobs.com/information/about-us#social">ethical business</a> working in harmony with environmental professionals to conserve the British countryside and natural world. Motivated by conservation success not profits.</p>
    </div>
  </header>
  
  <nav class="desktop border-top border-bottom">
    <div class="">
      <a href="/" class="home"></a>
    <?php 
      $navgroup = perch_page_attribute('navgroup', array(
          'template' => 'body_attributes.html'
      ),true);
      
      if($navgroup==''){
        $navgroup = 'main';
      }
      
      cjs_menu_manager_menu($navgroup);
      cjs_menu_manager_sub_menu($navgroup);
    ?>
    </div>
  </nav>