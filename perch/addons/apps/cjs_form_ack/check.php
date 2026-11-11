<?php

/*
 * Diagnostic page for the acknowledgement emails. Reports what is actually on
 * this server, because "it works locally" tells you nothing about a Perch
 * install with a cached regionHTML snapshot, a config file outside the
 * repository and its own SMTP credentials.
 *
 *   /perch/addons/apps/cjs_form_ack/check.php?secret=SECRET
 *
 * SECRET is PERCH_SCHEDULE_SECRET from perch/config (or, if that is not
 * defined on this server, PERCH_LICENSE_KEY). Without it the page 404s, so it
 * is safe to leave deployed, but it still tells anyone holding the secret a
 * fair amount about the install.
 *
 * Add &send=you@example.com to actually send a test acknowledgement, which is
 * the only way to tell a wiring problem from a delivery problem. &form=<id>
 * picks which form's wording to use; it defaults to cjs_online.
 *
 * Nothing here writes anything. The only side effect is the test email.
 */

$runtime = dirname(__DIR__, 3).'/runtime.php';

header('Content-Type: text/plain; charset=utf-8');

if (!file_exists($runtime)) {
    echo "Cannot find perch/runtime.php relative to ".__DIR__."\n";
    exit;
}

include($runtime);

$expected = defined('PERCH_SCHEDULE_SECRET') && PERCH_SCHEDULE_SECRET !== ''
                ? PERCH_SCHEDULE_SECRET
                : (defined('PERCH_LICENSE_KEY') ? PERCH_LICENSE_KEY : false);

$given = isset($_GET['secret']) ? (string) $_GET['secret'] : '';

if ($expected === false || $given === '' || !hash_equals((string) $expected, $given)) {
    http_response_code(404);
    echo "Not found\n";
    exit;
}

$fails = 0;

function heading($text)
{
    echo "\n".$text."\n".str_repeat('-', strlen($text))."\n";
}

function result($ok, $label, $detail = '')
{
    global $fails;
    if ($ok === false) $fails++;
    $mark = $ok === true ? ' ok  ' : ($ok === false ? 'FAIL ' : '  -  ');
    echo $mark.str_pad($label, 52).' '.$detail."\n";
}

echo "cjs_form_ack diagnostic - ".date('j M Y H:i')."\n";

/* ------------------------------------------------------------------ */
heading('Environment');

result(null, 'PHP', PHP_VERSION);
result(null, 'Perch path', PERCH_PATH);
result(null, 'Template path', PERCH_TEMPLATE_PATH);

/* ------------------------------------------------------------------ */
heading('1. Is the app installed and loading?');

$apps_file = PERCH_PATH.'/config/apps.php';
$listed    = file_exists($apps_file) && strpos(file_get_contents($apps_file), 'cjs_form_ack') !== false;

result($listed, 'cjs_form_ack in config/apps.php',
       $listed ? '' : "add 'cjs_form_ack', to the \$apps_list array in ".$apps_file);

foreach (['runtime.php', 'forms.php', 'CJS_Form_Ack.class.php'] as $file) {
    result(file_exists(__DIR__.'/'.$file), 'app file '.$file, __DIR__.'/'.$file);
}

result(function_exists('cjs_form_ack_form_handler'), 'cjs_form_ack_form_handler() exists',
       function_exists('cjs_form_ack_form_handler') ? '' : 'the app runtime is not being loaded');
result(function_exists('cjs_form_ack_send'), 'cjs_form_ack_send() exists',
       function_exists('cjs_form_ack_send') ? '' : 'the app runtime is not being loaded');

if (!class_exists('CJS_Form_Ack')) {
    if (file_exists(__DIR__.'/CJS_Form_Ack.class.php')) include_once(__DIR__.'/CJS_Form_Ack.class.php');
}

$config = class_exists('CJS_Form_Ack') ? CJS_Form_Ack::config() : [];
result(count($config) > 0, 'forms.php loads', count($config).' forms configured');

/* ------------------------------------------------------------------ */
heading('2. Is the email template on this server?');

$tpl = PERCH_TEMPLATE_PATH.'/emails/cjs-form-acknowledgement.html';
result(file_exists($tpl), 'email template file', $tpl);

