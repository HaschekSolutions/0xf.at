<?php 

// Error settings. For production set display_errors to "off"
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors','On');


// encryption SALT. This should be a long, unique string
// that the users passwords are being salted with before
// encrypting the data. If you change it after users have
// registered, they won't be able to sign in again
define('SALT','FILLINSOMETHINGHERE');

// If you are hosting for a lager audience you might want to
// get a key from https://www.google.com/recaptcha and fill
// it in here
define('RECAPTCHA_KEY','');
define('RECAPTCHA_SECRET','');

// The IP of the server. Needed for the TCP levels
define('IP','127.0.0.1');