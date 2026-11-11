<?php
	/*
	 * /sitemap.xml — an XML sitemap rather than the plain list of URLs this
	 * used to be. Job adverts carry a <lastmod>, which is what Google uses to
	 * decide when a job page is worth recrawling.
	 *
	 * Nothing has been echoed yet at this point, so the header still sends.
	 */
	header('Content-Type: application/xml; charset=utf-8');

	echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

	perch_pages_navigation(array(
	    'template' => 'sitemap.html',
	    'flat'     => true
	));

	/*
	 * The news archive. This was listed when the sitemap was a plain PHP file
	 * at the web root and was dropped when it moved to a Runway route, so none
	 * of the /article/ pages has been in the sitemap since. They are the
	 * largest single group of pages Search Console reports as unindexed.
	 *
	 * No 'blog' key, which matches article.php: /article/<slug> is resolved
	 * against the default blog, so listing posts from any of the other blogs
	 * here would publish URLs that 404.
	 *
	 * 'lte' against a full timestamp rather than 'lt' against a date, so a post
	 * published earlier today is listed instead of waiting for tomorrow, while
	 * anything scheduled ahead still stays out.
	 */
	$posts = perch_blog_custom(array(
	    'filter'        => 'postDateTime',
	    'match'         => 'lte',
	    'value'         => date('Y-m-d H:i:s'),
	    'sort'          => 'postDateTime',
	    'sort-order'    => 'DESC',
	    'count'         => 45000,
	    'skip-template' => true,
	));

	if (is_array($posts)) {
		foreach ($posts as $post) {

			$slug = isset($post['postSlug']) ? trim((string)$post['postSlug']) : '';

			/*
			 * A few posts were saved with an invisible character in the slug:
			 * a narrow no-break space pasted in with the text, a degree sign,
			 * one stray byte-order mark. Those pages do not answer at any
			 * spelling of their address, encoded or not, so listing them would
			 * hand Google a sitemap with known-dead URLs in it. Anything
			 * outside the characters that are safe unescaped in a path is left
			 * out here and wants correcting on the post itself.
			 */
			if ($slug === '' || !preg_match('/^[A-Za-z0-9._~-]+$/', $slug)) continue;

			$date = isset($post['postDateTime']) ? substr($post['postDateTime'], 0, 10) : '';

			echo "\t<url>\n";
			echo "\t\t<loc>https://www.countryside-jobs.com/article/".$slug."</loc>\n";
			if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
				echo "\t\t<lastmod>".$date."</lastmod>\n";
			}
			echo "\t</url>\n";
		}
	}

	cjs_jobs_sitemap();

	echo '</urlset>';
?>
