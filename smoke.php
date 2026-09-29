<?php
/**
 * Lightweight smoke test: boots the kernel, dispatches each route and prints
 * the status code (or the first lines of the exception). Run from CLI:
 *   php smoke.php              # public routes
 *   php smoke.php --admin      # public + authenticated admin routes
 *   php smoke.php /contact     # specific routes
 */
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$args = array_slice($argv, 1);
$asAdmin = in_array('--admin', $args, true);
$args = array_values(array_filter($args, fn ($arg) => $arg !== '--admin'));

$publicUris = [
    '/', '/services', '/services/recursive-3', '/services/recursive-5', '/services/recursive-7',
    '/how-it-works', '/work', '/work/cover-and-keys', '/work/deskpulse', '/about', '/faq',
    '/contact', '/contact/thank-you', '/sitemap.xml', '/robots.txt', '/admin/login',
];

$adminUris = [
    '/admin', '/admin/leads', '/admin/leads/export', '/admin/services', '/admin/services/create',
    '/admin/services/recursive-3', '/admin/projects', '/admin/projects/create',
    '/admin/projects/cover-and-keys', '/admin/faqs', '/admin/faqs/create', '/admin/faqs/1',
    '/admin/team', '/admin/team/create', '/admin/team/1', '/admin/settings', '/admin/audit-log',
];

$uris = $args ?: ($asAdmin ? array_merge($publicUris, $adminUris) : $publicUris);

$failed = 0;

if ($asAdmin) {
    $kernel->bootstrap();

    // One real session shared across requests, so auth actually round-trips.
    $session = $app['session']->driver();
    $session->setId('rf-smoke');
    $session->start();

    $token = 'rf-smoke-'.bin2hex(random_bytes(8));
    $session->put('_token', $token);

    $login = Illuminate\Http\Request::create('/admin/login', 'POST', [
        '_token' => $token,
        'email' => 'nick@recursivefrog.ph',
        'password' => 'password',
    ]);
    $login->setLaravelSession($session);
    $app->instance('request', $login);
    $loginResponse = $kernel->handle($login);

    echo 'login -> '.$loginResponse->getStatusCode().' (user: '.($app['auth']->user()?->email ?? 'none').")\n\n";
}

foreach ($uris as $uri) {
    $request = Illuminate\Http\Request::create($uri, 'GET');
    $app->instance('request', $request);

    try {
        $response = $kernel->handle($request);
        $code = $response->getStatusCode();
        echo str_pad($uri, 30).' -> '.$code."\n";

        if ($code >= 500) {
            $failed++;
            $session = $request->hasSession() ? $request->session() : null;
            if ($session && $session->has('errors')) {
                echo '   errors: '.json_encode($session->get('errors')->toArray())."\n";
            }

            $text = trim(preg_replace('/\s+/', ' ', strip_tags($response->getContent())));

            $patterns = [
                '/View \[[^\]]+\] not found/',
                '/Undefined variable [^ ]{1,60}/',
                '/Call to a member function [^ ]{1,60}/',
                '/Attempt to read property [^ ]{1,60}/',
                '/Lazy loading violation[^.]{0,140}/',
                '/[A-Za-z]+Exception: [^.]{0,160}/',
                '/[A-Za-z]+Exception - [^.]{0,160}/',
            ];

            $found = false;
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $text, $m)) {
                    echo '   -> '.trim($m[0])."\n";
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                echo '   -> '.substr($text, 0, 220)."\n";
            }
        }
    } catch (Throwable $e) {
        $failed++;
        echo str_pad($uri, 30).' -> EXCEPTION: '.$e->getMessage()."\n";
        echo '   at '.$e->getFile().':'.$e->getLine()."\n";
    }
}

echo $failed === 0 ? "\nAll routes OK\n" : "\n$failed route(s) failed\n";
