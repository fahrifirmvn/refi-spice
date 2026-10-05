<?php

$config['compose_save_localstorage'] = true;
$config['draft_autosave'] = 60;
$config['language'] = 'id_ID';
$config['login_autocomplete'] = 2;
$config['login_rate_limit'] = 3;
$config['plugins'][] = 'refi_branding';
$config['product_name'] = 'Refi Mail';
$config['session_lifetime'] = 15;
$config['session_samesite'] = 'Lax';

// Disposable local lab only: the mail server uses a generated self-signed CA.
// Staging and production must retain normal certificate verification.
$config['imap_conn_options'] = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
];
$config['smtp_conn_options'] = $config['imap_conn_options'];
