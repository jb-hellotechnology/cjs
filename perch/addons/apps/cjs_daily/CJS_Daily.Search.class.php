<?php

class CJS_Daily_SearchHandler implements PerchAPI_SearchHandler
{

    public static function get_search_sql($key)
    {


		$like = PerchDB::fetch()->pdb('%'.$key.'%');
		$today = date("Y-m-d");

		$sql = "SELECT 'CJS_Daily_SearchHandler' AS source, 2 AS score, title, advertURL, employerDisplay, daily_jobDynamicFields, '', '', '', ''   
        		FROM perch3_daily_jobs 
        		WHERE (title LIKE ".$like." OR employerDisplay LIKE ".$like." OR daily_jobDynamicFields LIKE ".$like.") 
        		AND advertStatus='pub' AND closingDate>'".$today."'";
	    
	    return $sql;
	    
    }

    public static function get_backup_search_sql($key)
    {
	    

		$like = PerchDB::fetch()->pdb('%'.$key.'%');
        $today = date("Y-m-d");

		$sql = "SELECT 'CJS_Daily_SearchHandler' AS source, 2 AS score, title, advertURL, employerDisplay, daily_jobDynamicFields, '', '', '', ''   
        		FROM perch3_daily_jobs 
        		WHERE (title LIKE ".$like." OR employerDisplay LIKE ".$like." OR daily_jobDynamicFields LIKE ".$like.") 
        		AND advertStatus='pub' AND closingDate>'".$today."'";
	    
	    return $sql;
	    
    }

    public static function format_result($key, $options, $result)
    {	
	    
	    //print_r($result);
	    
		$json = json_decode($result['col4']);

        $match = array();
		$match['url'] = $result['col2'];
        $match['title']   = $result['col1']." - ".$result['col3'];
        $match['excerpt'] = strip_tags($json->text->processed);
        $match['key']     = $key;
        return $match;
        
    }

}