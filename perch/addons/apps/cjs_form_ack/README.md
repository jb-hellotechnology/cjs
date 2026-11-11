# cjs_form_ack

Sends the person who filled in a form an email saying it arrived.

An advertiser told CJS that after submitting a vacancy they could not tell
whether it had worked. They were right: nothing acknowledged a
submission, even though several of the forms promise in their own label text
that "we will acknowledge your submission by email". This app is that
acknowledgement, for every advertising and submission form on the site.

There is no control panel for it — it has no `admin.php`, so it does not appear
in Perch's menu. Everything is in these files.

## Files

| File | What it is |
| --- | --- |
| `forms.php` | One entry per form: the copy, the reply-to address and which fields hold the sender's email and name. This is the file you edit. |
| `CJS_Form_Ack.class.php` | Finds the sender's address, renders the email and sends it. Never raises; every failure is logged and swallowed. |
| `runtime.php` | The two entry points, `cjs_form_ack_form_handler()` and `cjs_form_ack_send()`. |
| `../../../templates/emails/cjs-form-acknowledgement.html` | The email itself. Read the comment at the top before editing it. |

## Deploying

Two steps, both easy to forget.

### 1. Register the app

Add `'cjs_form_ack'` to `$apps_list` in `perch/config/apps.php`.

That file is **not in the repository** (`perch/config` is deliberately excluded,
see `.gitignore`), so deploying the code does not add the line — it has to be
added by hand on each server, once.

Until it is added, `cjs_form_ack_form_handler` does not exist, Perch logs
`cjs_form_ack form handler not found`, and nothing else changes: forms still
submit and store, no acknowledgements go out. Nothing breaks either way.

### 2. Republish the pages

Perch keeps a rendered snapshot of every region in `regionHTML` and serves
that, so **editing a form template changes nothing on the site until the region
is republished**. The `app` attribute is part of that snapshot, which means a
form wired up through the app attribute keeps dispatching to the old app list
until you do.

In the control panel: Pages, then Republish, then "Republish all pages".

This applies to any future change to a form template, not just this one.

## Adding a form

1. Add an entry to `forms.php`, keyed on the form's id — the `id` attribute on
   its `<perch:form>` tag, not the template filename and not the title Perch's
   Forms app shows. Check `email_field` against the template: most forms use
   `email`, but the organisation profile uses `contactEmail`.

2. Then pick one of two ways to fire it:

   **Add the app to the form tag** — for a form handled by `perch_forms`, which
   stores every submission it is given and cannot silently drop one:

   ```html
   <perch:form id="monthly_book" method="post" app="perch_forms cjs_form_ack">
   ```

   Keep `cjs_form_ack` last, so the submission is stored before the email goes
   out.

   **Call it from the handling app** — for a form handled by `cjs_daily`,
   `cjs_workdays` or `cjs_organisations`. Those all verify reCAPTCHA *after*
   Perch has called them and throw the submission away when it fails, so the app
   attribute would acknowledge adverts that were never stored. Instead, call it
   beside the team's own notification email, inside the branch that means the
   submission was kept:

   ```php
   if (function_exists('cjs_form_ack_send')) cjs_form_ack_send('cjs_online', $data->data);
   ```

   The `function_exists` guard is what makes the missing `apps.php` line
   harmless.

Sending twice is already prevented: a form id is only acknowledged once per
request, so a form wired up both ways sends one email.

One thing the app attribute cannot survive: a **success URL** set on the form in
Perch's Forms app. `perch_forms` redirects as the last thing it does, so
anything after it in the app list never runs. None of the forms listed below has
one. If you add a success URL to one of them, move it to the handler route, or
the acknowledgement silently stops.

## Which forms are wired up

Fourteen, all of them advertising or submission forms:

| Form id | Page | Fired by |
| --- | --- | --- |
| `cjs_online` | /advertise | cjs_daily |
| `cjs_free` | /advertise/jobs-free-ads | cjs_daily |
| `cjs_volunteer` | /advertise/volunteers | cjs_daily |
| `cjs_volunteerorganisation` | /advertise/volunteers/citizen-science | cjs_workdays |
| `cjs_organisation` | /advertise/organisation | cjs_organisations |
| `cjs_shortcourse_event` | /training/advertise | app attribute |
| `cjs_longcourse` | /training/advertise/advertise-long-course-centre | app attribute |
| `classified_advertising` | /advertise/classified-submit | app attribute |
| `monthly_book` | /advertise/professional-booking | app attribute |
| `cjs_free_listing` | /cjs-focus/submit-your-advert | app attribute |
| `voluntary_posts` | /cjs-focus/focus-sfls | app attribute |
| `grants_funding` | /information/general-submit | app attribute |
| `grants_funding_suggestions` | (grants and funding suggestions) | app attribute |
| `offices_and_sites` | /professional/prof-reminder | app attribute |

Surveys, contact forms, subscription forms and the search forms are **not**
wired up. Adding one is an entry in `forms.php` plus one of the two steps above.

## Sender, reply-to and deliverability

The email is sent through Perch's own mailer, so it uses the SMTP account in
`perch/config` rather than `mail()`. The From address stays `PERCH_EMAIL_FROM`,
which matches that SMTP account — changing it to a `countryside-jobs.com`
address would send mail the SPF record for the sending domain does not cover.

Reply-to is set per form, to the inbox that receives the submission
notification, so a reply to the acknowledgement lands with whoever is handling
it.

## Checking it works

Nothing is stored, so the trail is the mail log and Perch's debug output. Add
`?debug=true` to the form's page with `PERCH_DEBUG` on and look for
`cjs_form_ack:` lines. Failures are also written to the PHP error log, prefixed
`[cjs_form_ack]`.
