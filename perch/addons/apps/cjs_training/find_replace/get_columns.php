<?php
	 # include the API
    include('../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'cjs_training');

    # include your class files
    include('../CJS_Training.Long_Courses.class.php');
    include('../CJS_Training.Long_Course.class.php');
    
    $courses = new CJS_Training_Long_Courses($API);

    $columns = $courses->get_columns($_POST['table']);
    echo json_encode($columns);