<?php

/*
 * cjs_feed_keys - access keys for the XML feeds.
 *
 * Call cjs_feed_guard() as the FIRST statement of a feed page template, before
 * any header() or output, because a bad key answers 401 and stops there.
 *
 *   <?php $feed = cjs_feed_guard('jobs-feed-items'); ?>
 *
 * It returns ['tier' => 'full'|'summary', 'notice' => string|false, 'key' => row|false].
 * Pass the tier down to whatever renders the items. It never returns an error
 * for a MISSING key: no key means the summary feed (and, until the enforcement
 * date, the full one with a warning), so nothing that reads these feeds today
 * breaks on the day this goes live.
 *
 * Only a key that is present and wrong is an error, because that is someone
 * whose access was withdrawn, or a typo worth telling them about rather than
 * silently downgrading.
 */

    if (!function_exists('cjs_feed_keys_load')) {

        function cjs_feed_keys_load()
        {
            if (!class_exists('CJS_Feed_Keys')) {
                include_once(__DIR__.'/CJS_Feed_Keys.class.php');
            }
        }

        /*
         * The key on this request: ?key=, Authorization: Bearer, or X-Api-Key.
         * The query string is what feed readers and aggregators can actually
         * manage; the headers are there for anything scripted.
         */
        function cjs_feed_key_from_request()
        {
            if (isset($_GET['key']) && $_GET['key'] !== '') return trim($_GET['key']);

            $headers = [];

            if (!empty($_SERVER['HTTP_AUTHORIZATION'])) $headers[] = $_SERVER['HTTP_AUTHORIZATION'];
            if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) $headers[] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];

            foreach ($headers as $header) {
                if (preg_match('/^Bearer\s+(\S+)$/i', trim($header), $m)) return $m[1];
            }

            if (!empty($_SERVER['HTTP_X_API_KEY'])) return trim($_SERVER['HTTP_X_API_KEY']);

            return '';
        }

        function cjs_feed_guard($feed_id)
        {
            cjs_feed_keys_load();

            $key = cjs_feed_key_from_request();

            if ($key !== '') {
                $row = CJS_Feed_Keys::find($key);

                if (is_array($row) && $row['keyActive']) {
                    CJS_Feed_Keys::record_use($row, $feed_id);

                    return ['tier' => 'full', 'notice' => false, 'key' => $row];
                }

                /* Present and wrong. Say so rather than quietly serving less,
                   so whoever set it up can see what happened. */
                error_log('[cjs_feed_keys] rejected key on '.$feed_id.' from '.CJS_Feed_Keys::client_ip());
                cjs_feed_refuse();
            }

            if (!CJS_Feed_Keys::enforcing()) {
                $notice = 'From '.date('j F Y', strtotime(CJS_Feed_Keys::ENFORCE_FROM))
                        . ' the full text of each advert in this feed needs an access key.'
                        . ' Keys are free: email TheTeam@countryside-jobs.com and say which feed you read.'
                        . ' Add it to the address as ?key=YOUR-KEY. Without one the feed keeps working,'
                        . ' but each item will carry the job title, employer, location and closing date only.';

                if (!headers_sent()) header('Warning: 299 - "'.$notice.'"');

                return ['tier' => 'full', 'notice' => $notice, 'key' => false];
            }

            $notice = 'This is the summary feed. For the full text of each advert, add an access key to'
                    . ' the address as ?key=YOUR-KEY. Keys are free: email TheTeam@countryside-jobs.com'
                    . ' and say which feed you read.';

            return ['tier' => 'summary', 'notice' => $notice, 'key' => false];
        }

        /*
         * A key that is present and not valid. 401 with a readable body, and
         * no redirect: feed readers handle a redirect worse than a status code.
         */
        function cjs_feed_refuse()
        {
            if (!headers_sent()) {
                http_response_code(401);
                header('Content-Type: application/xml; charset=utf-8');
                header('WWW-Authenticate: Bearer realm="CJS feeds"');
            }

            echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"
               . '<error>'."\n"
               . '  <message>That feed key is not recognised, or has been withdrawn.</message>'."\n"
               . '  <contact>TheTeam@countryside-jobs.com</contact>'."\n"
               . '</error>'."\n";

            exit;
        }

        /*
         * The notice element, for a feed template to drop into its <channel>.
         * Returns '' when there is nothing to say.
         */
        function cjs_feed_notice($feed)
        {
            if (empty($feed['notice'])) return '';

            return '  <cjsFeed:notice>'.htmlspecialchars($feed['notice'], ENT_NOQUOTES, 'UTF-8').'</cjsFeed:notice>'."\n";
        }
    }