if (!file_exists($tpl)) {
    echo "\n      Without it PerchEmail builds an empty message, which either fails\n"
       . "      silently or arrives blank and gets filtered as spam. It lives\n"
       . "      outside the app folder, so uploading only the app folder misses it.\n";
}

/* ------------------------------------------------------------------ */
heading('3. Did the hook calls reach the server?');

/* These live in existing apps, not in the cjs_form_ack folder, and are what
   fires the acknowledgement for the five reCAPTCHA-gated forms. */
$hooks = [
    'cjs_daily/CJS_Daily.Jobs.class.php'            => 3,
    'cjs_workdays/CJS_Workdays.Work_Days.class.php' => 1,
    'cjs_organisations/CJS_Organisations.Orgs.class.php' => 1,
];

foreach ($hooks as $rel => $want) {
    $path  = PERCH_PATH.'/addons/apps/'.$rel;
    $found = file_exists($path) ? substr_count(file_get_contents($path), 'cjs_form_ack_send(') : 0;
    result($found >= $want, $rel, $found.' of '.$want.' call(s)');
}

/* ------------------------------------------------------------------ */
heading('4. Form templates, and whether the pages were republished');

/* Perch serves a cached snapshot of each region. The app attribute is baked
   into that snapshot, so a template edit does nothing until the page is
   republished. Forms fired from a handler do not care either way. */

$DB     = PerchDB::fetch();
$rows   = $DB->get_rows('SELECT regionID, regionPage, regionTemplate, regionHTML FROM '.PERCH_DB_PREFIX."content_regions WHERE regionTemplate LIKE '%forms/%'");
$byTpl  = [];
foreach ($rows as $row) $byTpl[basename($row['regionTemplate'])][] = $row;

printf("%-40s %-18s %-10s %s\n", 'template', 'in app attribute', 'fired by', 'pages still showing the old snapshot');

foreach ($config as $id => $form) {
    foreach (glob(PERCH_TEMPLATE_PATH.'/content/forms/*.html') as $path) {
        $src = file_get_contents($path);
        if (!preg_match('/<perch:form\b[^>]*\bid="'.preg_quote($id, '/').'"[^>]*>/', $src, $m)) continue;

        $name    = basename($path);
        $in_attr = strpos($m[0], 'cjs_form_ack') !== false;
        $hooked  = in_array($name, ['cjs_online.html','cjs_free.html','cjs_volunteer.html',
                                    'cjs_volunteerorganisation_survey.html','cjs_organisation.html']);

        $stale = [];
        if ($in_attr && isset($byTpl[$name])) {
            foreach ($byTpl[$name] as $row) {
                if (strpos($row['regionHTML'], 'cjs_form_ack') === false) $stale[] = $row['regionPage'];
            }
        }

        printf("%-40s %-18s %-10s %s\n", $name,
               $in_attr ? 'yes' : 'no',
               $hooked ? 'handler' : 'app attr',
               $stale ? 'REPUBLISH: '.implode(', ', $stale) : ($in_attr ? 'up to date' : ''));

        if ($stale) $fails++;
    }
}

/* ------------------------------------------------------------------ */
heading('5. Can this server build the message?');

if (class_exists('CJS_Form_Ack') && file_exists($tpl)) {
    $API   = new PerchAPI(1.0, 'cjs_form_ack');
    $Email = $API->get('Email');
    $Email->set_template(CJS_Form_Ack::TEMPLATE);
    $Email->subject('test');
    $Email->set_bulk(CJS_Form_Ack::vars('cjs_online',
        ['email' => 'test@example.com', 'name' => 'Test Person', 'organisation' => 'Test Org'],
        'test@example.com'));

    $r  = new ReflectionClass('PerchEmail');
    $bm = $r->getMethod('build_message');
    $bm->setAccessible(true);
    $body = (string) $bm->invoke($Email);

    result(strlen($body) > 500, 'message body builds', strlen($body).' bytes');
    result(!preg_match('/\$\w+/', $body, $um), 'all variables substituted',
           isset($um[0]) ? 'left over: '.$um[0] : '');
    result(trim($Email->subject()) !== '', 'subject read from <title>', $Email->subject());
} else {
    result(false, 'message body builds', 'skipped, app or template missing');
}

/* ------------------------------------------------------------------ */
heading('6. Can this server reach the mail server?');

