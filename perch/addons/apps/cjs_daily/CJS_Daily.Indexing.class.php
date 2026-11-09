<?php
/**
 * Telling search engines about an advert the moment it is published.
 *
 * A scraped copy of a CJS advert competes with the original, and the copy that
 * gets indexed first tends to be treated as the source. Waiting for a crawler
 * to find the sitemap can take a day; these two APIs cut that to minutes, so
 * the advert on countryside-jobs.com is the one Google and Bing see first.
 *
 *   IndexNow            Bing, Yandex, Seznam, Naver (one call covers them all)
 *   Google Indexing API Google, and officially supported for JobPosting pages
 *
 * Both are best-effort. Nothing here is allowed to slow an advert being saved
 * or to stop it being saved, so every network call has a short timeout and
 * every failure is logged and swallowed.
 *
 * Configuration lives in perch/config/config.production.php, which is outside
 * the repository because the Google key is a credential:
 *
 *   define('CJS_INDEXNOW_KEY', '...');                  // also served at /<key>.txt
 *   define('CJS_GOOGLE_INDEXING_KEY_FILE', '/home/cjsjobs/private/cjs-indexing.json');
 *
 * With neither constant set, every method is a no-op, which is what we want on
 * a local copy of the site.
 */
class CJS_Daily_Indexing
{
    /** Where the log and the de-duplication state are kept (never web-served). */
    const STATE_DIR = __DIR__.'/indexing';

    /** Don't submit the same URL twice inside this many seconds. */
    const DEDUPE_SECONDS = 600;

    /** Google's default quota is 200 URL notifications a day. */
    const GOOGLE_DAILY_CAP = 180;

    const SITE = 'https://www.countryside-jobs.com';


    /* ------------------------------------------------------------ public API */

    /**
     * An advert has been published or amended: ask for a (re)crawl.
     *
     * @param string|array $urls One or more paths ('/job/nov26/ranger') or full URLs.
     */
    public static function updated($urls)
    {
        return self::submit($urls, 'URL_UPDATED');
    }

    /**
     * An advert has closed. The page now returns 410, so this asks for it to be
     * dropped from the index rather than left to go stale.
     */
    public static function deleted($urls)
    {
        return self::submit($urls, 'URL_DELETED');
    }

    /**
     * Whether an advert is on the site right now. Adverts are routinely entered
     * days ahead of their posted date, so "published" on its own is not enough:
     * submitting one of those would send a crawler to a page that 404s.
     */
    public static function is_live($advert)
    {
        if (!is_array($advert)) return false;

        $status = isset($advert['advertStatus']) ? $advert['advertStatus'] : '';
        if ($status !== 'pub') return false;

        $posted  = self::date($advert, 'postedDate');
        $closing = self::date($advert, 'closingDate');

        if ($posted === null || $closing === null) return false;

        $today = date('Y-m-d');

        return ($posted <= $today && $closing >= $today);
    }

    /**
     * Work out what one saved advert needs, and do it.
     *
     * Called from the admin after a save, with the advert as it now stands and,
     * for an edit, as it stood beforehand. Comparing the two is what lets a
     * suspended or withdrawn advert be de-indexed straight away instead of
     * waiting for the nightly sweep to notice it has gone.
     *
     * @param array      $advert The advert after this save.
     * @param array|null $was    The advert before it, for an edit.
     */
    public static function advert($advert, $was = null)
    {
        $url = isset($advert['advertURL']) ? trim((string)$advert['advertURL']) : '';
        if ($url === '') return false;

        if (self::is_live($advert)) return self::updated($url);

        /* Not live now. Only worth a de-index notification if it used to be:
           an advert that has never been published was never in the index. */
        if (is_array($was) && self::is_live($was)) return self::deleted($url);

        return false;
    }

    /** A Y-m-d date from an advert field, or null if it is not a usable date. */
    private static function date($advert, $field)
    {
        $value = isset($advert[$field]) ? substr(trim((string)$advert[$field]), 0, 10) : '';

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) return null;
        if ($value === '0000-00-00') return null;

