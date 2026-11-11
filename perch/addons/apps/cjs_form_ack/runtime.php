<?php

/*
 * cjs_form_ack — acknowledgement emails for website forms.
 *
 * Two ways in, because the advertising forms are not all handled the same way:
 *
 *  1. Add cjs_form_ack to the app attribute of the <perch:form> tag, last in
 *     the list, e.g. app="perch_forms cjs_form_ack". Perch then calls
 *     cjs_form_ack_form_handler() after the app that stores the submission.
 *     Use this for the perch_forms-handled forms, which cannot silently reject
 *     a submission.
 *
 *  2. Call cjs_form_ack_send($form->id, $form->data) from the handling app
 *     itself, at the point it has decided the submission is good. Use this
 *     wherever the handler can throw a submission away after Perch has already
 *     called it — cjs_daily, cjs_workdays and cjs_organisations all drop a
 *     submission when reCAPTCHA fails, and an acknowledgement for an advert
 *     that was never stored is worse than no acknowledgement at all.
 *
 * The app must be listed in perch/config/apps.php. It is not: config/ is
 * deliberately outside the repository, so the line has to be added on each
 * server by hand. Until it is, the handler simply does not exist and Perch
 * logs "form handler not found" — forms keep working, no acknowledgements go
 * out. See README.md.
 */

    if (!function_exists('cjs_form_ack_load')) {

        function cjs_form_ack_load()
        {
            if (!class_exists('CJS_Form_Ack')) {
                include_once(__DIR__.'/CJS_Form_Ack.class.php');
            }
        }

        /*
         * Called by Perch for any form with cjs_form_ack in its app attribute.
         */
        function cjs_form_ack_form_handler($SubmittedForm)
        {
            cjs_form_ack_load();

            $config = CJS_Form_Ack::config();

            if (!isset($config[$SubmittedForm->id])) return;

            if (!$SubmittedForm->validate()) return;

            if (CJS_Form_Ack::is_spam($SubmittedForm)) return;

            CJS_Form_Ack::send($SubmittedForm->id, $SubmittedForm->data);
        }

        /*
         * Called directly by an app that has stored the submission itself.
         * $values is the submitted data keyed by field id, i.e. $form->data.
         */
        function cjs_form_ack_send($formID, $values)
        {
            cjs_form_ack_load();

            return CJS_Form_Ack::send($formID, $values);
        }
    }
