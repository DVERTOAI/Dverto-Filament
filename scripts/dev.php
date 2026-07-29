<?php

/**
 * Cross-platform `composer run dev` launcher.
 * Laravel Pail requires the pcntl extension (unavailable on Windows),
 * so it is omitted there; --kill-others would otherwise tear everything down.
 */

$isWindows = PHP_OS_FAMILY === 'Windows';

$command = $isWindows
    ? 'npx concurrently -c "#93c5fd,#c4b5fd,#fdba74" "php artisan serve" "php artisan queue:listen --tries=1 --timeout=0" "npm run dev" --names=server,queue,vite --kill-others'
    : 'npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#fdba74" "php artisan serve" "php artisan queue:listen --tries=1 --timeout=0" "php artisan pail --timeout=0" "npm run dev" --names=server,queue,logs,vite --kill-others';

passthru($command, $exitCode);

exit($exitCode);
