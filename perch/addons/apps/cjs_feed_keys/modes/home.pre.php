<?php

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');

    CJS_Feed_Keys::install();

    $message   = false;
    $new_key   = false;

    /* Revoke / restore / delete, by link. */
    if (isset($_GET['do']) && isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        switch ($_GET['do']) {
            case 'revoke':
                CJS_Feed_Keys::set_active($id, false);
                $message = $HTML->success_message('That key has been revoked. The next request using it gets a 401.');
                break;

            case 'restore':
                CJS_Feed_Keys::set_active($id, true);
                $message = $HTML->success_message('That key works again.');
                break;

            case 'delete':
                CJS_Feed_Keys::delete($id);
                $message = $HTML->success_message('That key has been deleted.');
                break;
        }
    }

    $Form->require_field('keyLabel', 'Required');

    if ($Form->submitted()) {
        $data = $Form->receive(['keyLabel', 'keyEmail', 'keyNotes']);

        if (trim($data['keyLabel']) !== '') {
            $new_key = CJS_Feed_Keys::create($data['keyLabel'], $data['keyEmail'], $data['keyNotes']);
            $message = $HTML->success_message('Key created for %s.', $HTML->encode($data['keyLabel']));
        }
    }

    $keys = CJS_Feed_Keys::all();

    /* The feeds a key opens. Keys are not scoped per feed: one key opens all
       of them, because every consumer so far reads one feed and scoping would
       be a setting nobody changes. The list is here so the admin page can show
       people the addresses to hand out. */
    $feeds = [
        'All jobs, full text'      => '/jobs-feed-items',
        'All jobs, for aggregators'=> '/jobs-xml-feed-aggregator',
        'Jobs by sector'           => '/feed/sector/ecology',
        'Jobs by region'           => '/feed/region/scotland-north',
    ];
