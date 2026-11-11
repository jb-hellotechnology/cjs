<?php

    include('../../../core/inc/api.php');

    $API = new PerchAPI(1.0, 'cjs_feed_keys');

    include('CJS_Feed_Keys.class.php');

    $Lang = $API->get('Lang');

    $Perch->page_title = $Lang->get('Feed Keys');

    include('modes/home.pre.php');

    include(PERCH_CORE.'/inc/top.php');

    include('modes/home.post.php');

    include(PERCH_CORE.'/inc/btm.php');