        return $value;
    }


    /* ------------------------------------------------------------- internals */

    private static function submit($urls, $type)
    {
        $urls = self::normalise($urls);
        if (!count($urls)) return false;

        /* An advert saved several times in a row should not spend the Google
           quota on every save. Closures bypass the window: a 410 that misses
           its notification would sit in the index until the next crawl. */
        if ($type === 'URL_UPDATED') {
            $urls = self::drop_recent($urls);
            if (!count($urls)) return false;
        }

        $indexnow = self::indexnow($urls);
        $google   = self::google($urls, $type);

        self::remember($urls);

        return ($indexnow || $google);
    }

    /**
     * Turn whatever we were handed into a list of absolute, de-duplicated URLs
     * on our own host. Anything else is dropped rather than submitted, because
     * both APIs reject a whole batch over one bad entry.
     */
    private static function normalise($urls)
    {
        if (!is_array($urls)) $urls = array($urls);

        $out = array();

        foreach ($urls as $url) {
            $url = trim((string)$url);
            if ($url === '') continue;

            if (strpos($url, '/') === 0) $url = self::SITE.$url;

            /* Only our own canonical host, and no query strings or fragments:
               those are not the address the advert is indexed under. */
            if (strpos($url, self::SITE.'/') !== 0) continue;
            $url = strtok($url, '?#');

            $out[$url] = true;
        }

        return array_keys($out);
    }


    /* -------------------------------------------------------------- IndexNow */

    /**
     * One POST covers every IndexNow participant. The key file must be readable
     * at https://www.countryside-jobs.com/<key>.txt or the batch is rejected:
     * that file is how the API knows the submission came from someone who
     * controls the site.
     */
    private static function indexnow($urls)
    {
        if (!defined('CJS_INDEXNOW_KEY') || CJS_INDEXNOW_KEY === '') {
            self::log('skip', 'indexnow', 'CJS_INDEXNOW_KEY is not set');
            return false;
        }

        $body = json_encode(array(
            'host'        => 'www.countryside-jobs.com',
            'key'         => CJS_INDEXNOW_KEY,
            'keyLocation' => self::SITE.'/'.CJS_INDEXNOW_KEY.'.txt',
            'urlList'     => array_values($urls),
        ), JSON_UNESCAPED_SLASHES);

        $res = self::post('https://api.indexnow.org/indexnow', $body, array(
            'Content-Type: application/json; charset=utf-8',
        ));

        /* 200 accepted, 202 accepted but the key is still being validated. */
        $ok = ($res['status'] == 200 || $res['status'] == 202);

        self::log($ok ? 'ok' : 'fail', 'indexnow',
                  'HTTP '.$res['status'].' for '.count($urls).' url(s)'.
                  ($ok ? '' : ' :: '.$res['error'].' '.substr($res['body'], 0, 300)));

        return $ok;
    }


    /* ------------------------------------------------- Google Indexing API */

    /**
     * The Indexing API takes one URL per call, so this loops. It is only
     * supported for JobPosting and BroadcastEvent pages, which is exactly what
     * an advert is.
     */
    private static function google($urls, $type)
    {
        $token = self::google_token();
        if ($token === null) return false;

        $sent = self::google_sent_today();
        $ok   = 0;

        foreach ($urls as $url) {

            if ($sent >= self::GOOGLE_DAILY_CAP) {
                self::log('skip', 'google', 'daily cap of '.self::GOOGLE_DAILY_CAP.' reached, '.$url.' left to the sitemap');
                continue;
            }

            $res = self::post('https://indexing.googleapis.com/v3/urlNotifications:publish',
                json_encode(array('url' => $url, 'type' => $type), JSON_UNESCAPED_SLASHES),
                array('Content-Type: application/json', 'Authorization: Bearer '.$token)
            );

            $sent++;

            if ($res['status'] == 200) {
                $ok++;
                self::log('ok', 'google', $type.' '.$url);
            }else{
                self::log('fail', 'google',
                          'HTTP '.$res['status'].' '.$type.' '.$url.
                          ' :: '.$res['error'].' '.substr($res['body'], 0, 300));
            }
        }

        self::google_count_today($sent);

        return ($ok > 0);
    }

    /**
     * Exchange the service account key for a one-hour access token.
     *
     * The service account also has to be added as an owner of the property in
     * Search Console; without that the API answers 403 on every call even
     * though the token itself is fine.
     */
    private static function google_token()
    {
        if (!defined('CJS_GOOGLE_INDEXING_KEY_FILE') || CJS_GOOGLE_INDEXING_KEY_FILE === '') {
            self::log('skip', 'google', 'CJS_GOOGLE_INDEXING_KEY_FILE is not set');
            return null;
        }

        if (!is_readable(CJS_GOOGLE_INDEXING_KEY_FILE)) {
            self::log('fail', 'google', 'key file not readable: '.CJS_GOOGLE_INDEXING_KEY_FILE);
            return null;
        }

        $key = json_decode(file_get_contents(CJS_GOOGLE_INDEXING_KEY_FILE), true);

        if (!is_array($key) || !isset($key['client_email']) || !isset($key['private_key'])) {
            self::log('fail', 'google', 'key file is not a service account JSON');
            return null;
        }

        /* A cached token saves a round trip on every advert in a batch. */
        $cached = self::state('google_token');
        if (is_array($cached) && isset($cached['expires']) && $cached['expires'] > time() + 60) {
            return $cached['token'];
        }

        $now = time();

        $jwt = self::jwt(array(
            'iss'   => $key['client_email'],
            'scope' => 'https://www.googleapis.com/auth/indexing',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ), $key['private_key']);

        if ($jwt === null) return null;

        $res = self::post('https://oauth2.googleapis.com/token',
            http_build_query(array(
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            )),
            array('Content-Type: application/x-www-form-urlencoded')
        );

        $data = json_decode($res['body'], true);

        if ($res['status'] != 200 || !is_array($data) || !isset($data['access_token'])) {
            self::log('fail', 'google',
                      'token request returned HTTP '.$res['status'].
                      ' :: '.$res['error'].' '.substr($res['body'], 0, 300));
            return null;
        }

        self::state('google_token', array(
            'token'   => $data['access_token'],
            'expires' => $now + (isset($data['expires_in']) ? (int)$data['expires_in'] : 3600),
        ));

        return $data['access_token'];
    }

    /** A signed RS256 JWT, which is all Google's JWT-bearer flow needs. */
    private static function jwt($claims, $private_key)
    {
        if (!function_exists('openssl_sign')) {
            self::log('fail', 'google', 'openssl extension is not available');
            return null;
        }

        $input = self::b64(json_encode(array('alg' => 'RS256', 'typ' => 'JWT'))).'.'.
                 self::b64(json_encode($claims));

        $signature = '';

        if (!openssl_sign($input, $signature, $private_key, 'sha256WithRSAEncryption')) {
            self::log('fail', 'google', 'could not sign the token: '.openssl_error_string());
            return null;
        }

        return $input.'.'.self::b64($signature);
    }

    private static function b64($raw)
    {
        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }

    /** Google's quota is per calendar day, so the counter resets with the date. */
    private static function google_sent_today()
    {
        $count = self::state('google_count');
        if (is_array($count) && isset($count['date']) && $count['date'] === date('Y-m-d')) {
            return (int)$count['sent'];
        }
        return 0;
    }

    private static function google_count_today($sent)
    {
        self::state('google_count', array('date' => date('Y-m-d'), 'sent' => (int)$sent));
    }


    /* ----------------------------------------------------------------- HTTP */

    private static function post($url, $body, $headers)
    {
        if (!function_exists('curl_init')) {
            return array('status' => 0, 'body' => '', 'error' => 'curl is not available');
        }

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            /* Short, because an advert is being saved while this runs. */
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_USERAGENT      => 'CountrysideJobsService/1.0 (+https://www.countryside-jobs.com)',
        ));

        $response = curl_exec($ch);
        $status   = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);

        curl_close($ch);

        return array(
            'status' => $status,
            'body'   => ($response === false) ? '' : $response,
            'error'  => $error,
        );
    }


    /* ------------------------------------------------- state, de-dupe, log */

    /**
     * Read or write one key in the small JSON state file. Used for the cached
     * Google token, the daily counter and the recently-submitted list, none of
     * which is worth a database table.
     */
    private static function state($key, $value = null)
    {
        $path  = self::STATE_DIR.'/state.json';
        $state = array();

        if (is_readable($path)) {
            $decoded = json_decode(file_get_contents($path), true);
            if (is_array($decoded)) $state = $decoded;
        }

        if ($value === null) return isset($state[$key]) ? $state[$key] : null;

        $state[$key] = $value;

        if (self::writable()) {
            file_put_contents($path, json_encode($state), LOCK_EX);
        }

        return $value;
    }

    private static function drop_recent($urls)
    {
        $recent = self::state('recent');
        if (!is_array($recent)) return $urls;

        $cutoff = time() - self::DEDUPE_SECONDS;
        $out    = array();

        foreach ($urls as $url) {
            if (isset($recent[$url]) && $recent[$url] > $cutoff) {
                self::log('skip', 'dedupe', $url.' was submitted in the last '.self::DEDUPE_SECONDS.'s');
                continue;
            }
            $out[] = $url;
        }

        return $out;
    }

    private static function remember($urls)
    {
        $recent = self::state('recent');
        if (!is_array($recent)) $recent = array();

        $now    = time();
        $cutoff = $now - self::DEDUPE_SECONDS;

        foreach ($urls as $url) $recent[$url] = $now;

        /* Forget anything past the window so the file cannot grow forever. */
        foreach ($recent as $url => $when) {
            if ($when <= $cutoff) unset($recent[$url]);
        }

        self::state('recent', $recent);
    }

    private static function writable()
    {
        if (is_dir(self::STATE_DIR)) return is_writable(self::STATE_DIR);
        return @mkdir(self::STATE_DIR, 0755, true);
    }

    /**
     * One line per event. This is the only record of whether a submission
     * worked, so it stays readable: tail it when an advert is slow to appear.
     */
    private static function log($result, $service, $message)
    {
        if (!self::writable()) return;

        $line = sprintf("%s  %-4s  %-8s  %s\n",
                        date('Y-m-d H:i:s'), $result, $service, $message);

        file_put_contents(self::STATE_DIR.'/indexing.log', $line, FILE_APPEND | LOCK_EX);
    }
}
