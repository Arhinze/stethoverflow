<?php

$site_name = "Steth Overflow";
$site_url="https://stethoverflow.com";
$site_url_short="stethoverflow.com";

$site_color_dark = "#042c06";
$site_color_light = "#ff9100";

define("SITE_NAME", "Steth Overflow");
define("SITE_NAME_SHORT", "Steth Overflow");
define("SITE_URL", "https://stethoverflow.com");
define("SITE_URL_SHORT", "stethoverflow.com");

ini_set("display_errors", '1');
date_default_timezone_set('Africa/Lagos');

//to know if to tell user to sign in or view profile:
$profile_or_sign_in = "";
if ($data) {
    $profile_or_sign_in = "Yes, Logged in";
} else {
    $profile_or_sign_in = "No, Logged out";
}  
    
define("PROFILE_OR_SIGN_IN", $profile_or_sign_in);
?>