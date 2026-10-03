#!/usr/bin/env php
<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit('Not found');
}

if (empty($argv[1])) {
    fwrite(STDERR, "A migration route is required.\n");
    exit(1);
}

// LavaLust still initializes its request and CSRF objects in CLI mode.
// Supply the request method before booting the application.
$_SERVER['REQUEST_METHOD'] = 'GET';
require dirname(__DIR__) . '/public/index.php';
