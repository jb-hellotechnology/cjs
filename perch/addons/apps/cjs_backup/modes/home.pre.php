<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 
*/

$HTML 		= $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

function backup_tables($tables = '*')
{
	
	$link = mysqli_connect(PERCH_DB_SERVER, PERCH_DB_USERNAME, PERCH_DB_PASSWORD, PERCH_DB_DATABASE);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysqli_query($link, 'SHOW TABLES');
		while($row = mysqli_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysqli_query($link, 'SELECT * FROM '.$table);
		$num_fields = mysqli_num_fields($result);

		$row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysqli_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//save file
	$filename = 'cjs_db_backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
	$handle = fopen('backups/'.$filename,'w+');
	fwrite($handle,$return);
	fclose($handle);
	$strTo = "jack@jackbarber.co.uk";
	$strSubject = "CJS Perch Database Backup";
	$strMessage = "<p>Download SQL of Backup Here:</p>
				   <p><a href=\"https://dev.countryside-jobs.com/perch/addons/apps/cjs_backup/backups/$filename\">Click here to download the file</a></p>";
	
	//*** Uniqid Session ***//
	$strSid = md5(uniqid(time()));
	
	$strHeader = "";
	$strHeader .= "From: no-reply@countryside-jobs.com\n";
	
	$strHeader .= "MIME-Version: 1.0\n";
	$strHeader .= "Content-Type: multipart/mixed; boundary=\"".$strSid."\"\n";
	$strHeader .= "This is a multi-part message in MIME format.\n";
	
	$strHeader .= "--".$strSid."\n";
	$strHeader .= "Content-type: text/html; charset=windows-874\n"; // or UTF-8 //
	$strHeader .= "Content-Transfer-Encoding: 7bit\n";
	$strHeader .= $strMessage."\n";
	
	$flgSend = mail($strTo,$strSubject,$strMessage,$strHeader) or die ('cannot send mail');  // @ = No Show Error //
}

if ($Form->submitted()){
	
	backup_tables();
	
}