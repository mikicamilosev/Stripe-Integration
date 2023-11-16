<?php

//Timezone
date_default_timezone_set('America/New_York');

//WEBSITE

define('WEBSITE_NAME', 'Rid My Inventory');
// define('WEBSITE_DOMAIN', 'http://nickc36.sg-host.com');
define('WEBSITE_DOMAIN', 'https://localhost/webshop/nickc36.sg-host.com/public_html/');

// It can be the same as domain (if script is placed on website's root folder)
// or it can contain path that include subfolders, if script is located in
//some subfolder and not in root folder.
// define('SCRIPT_URL', 'http://nickc36.sg-host.com/auth/');
define('SCRIPT_URL', 'https://localhost/webshop/nickc36.sg-host.com/public_html/auth/');

//DATABASE CONFIGURATION
// define('DB_HOST', 'localhost');
// define('DB_TYPE', 'mysql');
// define('DB_USER', 'uge2tu5r4idiz');
// define('DB_PASS', '&)#1A215*2*e');
// define('DB_NAME', 'dbvfylgjseyivt');
define('DB_HOST', 'localhost');
define('DB_TYPE', 'mysql');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'stripe');

//SESSION CONFIGURATION
define('SESSION_SECURE', false);
define('SESSION_HTTP_ONLY', true);
define('SESSION_USE_ONLY_COOKIES', true);

//LOGIN CONFIGURATION
define('LOGIN_MAX_LOGIN_ATTEMPTS', 6);
define('LOGIN_FINGERPRINT', false);
define('SUCCESS_LOGIN_REDIRECT', serialize(['default' => "index.php"]));

//PASSWORD CONFIGURATION
define('PASSWORD_RESET_KEY_LIFE', 30);

// REGISTRATION CONFIGURATION
define('MAIL_CONFIRMATION_REQUIRED', true);
// define('REGISTER_CONFIRM', "http://nickc36.sg-host.com/auth/confirm.php");
// define('REGISTER_PASSWORD_RESET', "http://nickc36.sg-host.com/auth/passwordreset.php");
define('REGISTER_CONFIRM', "https://localhost/webshop/nickc36.sg-host.com/public_html/auth/confirm.php");
define('REGISTER_PASSWORD_RESET', "https://localhost/webshop/nickc36.sg-host.com/public_html/auth/passwordreset.php");

// EMAIL SENDING CONFIGURATION
// Available MAILER options are 'mail' for php mail() and 'smtp' for using SMTP server for sending emails
define('MAILER', "mail");
define('SMTP_HOST', "");
define('SMTP_PORT', 25);
define('SMTP_USERNAME', "");
define('SMTP_PASSWORD', "");
define('SMTP_ENCRYPTION', "");

define('MAIL_FROM_NAME', "Advanced Security");
define('MAIL_FROM_EMAIL', "noreply@nickc36.sg-host.com");

// SOCIAL LOGIN CONFIGURATION

define('SOCIAL_CALLBACK_URI', "https://localhost/webshop/nickc36.sg-host.com/public_html/auth/socialauth_callback.php");

// GOOGLE
define('GOOGLE_ENABLED', false);
define('GOOGLE_ID', "");
define('GOOGLE_SECRET', "");

// FACEBOOK
define('FACEBOOK_ENABLED', false);
define('FACEBOOK_ID', "");
define('FACEBOOK_SECRET', "");

// TWITTER
define('TWITTER_ENABLED', false);
define('TWITTER_KEY', "");
define('TWITTER_SECRET', "");

// TRANSLATION
define('DEFAULT_LANGUAGE', 'en');
