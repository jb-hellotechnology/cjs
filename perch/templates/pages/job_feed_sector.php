<?php echo '<?xml version="1.0" encoding="UTF-8" ?>';?>
<rss version="2.0" xmlns:cjsFeed="http://countryside-jobs.com/feeds/cjsFeedModule/">

<channel>
  <title>CJS Jobs Feed - <?= ucwords(perch_get('sector')) ?></title>
  <link>https://www.countryside-jobs.com</link>
  <description>Countryside Jobs Specialists</description>
<?php 
	if(perch_get('sector')=='admin'){$sector = 'sAdminIT';}	
	if(perch_get('sector')=='arboriculture'){$sector = 'sArbo';}
	if(perch_get('sector')=='community'){$sector = 'sComVol';}
	if(perch_get('sector')=='countryside'){$sector = 'sCountry';}
	if(perch_get('sector')=='ecology'){$sector = 'sEco';}
	if(perch_get('sector')=='environment'){$sector = 'sEnvOE';}
	if(perch_get('sector')=='fieldwork'){$sector = 'sFieldwork';}
	if(perch_get('sector')=='forestry'){$sector = 'sForestry';}
	if(perch_get('sector')=='horticulture'){$sector = 'sHorti';}
	if(perch_get('sector')=='policy'){$sector = 'sPolicy';}
	if(perch_get('sector')=='land'){$sector = 'sPractical';}
	if(perch_get('sector')=='publicity'){$sector = 'sPubF';}
	if(perch_get('sector')=='rightsofway'){$sector = 'sRoW';}
	if(perch_get('sector')=='visitor'){$sector = 'sVisMan';}
	if(perch_get('sector')=='wildlife'){$sector = 'sWiWoAnC';}
	if(perch_get('sector')=='sustainability'){$sector = 'sSustainability';}	
	cjs_jobs_sector_feed($sector); 
?>
</channel>
</rss>