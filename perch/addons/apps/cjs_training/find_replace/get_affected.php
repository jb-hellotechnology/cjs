<?php
	 # include the API
    include('../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_training');

    # include your class files
    include('../CJS_Training.Long_Courses.class.php');
    include('../CJS_Training.Long_Course.class.php');
    
    $courses = new CJS_Training_Long_Courses($API);
    
    $table = $_POST['table'];
    $where = $_POST['where'];
    $selector = $_POST['selector'];
    $value = $_POST['value'];
    
    if($selector=='LIKE'){
	    $q = "$where LIKE '%$value%'";
    }else{
	    $q = "$where $selector $value";
    }
    
    $query = "SELECT * FROM $table WHERE $q";

    $count = $courses->get_count($query);
    echo $count;