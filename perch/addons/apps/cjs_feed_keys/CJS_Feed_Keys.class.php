<?php

/*
 * Access keys for the XML job feeds.
 *
 * The feeds carry the whole product: the advert copy employers pay to place,
 * in a form built for machines to read. They were open to anyone who knew the
 * address. This gives each consumer a key of their own, so a feed can be
 * attributed, rate-looked-at and turned off one subscriber at a time instead
 * of all at once.
 *
 * A key is a bearer secret in a URL, which is how podcast and job-board
 * partner feeds have always worked: a reader or an aggregator pastes a URL in
 * once and never visits again. It is not meant to be unguessable forever. It
 * is meant to say WHO is fetching, and to be revocable when the answer stops
 * being someone CJS wants to supply.
 */
class CJS_Feed_Keys
{
    /*
     * Until this date a request with no key still gets the full feed, with a
     * notice in the XML and a Warning header telling them to get one. After
     * it, no key means the summary feed: title, employer, location, closing
     * date and a link, but not the advert body.
     *
     * Nothing ever returns an error for a missing key, so no existing
     * integration breaks on the day this lands - it quietly gets less.
     */
    const ENFORCE_FROM = '2026-12-15';

    private static $table = false;
    private static $checked = false;

    public static function table()
    {
        if (self::$table === false) self::$table = PERCH_DB_PREFIX.'feed_keys';

        return self::$table;
    }

    /*
     * Idempotent, and called from the admin page rather than activate.php,
     * because the app is switched on by hand in config/apps.php and Perch
     * never runs an installer for it.
     */
    public static function install()
    {
        $DB = PerchDB::fetch();

        $DB->execute('CREATE TABLE IF NOT EXISTS '.self::table().' (
            feedKeyID   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            feedKey     VARCHAR(64)  NOT NULL DEFAULT "",
            keyLabel    VARCHAR(255) NOT NULL DEFAULT "",
            keyEmail    VARCHAR(255) NOT NULL DEFAULT "",
            keyNotes    TEXT,
            keyActive   TINYINT(1)   NOT NULL DEFAULT 1,
            keyCreated  DATETIME     DEFAULT NULL,
            keyLastUsed DATETIME     DEFAULT NULL,
            keyLastFeed VARCHAR(64)  NOT NULL DEFAULT "",
            keyLastIP   VARCHAR(45)  NOT NULL DEFAULT "",
            keyHits     INT(10) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (feedKeyID),
            UNIQUE KEY feedKey (feedKey)
        ) DEFAULT CHARSET=utf8');

        self::$checked = true;
    }

    public static function all()
    {
        $DB = PerchDB::fetch();

        return $DB->get_rows('SELECT * FROM '.self::table().' ORDER BY keyActive DESC, keyLabel ASC');
    }

    public static function create($label, $email = '', $notes = '')
    {
        $DB  = PerchDB::fetch();
        $key = self::generate();

        $DB->insert(self::table(), [
            'feedKey'    => $key,
            'keyLabel'   => trim($label),
            'keyEmail'   => trim($email),
            'keyNotes'   => trim($notes),
            'keyActive'  => 1,
            'keyCreated' => date('Y-m-d H:i:s'),
        ]);

        return $key;
    }

    public static function set_active($id, $active)
    {
        $DB = PerchDB::fetch();
        $DB->update(self::table(), ['keyActive' => $active ? 1 : 0], 'feedKeyID', (int) $id);
    }

    public static function delete($id)
    {
        $DB = PerchDB::fetch();
        $DB->delete(self::table(), 'feedKeyID', (int) $id);
    }

    public static function generate()
    {
        if (function_exists('random_bytes')) return bin2hex(random_bytes(16));

        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /*
     * The active row for this key, or false. Returns false rather than
     * raising if the table is not there yet, so a half-finished install
     * degrades to "nobody has a key" instead of breaking every feed.
     */
    public static function find($key)
    {
        if (!is_string($key) || !preg_match('/^[a-f0-9]{32}$/i', $key)) return false;

        $DB = PerchDB::fetch();

        try {
            $row = $DB->get_row('SELECT * FROM '.self::table().' WHERE feedKey='.$DB->pdb($key).' LIMIT 1');
        } catch (Exception $e) {
            error_log('[cjs_feed_keys] lookup failed: '.$e->getMessage());
            return false;
        }

        if (!is_array($row)) return false;

        return $row;
    }

    /*
     * Who fetched what, and when. Without this the keys are only a gate; with
     * it they answer the question that actually matters later - which of these
     * is still in use, and by whom.
     */
    public static function record_use($row, $feed_id)
    {
        $DB = PerchDB::fetch();

        try {
            $DB->execute('UPDATE '.self::table().'
                             SET keyHits = keyHits + 1,
                                 keyLastUsed = '.$DB->pdb(date('Y-m-d H:i:s')).',
                                 keyLastFeed = '.$DB->pdb((string) $feed_id).',
                                 keyLastIP   = '.$DB->pdb(self::client_ip()).'
                           WHERE feedKeyID = '.(int) $row['feedKeyID']);
        } catch (Exception $e) {
            /* Never let bookkeeping stop a feed being served. */
            error_log('[cjs_feed_keys] could not record use: '.$e->getMessage());
        }
    }

    public static function client_ip()
    {
        /* Cloudflare sits in front, so REMOTE_ADDR is the edge. */
        foreach (['HTTP_CF_CONNECTING_IP', 'REMOTE_ADDR'] as $header) {
            if (!empty($_SERVER[$header])) return substr($_SERVER[$header], 0, 45);
        }

        return '';
    }

    public static function enforcing()
    {
        return date('Y-m-d') >= self::ENFORCE_FROM;
    }
}
