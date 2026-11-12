<?php

/*
 * Sends the person who filled in a form an email saying it arrived.
 *
 * One class for every form on the site, whichever app actually handles the
 * submission: the advertising forms are spread across perch_forms, cjs_daily,
 * cjs_training, cjs_workdays and cjs_organisations, and each of those did its
 * own thing with mail(). Keeping the acknowledgement in one place means the
 * wording, the sender and the reply-to behave the same everywhere, and adding a
 * form is an entry in forms.php rather than another block of inline HTML.
 *
 * Nothing here ever stops a submission. Every failure is logged and swallowed:
 * an advertiser whose advert is safely in the database must not see a 500
 * because their mail server was slow.
 */
class CJS_Form_Ack
{
    /* Where the email body comes from, relative to perch/templates. */
    const TEMPLATE = 'emails/cjs-form-acknowledgement.html';

    /* Seconds to wait for the mail host to accept a connection before giving
       up on the acknowledgement. Deliberately short: this runs inside an
       advertiser's form submission. */
    const CONNECT_TIMEOUT = 5;

    /* Form ids already acknowledged in this request. A form listed in the app
       attribute AND wired up in its handler would otherwise send twice. */
    private static $sent = [];

    private static $config = null;

    /*
     * $formID  the id attribute from <perch:form>
     * $values  submitted values, keyed by field id (PerchAPI_SubmittedForm->data)
     *
     * Returns true only if an email was handed to PHPMailer without error.
     */
    public static function send($formID, $values)
    {
        $formID = (string) $formID;

        if (isset(self::$sent[$formID])) {
            PerchUtil::debug('cjs_form_ack: already acknowledged '.$formID.' in this request.', 'notice');
            return false;
        }

        $config = self::config();

        if (!isset($config[$formID])) {
            PerchUtil::debug('cjs_form_ack: no acknowledgement configured for form "'.$formID.'".', 'notice');
            return false;
        }

        if (!is_array($values) || !count($values)) return false;

        $form  = $config[$formID];
        $email = self::first_email($values, $form['email_field']);

        if (!$email) {
            self::log($formID.': no usable email address in '.implode(', ', $form['email_field']).', nothing sent.');
            return false;
        }

        self::$sent[$formID] = true;

        $name     = isset($form['name_field']) ? self::first_value($values, $form['name_field']) : '';
        $vars     = self::vars($formID, $values, $email);
        $reply_to = self::reply_to($form);

        try {
            $API   = new PerchAPI(1.0, 'cjs_form_ack');
            $Email = $API->get('Email');

            $Email->set_template(self::TEMPLATE);
            $Email->template_method('dollar');

            $Email->subject($form['subject']);
            $Email->senderName('Countryside Jobs Service');
            $Email->senderEmail(PERCH_EMAIL_FROM);
            $Email->replyToEmail($reply_to);
            $Email->recipientEmail($email);
            if ($name !== '') $Email->recipientName($name);

            $Email->set_bulk($vars);

            /* The claim at the top of this file - that nothing here can stop a
               submission - was not actually true. $Email->send() opens an SMTP
               connection inside the advertiser's request, and PHPMailer 5.2
               waits 300 seconds for it. A mail host that is slow, filtered or
               refusing connections therefore hangs the request until PHP's
               max_execution_time kills it, and the advertiser sees a 500 on an
               advert that has already been written to the database.

               So: knock on the port first, with a short timeout, and give up
               quietly if nothing answers. An acknowledgement that does not
               arrive is a nuisance; an advert submission that dies is not. */
            if (!self::mail_host_reachable()) {
                self::log($formID.': '.PERCH_EMAIL_HOST.':'.PERCH_EMAIL_PORT.' did not answer within '
                          .self::CONNECT_TIMEOUT.'s, acknowledgement skipped.');
                return false;
            }

            $sent = $Email->send();

            if (!$sent) {
                self::log($formID.': send failed for '.$email.'. '.(isset($Email->errors) ? $Email->errors : ''));
                return false;
            }

            /* Logged on success as well as failure, and deliberately so: without
               it the error log cannot tell "the acknowledgement was never
               attempted" from "it was attempted and worked", which is the first
               thing you need to know when someone says they did not get one.
               These forms run at a few submissions a day, so it is not noise. */
            PerchUtil::debug('cjs_form_ack: acknowledged '.$formID.' to '.$email, 'success');
            error_log('[cjs_form_ack] '.$formID.': acknowledged to '.$email);

            return true;

        } catch (Exception $e) {
            self::log($formID.': '.$e->getMessage());
            return false;
        } catch (Throwable $e) {
            self::log($formID.': '.$e->getMessage());
            return false;
        }
    }

    /*
     * True if the submission tripped the honeypot field, so callers coming in
     * through the form-handler dispatch can drop it without sending anything.
     */
    public static function is_spam($SubmittedForm)
    {
        if (!is_object($SubmittedForm)) return false;

        $antispam = $SubmittedForm->get_antispam_values();

        if (is_array($antispam) && isset($antispam['honeypot']) && trim($antispam['honeypot']) !== '') {
            PerchUtil::debug('cjs_form_ack: honeypot completed, no acknowledgement sent.', 'notice');
            return true;
        }

        return false;
    }