result(null, 'method', PERCH_EMAIL_METHOD);
result(null, 'host', PERCH_EMAIL_HOST.':'.PERCH_EMAIL_PORT.' ('.(PERCH_EMAIL_SECURE ?: 'no encryption').')');
result(null, 'username', PERCH_EMAIL_USERNAME);
result(null, 'from', PERCH_EMAIL_FROM);

if (strtolower(PERCH_EMAIL_METHOD) === 'smtp' && class_exists('PHPMailer')) {
    $mail = new PHPMailer(false);
    $mail->IsSMTP();
    $mail->Host       = PERCH_EMAIL_HOST;
    $mail->Port       = PERCH_EMAIL_PORT;
    $mail->SMTPAuth   = PERCH_EMAIL_AUTH;
    $mail->Username   = PERCH_EMAIL_USERNAME;
    $mail->Password   = PERCH_EMAIL_PASSWORD;
    $mail->SMTPSecure = PERCH_EMAIL_SECURE;
    $mail->Timeout    = 10;

    $connected = false;
    try {
        $connected = $mail->smtpConnect();
    } catch (Exception $e) {
        $mail->ErrorInfo = $e->getMessage();
    }

    result((bool) $connected, 'SMTP connect and authenticate', $connected ? '' : $mail->ErrorInfo);
    if ($connected) $mail->smtpClose();
}

/* ------------------------------------------------------------------ */
heading('7. Recent job adverts');

/* An advert saved as a draft never emails anybody - not the advertiser and not
   CJS - because cjs_daily only sends on pen/pub/over. If the submission under
   investigation is not listed here with one of those statuses, that is the
   answer, and nothing is wrong with the acknowledgement. */

$jobs = $DB->get_rows('SELECT daily_jobID, title, advertStatus, postedDate FROM '.PERCH_DB_PREFIX.'daily_jobs ORDER BY daily_jobID DESC LIMIT 8');

foreach ($jobs as $job) {
    printf("  #%-7s %-10s %-12s %s\n", $job['daily_jobID'], $job['advertStatus'], $job['postedDate'], substr($job['title'], 0, 50));
}
echo "\n  draft = no email was due to anyone. pen / pub / over = CJS was emailed,\n  so the acknowledgement should have gone out at the same moment.\n";

/* ------------------------------------------------------------------ */
heading('8. What the error log says');

/* CJS_Form_Ack writes every failure to the PHP error log, prefixed
   [cjs_form_ack]. If the acknowledgement was attempted and failed, it is here;
   if there is nothing here at all, it was never attempted. */

$log = ini_get('error_log');

if ($log && is_readable($log)) {
    $lines = [];
    foreach (array_slice(file($log), -4000) as $line) {
        if (strpos($line, 'cjs_form_ack') !== false) $lines[] = rtrim($line);
    }
    $lines = array_slice($lines, -15);

    result(null, 'log file', $log);
    echo $lines ? '  '.implode("\n  ", $lines)."\n"
                : "  No [cjs_form_ack] lines, so no acknowledgement has failed. Combined\n"
                 ."  with a 'pen' or 'pub' advert above, that means the call never ran.\n";
} else {
    result(null, 'log file', $log ? $log.' (not readable from here)' : 'not set in php.ini - check the host control panel');
}

/* ------------------------------------------------------------------ */
heading('9. Send a real test?');

if (isset($_GET['send']) && filter_var($_GET['send'], FILTER_VALIDATE_EMAIL)) {
    $to   = $_GET['send'];
    $form = isset($_GET['form']) && isset($config[$_GET['form']]) ? $_GET['form'] : 'cjs_online';

    $sent = cjs_form_ack_send($form, [
        'email'        => $to,
        'contactEmail' => $to,
        'name'         => 'Test Person',
        'organisation' => 'Test Organisation',
    ]);

    result((bool) $sent, 'test acknowledgement sent', $sent ? $form.' -> '.$to : 'see the PHP error log for [cjs_form_ack]');
} else {
    echo "  Add &send=you@example.com to send one (optionally &form=cjs_free etc).\n";
}

/* ------------------------------------------------------------------ */
echo "\n".($fails ? $fails." check(s) failed - see FAIL above." : "All checks passed.")."\n";
