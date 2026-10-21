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

	cjs_jobs_sitemap();

	echo '</urlset>';
?>
