<?php

/*
 * Every form that sends the person who filled it in an acknowledgement email.
 *
 * An advertiser told us they could not tell whether a submission had worked: the
 * <perch:success> panel sits wherever the form region sits, which on most of
 * these pages is well below the fold. Several of the forms also promise, in
 * their own label text, that "we will acknowledge your submission by email" —
 * which nothing was actually doing. This is that acknowledgement.
 *
 * The key is the form's id, i.e. the id attribute on <perch:form>, NOT the
 * template filename and not the title shown in Perch's Forms app. Two pages can
 * share one form id (the short course form is on /training/advertise and on
 * /training/advertise/advertise-short-course-event); they share one entry here.
 *
 * Fields per entry:
 *
 *   label        Short name for the thing they sent, used in the email's own
 *                "what you sent us" line. Lower case, no trailing full stop.
 *   subject      Subject line of the acknowledgement.
 *   heading      Big line at the top of the email.
 *   intro        First paragraph. Say plainly that it arrived.
 *   next         Bullet list of what happens now. Keep to three at most, and
 *                only promise things CJS actually does.
 *   reply_to     Team address replies should go to. This is the inbox that
 *                receives the submission notification, so that a reply to the
 *                acknowledgement lands with whoever is handling it.
 *   email_field  Field ids to look in for the sender's own address, in order of
 *                preference. The first one with a valid address in it wins.
 *   name_field   Field ids to look in for their name, in order of preference.
 *                Optional — the email just drops the greeting's name if absent.
 *   org_field    Field ids to look in for their organisation. Optional.
 *
 * Adding a form: add an entry here, then either add cjs_form_ack to the app
 * attribute of its <perch:form> tag, or call cjs_form_ack_send() from the app
 * that handles it. README.md explains which of the two to pick.
 */

