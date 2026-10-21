<?php
/**
 * A class representing a single Email
 */
 
 /* Brevo API key: CJS_BREVO_API_KEY, defined in perch/config */
class CJS_Daily_Email extends PerchAPI_Base
{
  protected $table     = 'emails';
  protected $pk        = 'emailID';
}
