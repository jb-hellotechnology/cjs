<?php

class CJS_Workdays_Survey_SearchHandler implements PerchAPI_SearchHandler
{

    public static function get_search_sql($key)
    {


		$like = PerchDB::fetch()->pdb('%'.$key.'%');
		$sql = "SELECT 'CJS_Workdays_Survey_SearchHandler' AS source, 2 AS score, Title, provider, surveyDynamicFields, section, surveyID, '', '', ''  
        		FROM perch3_surveys 
        		WHERE (Title LIKE ".$like." OR provider LIKE ".$like." OR surveyDynamicFields LIKE ".$like.") 
        		AND advertStatus='pub'";
	    
	    return $sql;
	    
    }

    public static function get_backup_search_sql($key)
    {
	    

		$like = PerchDB::fetch()->pdb('%'.$key.'%');
        $sql = "SELECT 'CJS_Workdays_Survey_SearchHandler' AS source, 1 AS score, Title, provider, surveyDynamicFields, section, '', '', '', ''  
        		FROM perch3_surveys 
        		WHERE (Title LIKE ".$like." OR provider LIKE ".$like." OR surveyDynamicFields LIKE ".$like.") 
        		AND advertStatus='pub'";
	    
	    return $sql;
	    
    }

    public static function format_result($key, $options, $result)
    {	
		$json = json_decode($result['col3']);

        $match = array();
		$match['url'] = '/workdays/surveys/'.$result['col4'].'#S'.$result['col5'];
        $match['title']   = $result['col1'];
        $match['excerpt'] = strip_tags($json->description->processed);
        $match['key']     = $key;
        return $match;
        
    }

}