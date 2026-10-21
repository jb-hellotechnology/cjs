<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$category = perch_get('category');
$section = perch_get('section');
$subsection = perch_get('subsection');

?>
<?php perch_layout('header'); ?>
<main class="flow">
<?php

    switch ($category){
      case 'short-courses':
        if($section){
          perch_content('Short Courses Hero: '.$section);
        }else{
          perch_content('Short Courses Hero');
        }
        break;
      case 'long-courses':
        if($section){
          perch_content('Long Courses Hero: '.$section);
        }else{
          perch_content('Long Courses Hero');
        }
        break;
      case 'distance-learning':
        perch_content('Distance Learning Hero');
        break;
      case 'events':
        perch_content('Events Hero');
        break;
      case 'centres-and-providers':
        perch_content('Centres and Providers Hero');
        break;
    }
    if($category=='' AND $section=='' AND $subsection==''){
      perch_content('Training Hero');
    }

?>
  
    <?php
    switch ($category){
      case 'short-courses':
        echo '<div class="container">';
        perch_content('Short Courses Header');
        if ($section){
          perch_content('Short Courses Intro: '.$section);
          echo '</div>';
          short_courses($section);  
        }else{
          perch_content('Short Courses Intro');
          show_list_short_courses();         
          echo '</div>';
        }
        break;
      case 'long-courses':
        echo '<div class="container">';
        perch_content('Long Courses Header');
        if ($section){
          perch_content('Long Courses Intro: '.$section);
          echo '</div>';
          long_courses($section);
        }else{
          perch_content('Long Courses Intro');
          show_list_long_courses();   
          echo '</div>';
        }
        break;
      case 'distance-learning':
        echo '<div class="container">';
        perch_content('Distance Learning Header');
        echo '</div>';
        long_courses($category);
        break;
      case 'events':
        echo '<div class="container">';
        perch_content('Events Header');
        echo '</div>';
        get_events();
        break;
      case 'centres-and-providers':
        echo '<div class="container">';
        perch_content('Centres and Providers Header');
        echo '</div>';
        get_centres_providers();
        break;
    }
    if($category=='' AND $section=='' AND $subsection==''){
      echo '<div class="container">';
      perch_content('Training Content');
      echo '</div>';
    }
    ?>
</main>
<?php perch_layout('footer'); ?>