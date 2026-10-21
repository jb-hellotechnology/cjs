<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
      <?php perch_content('Content'); ?>
	  <div class="textarea flow news-archive">
      <?php
        $start = date('Y-m-d',strtotime('last monday'));
        $thisDate = $start;
        
        $today = date('Y-m-d');
        $posts = perch_blog_custom(array(
          'sort' => 'postDateTime',
          'sort-order' => 'ASC',
          'count' => 1,
          'blog' => 'news',
          'template' => 'posts/news_post.html',
          'skip-template' => true,
        ));
        
        if(count($posts)>0){
      
        $oldestPost = $posts[0]['postDateTime'];
        $oldestPostParts = explode(" ",$oldestPost);
        $oldestPost = $oldestPostParts[0];
      
        $dateParts = explode("-",$thisDate);
        $year = '';
        $month = '';
        
        if($oldestPost == ''){
	        $oldestPost = date('Y');
        }
      
        while($thisDate>$oldestPost){
          $dateParts = explode("-",$thisDate);
          $thisYear = $dateParts[0];
          $thisMonth = $dateParts[1];
          if($thisYear<>$year){
            echo "<h3>$thisYear</h3>"; 
            $year = $thisYear;
            echo "<ul class=\"archive\">";
          }
          
          if($thisMonth<>$month){
            $monthText = date("F", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
            echo "<li><p><strong>$monthText</strong></p>";
            
            $month = $thisMonth;
            
            $firstMonday = date("d/m", strtotime("first Monday of $year-$month"));
            $secondMonday = date("d/m", strtotime("first Monday of $year-$month + 7 days"));
            $thirdMonday = date("d/m", strtotime("first Monday of $year-$month + 14 days"));
            $fourthMonday = date("d/m", strtotime("first Monday of $year-$month + 21 days"));
            $fourthMondayMonth = date("m", strtotime("first Monday of $year-$month + 21 days"));
            $fifthMonday = date("d/m", strtotime("first Monday of $year-$month + 28 days"));
            $fifthMondayMonth = date("m", strtotime("first Monday of $year-$month + 28 days"));
            
            $firstMondayLink = date("Y-m-d", strtotime("first Monday of $year-$month"));
            $secondMondayLink = date("Y-m-d", strtotime("first Monday of $year-$month + 7 days"));
            $thirdMondayLink = date("Y-m-d", strtotime("first Monday of $year-$month + 14 days"));
            $fourthMondayLink = date("Y-m-d", strtotime("first Monday of $year-$month + 21 days"));
            $fifthMondayLink = date("Y-m-d", strtotime("first Monday of $year-$month + 28 days"));

            echo '<p>';
            if($oldestPost<=$firstMondayLink AND $firstMondayLink<=$today){
              echo '<a href="/news/weeks-news/'.$firstMondayLink.'">'.$firstMonday.'</a>  ';
            }
            if($oldestPost<=$secondMondayLink AND $secondMondayLink<=$today){
              echo '<a href="/news/weeks-news/'.$secondMondayLink.'">'.$secondMonday.'</a>  ';
            }
            if($oldestPost<=$thirdMondayLink AND $thirdMondayLink<=$today){
              echo '<a href="/news/weeks-news/'.$thirdMondayLink.'">'.$thirdMonday.'</a>  ';
            }
            if($oldestPost<=$fourthMondayLink AND $fourthMondayLink<=$today){
              echo '<a href="/news/weeks-news/'.$fourthMondayLink.'">'.$fourthMonday.'</a>   ';
            }
            if($oldestPost<=$fifthMondayLink AND $fifthMondayLink<=$today AND $fifthMondayMonth==$fourthMondayMonth){
              echo '<a href="/news/weeks-news/'.$fifthMondayLink.'">'.$fifthMonday.'</a>';
            }
            echo '</p></li>';
          }
          if($month=='1'){
            echo "</ul>";
          }
          $thisDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2]-7, $dateParts[0]));
        }
      echo "</ul>";
      
      }
      ?>
      
      <h4>2019</h4>
		<p><strong>December</strong></p>
		<p><a href="../NewsArchive/2019/49.html">2/12</a>&nbsp;&nbsp;&nbsp;
		<p><strong>November</strong></p>
		<p><a href="../NewsArchive/2019/45.html">4/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/46.html">11/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/47.html">16/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/48.html">25/11</a></p>
		<p><strong>October</strong></p>
		<p><a href="../NewsArchive/2019/41.html">7/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/42.html">14/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/43.html">21/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/44.html">28/10</a></p>
		<p><strong>September</strong></p>
		<p><a href="../NewsArchive/2019/36.html">2/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/37.html">9/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/38.html">16/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/39.html">23/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/40.html">30/9</a></p>
		<p><strong>August</strong></p>
		<p><a href="../NewsArchive/2019/32.html">5/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/33.html">12/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/34.html">19/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/35.html">26/8</a></p>
		<p><strong>July</strong></p>
		<p><a href="../NewsArchive/2019/27.html">1<strong>/7</strong></a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/28.html">8/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/29.html">15/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/30.html">22/7</a>&nbsp;&nbsp;<a href="../NewsArchive/2019/31.html">29/7</a>&nbsp; </p>
		<p><strong>June</strong></p>
		<p><a href="../NewsArchive/2019/23.html">3/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/24.html">10/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/25.html">17/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/26.html">28/6</a></p>
		<p><strong>May</strong></p>
		<p><a href="../NewsArchive/2019/19.html">6/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/20.html">13/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/21.html">20/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/22.html">27/5</a></p>
		<p><strong>April</strong></p>
		<p><a href="../NewsArchive/2019/14.html">1/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/15.html">8/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/16.html">15/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/17.html">22/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/18.html">29/4</a></p>
		<p><strong>March</strong></p>
		<p><a href="../NewsArchive/2019/10.html">4/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/11.html">11/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/12.html">18/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/13.html">25/3</a></p>
		<p><strong>February</strong></p>
		<p><a href="../NewsArchive/2019/06.html">4/2</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/07.html">11/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/08.html">18/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/09.html">25/2</a></p>
		<p><strong>January</strong></p>
		<p><a href="../NewsArchive/2019/02.html">7/1</a> - includes news from 
		over Christmas break&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/03.html">14/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/04.html">21/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2019/05.html">28/1</a></p>
		<p>&nbsp;</p>
		<h4>Previous years:</h4>
		<h4>2018:</h4>
		<table style="width: 100%">
			<tr>
				<td>
		<p><strong>December</strong></p>
		<p><a href="../NewsArchive/2018/49.html">3/12</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/50.html">10/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/51.html">17/12</a> &lt;last 
		update for 2018</p>
		<p><strong>October</strong></p>
		<p><a href="../NewsArchive/2018/40.html">1/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/41.html">8/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/42.html">15/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/43.html">22/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/44.html">29/10</a></p>
		<p><strong>August</strong></p>
		<p><strong><a href="../NewsArchive/2018/32.html">6/8</a>&nbsp;&nbsp;&nbsp; </strong>
		<a href="../NewsArchive/2018/33.html">13/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/34.html">20/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/35.html">27/8</a></p>
		<p><strong>June</strong></p>
		<p><a href="../NewsArchive/2018/23.html">4/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/24.html">11/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/25.html">18/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/26.html">25/6</a></p>
		<p><strong>April</strong></p>
		<p><a href="../NewsArchive/2018/14.html">3/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/15.html">9/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/16.html">16/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/17.html">23/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/18.html">30/4</a></p>
		<p><strong>February</strong></p>
		<p><a href="../NewsArchive/2018/06.html">5/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/07.html">12/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/08.html">19/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/09.html">26/2</a></p>
				</td>
				<td>
		<p><strong>November</strong></p>
		<p><a href="../NewsArchive/2018/45.html">5/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/46.html">12/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/47.html">19/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/48.html">26/11</a></p>
		<p><strong>September</strong></p>
		<p><a href="../NewsArchive/2018/36.html">3/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/37.html">10/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/38.html">17/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/39.html">24/9</a></p>
		<p><strong>July</strong></p>
		<p><a href="../NewsArchive/2018/27.html">2/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/28.html">9/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/29.html">16/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/30.html">23/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/31.html">30/7</a></p>
		<p><strong>May</strong></p>
		<p><a href="../NewsArchive/2018/19.html">7/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/20.html">14/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/21.html">21/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/22.html">28/5</a></p>
		<p><strong>March</strong></p>
		<p><strong><a href="../NewsArchive/2018/10.html">5/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/11.html">12/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/12.html">19/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/13.html">26/3</a></strong></p>
		<p><strong>January</strong></p>
		<p><a href="../NewsArchive/2018/02.html">8/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/03.html">15/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/04.html">22/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2018/05.html">29/1</a></p>
		<p>News from over the Christmas break
		<a href="../NewsArchive/2018/01.html">here</a></p>
				</td>
			</tr>
		</table>
		<p>&nbsp;</p>
		<h4>2017:</h4>
		<table style="width: 100%">
			<tr>
				<td style="width: 50%">
		<p><strong>December</strong></p>
		<p><strong><a href="../NewsArchive/2017/49.html">4/12</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/50.html">11/12</a>&nbsp;&nbsp;&nbsp; </strong>
		<a href="../NewsArchive/2017/51.html">18/12</a> &lt; last update for 2017</p>
		<p><strong>October</strong></p>
		<p><a href="../NewsArchive/2017/40.html">2/10</a>&nbsp;&nbsp;&nbsp;
		<strong>
		<a href="../NewsArchive/2017/41.html">9/10</a>&nbsp;&nbsp;&nbsp; </strong>
		<a href="../NewsArchive/2017/42.html">16/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/43.html">23/10</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/44.html">30/10</a></p>
		<p><strong>August</strong></p>
		<p><a href="../NewsArchive/2017/32.html">7/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/33.html">14/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/34.html">21/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/35.html">28/8</a></p>
		<p><strong>June</strong></p>
		<p><strong><a href="../NewsArchive/2017/23.html">5/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/24.html">12/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/25.html">19/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/26.html">26/6</a></strong></p>
		<p><strong>April</strong></p>
		<p><a href="../NewsArchive/2017/14.html">3/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/15.html">10/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/16.html">17/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/17.html">24/4</a></p>
		<p><strong>February</strong></p>
		<p><strong><a href="../NewsArchive/2017/06.html">6/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/07.html">13/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/08.html">20/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/09.html">27/2</a></strong></p>
				</td>
				<td>
		<p><strong>November</strong></p>
		<p><a href="../NewsArchive/2017/45.html">6/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/46.html">13/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/47.html">20/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/48.html">27/11</a></p>
		<p><strong>September</strong></p>
		<p><a href="../NewsArchive/2017/36.html">2/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/37.html">11/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/38.html">18/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/39.html">25/9</a></p>
		<p><strong>July</strong></p>
		<p><strong><a href="../NewsArchive/2017/27.html">3/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/28.html">10/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/29.html">17/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/30.html">24/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/31.html">31/7</a></strong></p>
		<p><strong>May</strong></p>
		<p><a href="../NewsArchive/2017/18.html">1/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/19.html">8/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/20.html">15/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/21.html">22/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/22.html">29/5</a></p>
		<p><strong>March</strong></p>
		<p><a href="../NewsArchive/2017/10.html">6/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/11.html">13/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/12.html">20/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/13.html">27/3</a></p>
		<p><strong>January</strong></p>
		<p><a href="../NewsArchive/2017/02.html">9/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/03.html">16/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/04.html">23/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2017/05.html">30/1</a></p>
				</td>
			</tr>
		</table>
		<p>
	<br></p>
		<h4>2016:</h4>
		<table style="width: 100%">
			<tr>
				<td style="width: 50%">
		<p><strong>December</strong></p>
		<p><a href="../NewsArchive/2016/49.html">5/12</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/50.html">12/12</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/51.html">19/12</a> &lt; last update for 2016</p>
		<p><strong>October</strong></p>
		<p><a href="../NewsArchive/2016/40.html">3/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/41.html">10/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/42.html">17/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/43.html">24/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/44.html">31/10</a></p>
		<p><strong>August</strong></p>
		<p><a href="../NewsArchive/2016/31.html">1/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/32.html">8/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/33.html">15/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/34.html">22/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/35.html">29/8</a></p>
		<p><strong>June</strong></p>
		<p><a href="../NewsArchive/2016/23.html">6/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/24.html">13/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/25.html">20/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/26.html">27/6</a></p>
		<p><strong>April</strong></p>
		<p><a href="../NewsArchive/2016/14.html">4/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/15.html">11/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/16.html">18/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/17.html">25/4</a></p>
		<p><strong>February</strong></p>
		<p><a href="../NewsArchive/2016/05.html">1/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/06.html">8/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/07.html">15/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/08.html">22/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/09.html">29/2</a></p>
				</td>
				<td>
		<p><strong>November</strong></p>
		<p><a href="../NewsArchive/2016/45.html">7/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/46.html">14/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/47.html">21/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/48.html">28/11</a></p>
		<p><strong>September</strong></p>
		<p><a href="../NewsArchive/2016/36.html">5/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/37.html">12/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/38.html">19/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/39.html">26/9</a></p>
		<p><strong>July</strong></p>
		<p><a href="../NewsArchive/2016/27.html">4/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/28.html">11/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/29.html">18/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/30.html">25/7</a></p>
		<p><strong>May</strong></p>
		<p><a href="../NewsArchive/2016/18.html">2/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/19.html">9/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/20.html">16/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/21.html">23/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/22.html">30/5</a></p>
		<p><strong>March</strong></p>
		<p><a href="../NewsArchive/2016/10.html">7/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/11.html">14/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/12.html">21/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/13.html">28/3</a></p>
		<p><strong>January</strong></p>
		<p><a href="../NewsArchive/2016/01.html">6/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/02.html">11/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/03.html">18/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2016/04.html">25/1</a></p>
				</td>
			</tr>
		</table>
		<p>&nbsp;</p>
		<h4>2015:</h4>
		<table style="width: 100%">
			<tr>
				<td style="width: 50%">
				<p><strong>December</strong></p>
				<p><a href="../NewsArchive/2015/50.html">7/12</a>&nbsp;&nbsp;
		<a href="../NewsArchive/2015/51.html">14/12</a> &lt; last update for 2015</p>
		<p><strong>October</strong></p>
		<p><a href="../NewsArchive/2015/41.html">5/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/42.html">12/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/43.html">19/10</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/44.html">26/10</a></p>
		<p><strong>August</strong></p>
		<p><a href="../NewsArchive/2015/32.html">3/8</a>&nbsp;
		<a href="../NewsArchive/2015/33.html">10/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/34.html">17/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/35.html">24/8</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/36.html">31/8</a></p>
		<p><strong>June</strong></p>
		<p><a href="../NewsArchive/2015/23.html">1/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/24.html">8/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/25.html">15/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/26.html">22/6</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/27.html">29/6</a></p>
		<p><strong>April</strong></p>
		<p><a href="../NewsArchive/2015/15.html">6/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/16.html">13/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/17.html">20/4</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/18.html">27/4</a></p>
		<p><strong>February</strong></p>
		<p><a href="../NewsArchive/2015/6.html">2/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/7.html">9/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/8.html">16/2</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/9.html">23/2</a></p>
				</td>
				<td>
		<p><strong>November</strong></p>
		<p><a href="../NewsArchive/2015/45.html">2/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/46.html">9/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/47.html">16/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/48.html">23/11</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/49.html">30/11</a></p>
		<p><strong>September</strong></p>
		<p><a href="../NewsArchive/2015/37.html">7/9</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/38.html">14/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/39.html">21/9</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/40.html">28/9</a></p>
		<p><strong>July</strong></p>
		<p><a href="../NewsArchive/2015/28.html">6/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/29.html">13/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/30.html">20/7</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/31.html">27/7</a></p>
		<p><strong>May</strong></p>
		<p><a href="../NewsArchive/2015/19.html">4/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/20.html">11/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/21.html">18/5</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/22.html">25/5</a></p>
		<p><strong>March</strong></p>
		<p><a href="../NewsArchive/2015/10.html">2/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/11.html">9/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/12.html">16/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/13.html">23/3</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/14.html">30/3</a></p>
		<p><strong>January</strong></p>
		<p><a href="../NewsArchive/2015/2.html">5/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/3.html">12/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/4.html">19/1</a>&nbsp;&nbsp;&nbsp;
		<a href="../NewsArchive/2015/5.html">26/1</a></p>
				</td>
			</tr>
		</table>
    
    <p class="center">Links to many of the articles on these pages are no longer current, please proceed with caution. </p>
    <p class="center">CJS is not responsible for content of external sites. Details believed correct but given without prejudice.<br />
    Disclaimer: the views expressed in these news pages do not necessarily reflect the views or policies of CJS.</p>
	</div>
</main>

<?php perch_layout('footer'); ?>