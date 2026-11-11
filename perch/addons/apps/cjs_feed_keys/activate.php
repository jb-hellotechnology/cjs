<?php
    /*
     * Perch runs this when an app is activated through the control panel. This
     * app is switched on by hand in config/apps.php, so this may never run -
     * the admin page calls CJS_Feed_Keys::install() itself, which does the same
     * thing and is safe to repeat.
     */
    include_once(__DIR__.'/CJS_Feed_Keys.class.php');
    CJS_Feed_Keys::install();
