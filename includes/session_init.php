<?php

if (!isset($_SESSION)) {
    // HTTP Only cookies
    ini_set("session.cookie_httponly", true);
    
    if ($config_https_only) {
        // Tell client to only send cookie(s) over HTTPS
        ini_set("session.cookie_secure", true);
    }
    
    session_start();

}

// Load i18n functions (but don't initialize yet - wait for user session to load)
if (!function_exists('i18n_init')) {
    require_once __DIR__ . "/i18n.php";
}