return [

    /* ------------------------------------------------------------------ *
     * Jobs and volunteer adverts (/advertise) — handled by cjs_daily      *
     * ------------------------------------------------------------------ */

    'cjs_online' => [
        'label'       => 'your job advert for CJS Weekly and the CJS website',
        'subject'     => 'We have your job advert - Countryside Jobs Service',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your job advert has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and prepare it for publication.',
            'If anything is missing, or there is a payment for us to take, we will be in touch.',
            'If you have an advertiser profile, you can check and update your adverts there at any time.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation', 'employerDisplay'],
    ],

    'cjs_free' => [
        'label'       => 'your free job advert',
        'subject'     => 'We have your free job advert - Countryside Jobs Service',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your free job advert has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and prepare it for publication.',
            'If anything is missing we will be in touch.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation', 'employerDisplay'],
    ],

    'cjs_volunteer' => [
        'label'       => 'your volunteer advert',
        'subject'     => 'We have your volunteer advert - Countryside Jobs Service',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your volunteer advert has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and prepare it for publication.',
            'If anything is missing, or there is a payment for us to take, we will be in touch.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation', 'employerDisplay'],
    ],

    /* ------------------------------------------------------------------ *
     * Volunteering organisations and organisation profiles                *
     * ------------------------------------------------------------------ */

    'cjs_volunteerorganisation' => [
        'label'       => 'your organisation listing for CJS Volunteers',
        'subject'     => 'We have your organisation listing - Countryside Jobs Service',
        'heading'     => 'Thank you, we have your listing',
        'intro'       => 'Your organisation listing has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the listing over before it goes live.',
            'If we have any queries we will be in touch.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email', 'contactemail'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation', 'orgName'],
    ],

    'cjs_organisation' => [
        'label'       => 'your organisation profile',
        'subject'     => 'We have your organisation profile - Countryside Jobs Service',
        'heading'     => 'Thank you, we have your profile',
        'intro'       => 'Your organisation profile has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the profile over before it goes live.',
            'If we have any queries we will be in touch.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['contactEmail', 'directEmail'],
        'name_field'  => ['contactName'],
        'org_field'   => ['contactOrganisation', 'name'],
    ],

    /* ------------------------------------------------------------------ *
     * Training (/training/advertise)                                      *
     * ------------------------------------------------------------------ */

    'cjs_shortcourse_event' => [
        'label'       => 'your short course or event advert',
        'subject'     => 'We have your course advert - CJS Training Directory',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your short course or event advert has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and add it to the Training Directory.',
            'If anything is missing, or there is a payment for us to take, we will be in touch.',
        ],
        'reply_to'    => 'training@countryside-jobs.com',
        'email_field' => ['email', 'contactemail'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation'],
    ],

    'cjs_longcourse' => [
        'label'       => 'your long course or training centre advert',
        'subject'     => 'We have your course advert - CJS Training Directory',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your long course or training centre advert has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and add it to the Training Directory.',
            'If anything is missing, or there is a payment for us to take, we will be in touch.',
        ],
        'reply_to'    => 'training@countryside-jobs.com',
        'email_field' => ['email', 'contactemail'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation'],
    ],

    /* ------------------------------------------------------------------ *
     * CJS Professional                                                    *
     * ------------------------------------------------------------------ */

    'classified_advertising' => [
        'label'       => 'your classified advert for CJS Professional',
        'subject'     => 'We have your classified advert - CJS Professional',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your classified advert has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and prepare it for the next edition.',
            'If you asked for any paid options we will contact you to arrange payment.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        /* This form has no organisation field: it has two inputs both with
           id="name" ("Company Name" and the contact's "Name"), so they post
           under one key and the company name is lost. Worth fixing in
           classified_advertising.html; until then there is nothing to read. */
        'org_field'   => ['organisation'],
    ],

    'monthly_book' => [
        'label'       => 'your CJS Professional booking request',
        'subject'     => 'We have your booking request - CJS Professional',
        'heading'     => 'Thank you, we have your booking request',
        'intro'       => 'Your booking request has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will confirm the booking and the months you asked for.',
            'We will contact you to arrange payment and to collect your advert copy.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation'],
    ],

    'grants_funding' => [
        'label'       => 'your information listing',
        'subject'     => 'We have your listing - Countryside Jobs Service',
        'heading'     => 'Thank you, we have your listing',
        'intro'       => 'Your listing has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the listing over before it is published.',
            'If you asked about a logo or extra wording we will come back to you with a quote.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation'],
    ],

    'grants_funding_suggestions' => [
        'label'       => 'your grants and funding suggestion',
        'subject'     => 'Thank you for your suggestion - Countryside Jobs Service',
        'heading'     => 'Thank you, we have your suggestion',
        'intro'       => 'Your grants and funding suggestion has reached us safely.',
        'next'        => [
            'A member of the CJS team will look into the scheme you have told us about.',
            'If we need any more detail we will be in touch.',
        ],
        'reply_to'    => 'ranger@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation'],
    ],

    'offices_and_sites' => [
        'label'       => 'your CJS Professional mailing list request',
        'subject'     => 'We have your mailing list request - CJS Professional',
        'heading'     => 'Thank you, we have your request',
        'intro'       => 'Your request about the CJS Professional mailing list has reached us safely.',
        'next'        => [
            'A member of the CJS team will update the mailing list for you.',
            'If we have any queries about the address we will be in touch.',
        ],
        'reply_to'    => 'professional@countrysidejobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation'],
    ],

    /* ------------------------------------------------------------------ *
     * CJS Focus                                                           *
     * ------------------------------------------------------------------ */

    'cjs_free_listing' => [
        'label'       => 'your advert for CJS Focus',
        'subject'     => 'We have your advert - CJS Focus',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your CJS Focus advert has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and prepare it for the next edition.',
            'If you chose a logo, a longer listing or a display advert we will contact you about artwork and payment.',
        ],
        'reply_to'    => 'focus@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation', 'company'],
    ],

    'voluntary_posts' => [
        'label'       => 'your vacancy for CJS Focus on Volunteering',
        'subject'     => 'We have your vacancy - CJS Focus on Volunteering',
        'heading'     => 'Thank you, we have your advert',
        'intro'       => 'Your vacancy for CJS Focus on Volunteering has reached us safely. There is no need to send it again.',
        'next'        => [
            'A member of the CJS team will check the advert over and prepare it for the next edition.',
            'If you chose your own wording or any paid options we will contact you about costs.',
        ],
        'reply_to'    => 'focus@countryside-jobs.com',
        'email_field' => ['email'],
        'name_field'  => ['name'],
        'org_field'   => ['organisation', 'employer'],
    ],

];
