<?php

defined('FROM_POST_HANDLER') || die("Direct file access is not allowed");

$name = sanitizeInput($_POST['name']);
$email = sanitizeInput($_POST['email']);
$role = intval($_POST['role']);
$force_mfa = intval($_POST['force_mfa'] ?? 0);
$language = sanitizeInput($_POST['language']);

// Validate language against whitelist
if (!empty($language)) {
    $allowed_languages = array_keys(i18n_get_available_languages());
    if (!in_array($language, $allowed_languages, true)) {
        $language = ''; // Invalid language, clear it
    }
}

// If language is empty string (auto-detect), store NULL in database
if (empty($language)) {
    $language_value = 'NULL';
} else {
    $language_value = "'$language'";
}