    /*
     * Everything the email template needs, already escaped. Public so that the
     * template can be rendered and checked without sending anything — see
     * test.php.
     */
    public static function vars($formID, $values, $email = '')
    {
        $config = self::config();
        $form   = $config[$formID];

        $name = isset($form['name_field']) ? self::first_value($values, $form['name_field']) : '';
        $org  = isset($form['org_field'])  ? self::first_value($values, $form['org_field'])  : '';

        return [
            /* PerchEmail reads the subject back out of <title> once the
               variables are in, so email_subject has to be set for it. */
            'email_subject' => self::esc($form['subject']),
            'heading'       => self::esc($form['heading']),
            'greeting'      => $name !== '' ? 'Hello '.self::esc($name).',' : 'Hello,',
            'intro'         => self::esc($form['intro']),
            'next_steps'    => self::list_html(isset($form['next']) ? $form['next'] : []),
            'details'       => self::details_html($form['label'], $name, $org, $email),
            'reply_to'      => self::esc(self::reply_to($form)),
            'year'          => date('Y'),
        ];
    }

    private static function reply_to($form)
    {
        if (isset($form['reply_to']) && trim($form['reply_to']) !== '') return trim($form['reply_to']);

        return PERCH_EMAIL_FROM;
    }

    public static function config()
    {
        if (self::$config === null) {
            $config = include(__DIR__.'/forms.php');
            self::$config = is_array($config) ? $config : [];
        }

        return self::$config;
    }

    /* The first field in $keys holding something that looks like an address. */
    private static function first_email($values, $keys)
    {
        foreach ($keys as $key) {
            if (!isset($values[$key])) continue;

            $candidate = trim(self::flatten($values[$key]));

            if ($candidate === '') continue;

            /* People paste "Jane Smith <jane@example.com>" and trailing commas. */
            if (preg_match('/<([^>]+)>/', $candidate, $m)) $candidate = trim($m[1]);
            $candidate = trim($candidate, " \t\n\r,;");

            if (filter_var($candidate, FILTER_VALIDATE_EMAIL)) return $candidate;
        }

        return false;
    }

    private static function first_value($values, $keys)
    {
        foreach ($keys as $key) {
            if (!isset($values[$key])) continue;

            $candidate = trim(self::flatten($values[$key]));

            if ($candidate !== '') return $candidate;
        }

        return '';
    }

    /* Checkbox groups and multi-selects arrive as arrays. */
    private static function flatten($value)
    {
        if (is_array($value)) return implode(', ', array_map('strval', $value));

        return (string) $value;
    }

    /*
     * PerchEmail makes the plain text alternative with strip_tags and nothing
     * else, so the newlines in these two builders are what stop the list items
     * and table rows running into one line there.
     */
    private static function list_html($items)
    {
        if (!count($items)) return '';

        $html = '<ul style="margin:0 0 1.25em 0; padding-left:1.25em;">';

        foreach ($items as $item) {
            $html .= "\n".'<li style="margin-bottom:0.5em;">'.self::esc($item).'</li>';
        }

        return $html."\n</ul>";
    }

    private static function details_html($label, $name, $org, $email)
    {
        $rows = [
            'What you sent' => $label,
            'Your name'     => $name,
            'Organisation'  => $org,
            'Email'         => $email,
            'Received'      => date('j F Y \a\t g:ia'),
        ];

        $html = '<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="width:100%; border-collapse:collapse;">';

        foreach ($rows as $heading => $value) {
            if (trim((string) $value) === '') continue;

            $html .= "\n".'<tr>'
                  .  '<th align="left" valign="top" style="padding:0.35em 1em 0.35em 0; font-weight:bold; width:9em;">'.self::esc($heading).': </th>'
                  .  '<td valign="top" style="padding:0.35em 0;">'.self::esc($value).'</td>'
                  .  '</tr>';
        }

        return $html."\n</table>";
    }

    /*
     * Values go into an HTML email, so they are escaped. Note the dollar
     * substitution in PerchEmail replaces anything matching $word, so a stray
     * dollar sign in submitted text would eat the following word: take it out.
     */
    private static function esc($value)
    {
        $value = str_replace('$', '', (string) $value);

        /* ENT_COMPAT, not ENT_QUOTES: these values land in text nodes and in
           one double-quoted href, so single quotes are safe to leave alone.
           PerchEmail builds the plain text alternative with strip_tags and no
           entity decoding, and an apostrophe is far more likely in a name than
           an ampersand. */
        return htmlspecialchars($value, ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8');
    }

    /*
     * Can we open a TCP connection to the mail host at all? This is not a test
     * that mail will send - it is a test that trying will not block. The TLS
     * and SMTP dialogue that follows has its own (much longer) timeouts, but
     * the failure that hangs a request is almost always the connection itself.
     */
    private static function mail_host_reachable()
    {
        if (!defined('PERCH_EMAIL_METHOD') || strtolower(PERCH_EMAIL_METHOD) !== 'smtp') return true;

        $errno  = 0;
        $errstr = '';

        $socket = @fsockopen(PERCH_EMAIL_HOST, (int) PERCH_EMAIL_PORT, $errno, $errstr, self::CONNECT_TIMEOUT);

        if ($socket === false) {
            PerchUtil::debug('cjs_form_ack: mail host unreachable: '.$errstr.' ('.$errno.')', 'error');
            return false;
        }

        fclose($socket);

        return true;
    }

    private static function log($message)
    {
        PerchUtil::debug('cjs_form_ack: '.$message, 'error');
        error_log('[cjs_form_ack] '.$message);
    }
}
