<?php

/*
 * CJS Page Tags
 *
 * Any page can be given keywords in the "Archive Tags" field in Page Details.
 * The keywords are turned into tag slugs with the same rules Perch Blog uses for
 * post tags, so a standard content page can be listed on /archive/<tag> next to
 * the news items and features carrying that tag.
 *
 * The field is defined in perch/templates/pages/attributes/tags.html.
 */

if (!defined('CJS_PAGE_TAGS_ATTRIBUTE')) define('CJS_PAGE_TAGS_ATTRIBUTE', 'tags');

/*
 * Turn a comma separated keyword string into an array of tag slugs.
 * "Countryside, Volunteering" becomes array('countryside', 'volunteering').
 */
function cjs_page_tag_slugs($str){

  $slugs = array();

  // textarea style fields arrive as an array of raw/processed values
  if(is_array($str)){
    $str = isset($str['raw']) ? $str['raw'] : '';
  }

  if(!is_string($str) || trim($str)==''){
    return $slugs;
  }

  foreach(explode(',', $str) as $keyword){
    $slug = PerchUtil::urlify(trim($keyword));
    if($slug!='' && !in_array($slug, $slugs)){
      $slugs[] = $slug;
    }
  }

  return $slugs;
}

/*
 * The best available one line description for a page, used as the listing excerpt.
 */
function cjs_page_tags_description($attributes){

  foreach(array('description', 'og_description') as $key){

    if(!isset($attributes[$key])) continue;

    $value = $attributes[$key];

    if(is_array($value)){
      if(isset($value['processed'])){
        $value = $value['processed'];
      }elseif(isset($value['raw'])){
        $value = $value['raw'];
      }else{
        $value = '';
      }
    }

    if(is_string($value) && trim($value)!=''){
      return trim($value);
    }
  }

  return '';
}

/*
 * The pages tagged with $tag, as an array of template vars.
 * $tag is a tag slug, as handed over by the /archive/[slug:tag] route.
 *
 * Options: 'sort' (pageTitle, pageNavText, pageModified or pageURL),
 * 'sort-order' (ASC or DESC) and 'count'.
 */
function cjs_page_tags_get_pages($tag, $opts=array()){

  $tag = PerchUtil::urlify(urldecode($tag));
  if($tag=='') return array();

  $default_opts = array(
    'sort'       => 'pageTitle',
    'sort-order' => 'ASC',
    'count'      => false,
  );

  $opts = array_merge($default_opts, $opts);

  $db  = PerchDB::fetch();

  // nav-only pages have no content of their own, and new pages are unpublished
  $sql = 'SELECT pageID, pagePath, pageTitle, pageNavText, pageModified, pageAttributes
            FROM '.PERCH_DB_PREFIX.'pages
           WHERE pageNew=0 AND pageNavOnly=0
             AND pageAttributes LIKE '.$db->pdb('%"'.CJS_PAGE_TAGS_ATTRIBUTE.'"%');

  $rows  = $db->get_rows($sql);
  $pages = array();

  if(PerchUtil::count($rows)){
    foreach($rows as $row){

      $attributes = PerchUtil::json_safe_decode($row['pageAttributes'], true);

      if(!is_array($attributes)) continue;
      if(!isset($attributes[CJS_PAGE_TAGS_ATTRIBUTE])) continue;

      // pages set to "Prevent Access" redirect to the 404, so never list them
      if(isset($attributes['hide_search']) && $attributes['hide_search']=='yes') continue;

      $slugs = cjs_page_tag_slugs($attributes[CJS_PAGE_TAGS_ATTRIBUTE]);

      if(!in_array($tag, $slugs)) continue;

      $pages[] = array(
        'pageID'       => $row['pageID'],
        'pageURL'      => $row['pagePath'],
        'pageTitle'    => $row['pageTitle'],
        'pageNavText'  => ($row['pageNavText']!='' ? $row['pageNavText'] : $row['pageTitle']),
        'pageModified' => $row['pageModified'],
        'pageTags'     => implode(', ', $slugs),
        'excerpt'      => cjs_page_tags_description($attributes),
      );
    }
  }

  if(PerchUtil::count($pages)){

    $sort = isset($pages[0][$opts['sort']]) ? $opts['sort'] : 'pageTitle';

    usort($pages, function($a, $b) use ($sort){
      return strcasecmp((string)$a[$sort], (string)$b[$sort]);
    });

    if(strtoupper($opts['sort-order'])=='DESC'){
      $pages = array_reverse($pages);
    }

    if($opts['count']){
      $pages = array_slice($pages, 0, (int)$opts['count']);
    }
  }

  return $pages;
}

/*
 * Output the pages tagged with $tag through a template, in the style of the
 * perch_* functions. Returns an empty string when nothing is tagged, so the
 * calling page can leave its heading out.
 */
function cjs_page_tags($tag, $opts=array(), $return=false){

  $default_opts = array(
    'template' => 'content/tagged_page_in_list.html',
  );

  $opts  = array_merge($default_opts, $opts);
  $pages = cjs_page_tags_get_pages($tag, $opts);

  if(isset($opts['skip-template']) && $opts['skip-template']){
    return $pages;
  }

  $html = '';

  if(PerchUtil::count($pages)){
    $Template = new PerchTemplate($opts['template'], 'content');
    $html = $Template->render_group($pages, true);
  }

  if($return) return $html;

  echo $html;
}

/*
 * Every tag slug in use on a page, with the number of pages using it, as
 * array('countryside' => array('tagTitle' => 'countryside', 'qty' => 3)).
 * Useful for listing tags that only pages carry, alongside perch_blog_tags().
 */
function cjs_page_tags_all(){

  $db  = PerchDB::fetch();

  $sql = 'SELECT pageAttributes FROM '.PERCH_DB_PREFIX.'pages
           WHERE pageNew=0 AND pageNavOnly=0
             AND pageAttributes LIKE '.$db->pdb('%"'.CJS_PAGE_TAGS_ATTRIBUTE.'"%');

  $rows = $db->get_rows($sql);
  $tags = array();

  if(PerchUtil::count($rows)){
    foreach($rows as $row){

      $attributes = PerchUtil::json_safe_decode($row['pageAttributes'], true);

      if(!is_array($attributes)) continue;
      if(!isset($attributes[CJS_PAGE_TAGS_ATTRIBUTE])) continue;
      if(isset($attributes['hide_search']) && $attributes['hide_search']=='yes') continue;

      foreach(cjs_page_tag_slugs($attributes[CJS_PAGE_TAGS_ATTRIBUTE]) as $slug){
        if(!isset($tags[$slug])){
          $tags[$slug] = array('tagTitle' => $slug, 'tagSlug' => $slug, 'qty' => 0);
        }
        $tags[$slug]['qty']++;
      }
    }
  }

  ksort($tags);

  return $tags;
}
