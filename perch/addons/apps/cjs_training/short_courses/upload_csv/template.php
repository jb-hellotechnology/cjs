<?php

    /*
        Sends the blank CSV template for the Short Courses importer. Built from
        CJS_Training_Short_Courses::$csv_columns rather than a file on disk, so the header row
        can never drift out of step with the column order the importer expects.
    */

    # include the API - this also puts the download behind the Perch login
    include('../../../../../core/inc/api.php');

    $API  = new PerchAPI(1.0, 'cjs_training');

    # include your class files
    include('../../CJS_Training.Short_Courses.class.php');
    include('../../CJS_Training.Short_Course.class.php');

    $SCourses = new CJS_Training_Short_Courses($API);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="short-courses-template.csv"');

    $out = fopen('php://output', 'w');
    fputcsv($out, $SCourses->csv_columns);
    fclose($out);
    exit;
