<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/


defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('EMAIL_CONFIG_EMAIL')  OR define('EMAIL_CONFIG_EMAIL', 'nlp@empoweryourdestiny.com.au');

defined('UNSUBSCRIBE_LINK')  OR define('UNSUBSCRIBE_LINK', 'https://www.franhive.com/eyd/unsubscribe');

defined('EMAIL_FROM')  OR define('EMAIL_FROM', 'EYD TEAM');


// Stripe configuration: read from environment (no hard-coded secrets)
// Expected env vars:
// STRIPE_MODE (live|test), STRIPE_LIVE_PUBLISHABLE_KEY, STRIPE_LIVE_SECRET_KEY, STRIPE_LIVE_WEBHOOK_SECRET
// STRIPE_TEST_PUBLISHABLE_KEY, STRIPE_TEST_SECRET_KEY, STRIPE_TEST_WEBHOOK_SECRET
$stripeMode = getenv('STRIPE_MODE') ?: 'test'; // default to test for safety
defined('STRIPE_MODE') || define('STRIPE_MODE', $stripeMode);

$livePublishable = getenv('STRIPE_LIVE_PUBLISHABLE_KEY') ?: '';
$liveSecret      = getenv('STRIPE_LIVE_SECRET_KEY') ?: '';
$liveWebhook     = getenv('STRIPE_LIVE_WEBHOOK_SECRET') ?: '';

$testPublishable = getenv('STRIPE_TEST_PUBLISHABLE_KEY') ?: '';
$testSecret      = getenv('STRIPE_TEST_SECRET_KEY') ?: '';
$testWebhook     = getenv('STRIPE_TEST_WEBHOOK_SECRET') ?: '';

if (STRIPE_MODE === 'live') {
    defined('STRIPE_PUBLISHABLE_KEY') || define('STRIPE_PUBLISHABLE_KEY', $livePublishable);
    defined('STRIPE_SECRET_KEY')      || define('STRIPE_SECRET_KEY',      $liveSecret);
    defined('STRIPE_WEBHOOK_SECRET')  || define('STRIPE_WEBHOOK_SECRET',  $liveWebhook);
} else {
    defined('STRIPE_PUBLISHABLE_KEY') || define('STRIPE_PUBLISHABLE_KEY', $testPublishable);
    defined('STRIPE_SECRET_KEY')      || define('STRIPE_SECRET_KEY',      $testSecret);
    defined('STRIPE_WEBHOOK_SECRET')  || define('STRIPE_WEBHOOK_SECRET',  $testWebhook);
}



# CloudTalk shared secret (must match your HTTP Request header)
defined('CT_SECRET')  || define('CT_SECRET',  '8e8493de39141aaaa04bd800fce5c915456a804b4c87c0b6a3f7a927a44477e7');

defined('CLOUDTALK_PARTNER') OR define('CLOUDTALK_PARTNER', 'demo.franhive.com'); 

