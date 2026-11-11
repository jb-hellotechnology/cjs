<?php

    echo $HTML->title_panel(['heading' => $Lang->get('Feed Keys')], $CurrentUser);

    $HTML->main_panel_start();

    if ($message) echo $message;

    if ($new_key) {
        echo $HTML->success_block('The new key',
            '<code>'.$HTML->encode($new_key).'</code><br />Add it to a feed address as '
            .'<code>?key='.$HTML->encode($new_key).'</code>. This is the only place it is shown in full; '
            .'it is in the table below too, so you can always come back for it.');
    }

    if (!CJS_Feed_Keys::enforcing()) {
        echo $HTML->info_block('Not enforced yet',
            'Until '.date('j F Y', strtotime(CJS_Feed_Keys::ENFORCE_FROM)).' a request with no key still gets '
            .'the full feed, with a notice in the XML and a Warning header. After that date a request with no '
            .'key gets the summary feed instead: job title, employer, location, closing date and link, but not '
            .'the advert text. A request with a wrong or revoked key is refused today, either way. '
            .'The date is CJS_Feed_Keys::ENFORCE_FROM in the app.');
    }

    echo $HTML->heading2('Keys');

    if (PerchUtil::count($keys)) {

        echo '<table class="d">
                <thead>
                  <tr>
                    <th>Who</th><th>Key</th><th>Created</th><th>Last used</th><th>Fetches</th><th></th>
                  </tr>
                </thead>
                <tbody>';

        foreach ($keys as $key) {

            $who = $HTML->encode($key['keyLabel']);
            if ($key['keyEmail'] !== '') $who .= '<br /><span class="quiet">'.$HTML->encode($key['keyEmail']).'</span>';
            if (!$key['keyActive'])      $who .= '<br /><strong>revoked</strong>';

            $used = $key['keyLastUsed']
                        ? $HTML->encode(date('j M Y H:i', strtotime($key['keyLastUsed'])))
                          .'<br /><span class="quiet">'.$HTML->encode($key['keyLastFeed'].' '.$key['keyLastIP']).'</span>'
                        : '<span class="quiet">never</span>';

            $actions = $key['keyActive']
                        ? '<a href="?do=revoke&amp;id='.(int) $key['feedKeyID'].'">Revoke</a>'
                        : '<a href="?do=restore&amp;id='.(int) $key['feedKeyID'].'">Restore</a>'
                          .' &middot; <a href="?do=delete&amp;id='.(int) $key['feedKeyID'].'">Delete</a>';

            echo '<tr>'
               . '<td>'.$who.'</td>'
               . '<td><code>'.$HTML->encode($key['feedKey']).'</code></td>'
               . '<td>'.($key['keyCreated'] ? $HTML->encode(date('j M Y', strtotime($key['keyCreated']))) : '').'</td>'
               . '<td>'.$used.'</td>'
               . '<td>'.(int) $key['keyHits'].'</td>'
               . '<td class="action last">'.$actions.'</td>'
               . '</tr>';
        }

        echo '</tbody></table>';

    } else {
        echo $HTML->para('No keys yet. Everyone reading a feed is doing it anonymously.');
    }

    echo $HTML->heading2('Issue a key');

    echo $Form->form_start();
    echo $Form->text_field('keyLabel', 'Who it is for', '', '', false);
    echo $Form->hint('The organisation or person, as you would describe them to a colleague.');
    echo $Form->text_field('keyEmail', 'Email', '', '', false);
    echo $Form->hint('Optional, but it is the only way to warn them before revoking.');
    echo $Form->textarea_field('keyNotes', 'Notes', '', 'form-control', false);
    echo $Form->submit_field('btnSubmit', 'Create key');
    echo $Form->form_end();

    echo $HTML->heading2('The feed addresses');

    echo '<table class="d"><thead><tr><th>Feed</th><th>Address</th></tr></thead><tbody>';
    foreach ($feeds as $name => $path) {
        echo '<tr><td>'.$HTML->encode($name).'</td><td><code>https://www.countryside-jobs.com'
           . $HTML->encode($path).'?key=…</code></td></tr>';
    }
    echo '</tbody></table>';

    echo $HTML->para('A key can also be sent as <code>Authorization: Bearer …</code> or <code>X-Api-Key</code>, '
                    .'for anything scripted. The volunteers feed is not keyed: it carries no advert text.');

    $HTML->main_panel_end();
