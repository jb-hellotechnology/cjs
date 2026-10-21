<?php
	$slug  = '/job/'.perch_get('advertdate').'/'.perch_get('adverturl');
	$draft = perch_get('draft');

	/*
	 * Every /job/... URL matches the route, so an unknown slug used to return an
	 * empty 200 page and a closed advert kept its JobPosting markup. Both read
	 * to Google as pages that should not be in the index, so say so with the
	 * status code: 404 for an advert that was never here, 410 for one that has
	 * closed.
	 */
	$job = cjs_job_state($slug, $draft);

	/* Canonical taken from the stored advert URL, not the request, so the same
	   advert reached with a query string or odd casing points at one page. */
	$canonical = ($job['item']!==null && $job['item']['advertURL']!='') ? $job['item']['advertURL'] : $slug;

	if ($job['state']=='missing') {

		http_response_code(404);
		$title       = 'Advert not found: Countryside Jobs Service';
		$description = 'This job advert could not be found. Browse current countryside, conservation and ecology vacancies with CJS.';

	}else{

		if ($job['state']=='closed') http_response_code(410);

		$employer    = employer_name($slug);
		$description = cjs_job_description($slug, $draft);
		$title       = cjs_job_title($slug, $draft);

		$title = "$employer: $title advertised with CJS";
		if ($job['state']=='closed') $title = "$employer: ".cjs_job_title($slug, $draft)." (closed) - CJS";

	}

    # use the variables in the array value
    perch_page_attributes_extend(array(
        'description'    => $description,
        'og_description' => $description,
        'og_title'       => $title,
        'og_type'        => 'article',
        'sharing_image'  => '/Templates/Images/Job-Alert.jpg',
        'og_author'      => 'https://www.facebook.com/CountrysideJobsService/',
    ));
?>

<?php
	perch_layout('header', array(
		'og_title'  => $title,
		'canonical' => $canonical
	));
?>

<?php
  perch_content('Hero');
?>

<main class="flow">
  <?php
    cjs_job($slug,$draft);
  ?>
  <div>
  <?php
    if ($job['state']!='missing') employer_link($slug);
  ?>
    <p>Get jobs like this by email <a href="/jobs/cjs-newsletters">every day</a> or <a href="/jobs/cjs-weekly">weekly</a></p>
  </div>
</main>

<?php perch_layout('footer'); ?>
