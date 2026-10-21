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
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
   integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
   crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
   integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
   crossorigin=""></script>
   
   <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <script src="/assets/plugins/redactor/redactor.min.js" crossorigin="anonymous"></script>
   <script
     src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATUBEmTAVMlmQUpMKKD95ic8B62ECXOQo&callback=initMap&v=weekly"
     defer
   ></script>
   
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   
   <script src="/assets/plugins/redactor/redactor.min.js"></script>
  <script src="/assets/plugins/redactor/plugins/counter.js"></script>
  <script src="/assets/plugins/redactor/plugins/limiter.js"></script>
  <link rel="stylesheet" href="/assets/plugins/redactor/redactor.css" />
    
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
</head>
<body class="<?php
    perch_page_attribute('bodyclass', array(
        'template' => 'body_attributes.html'
    ));
    ?>">
    
  <header class="primary flow">
    <div class="masthead">
      <a href="/"><img src="https://www.countryside-jobs.com/assets/images/cjs.gif" alt="Countryside Jobs Service Icon" class="logo" /></a>
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
  </header>
  
  <nav class="border-top border-bottom">
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