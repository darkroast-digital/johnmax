<?php

namespace Shiva\Middleware;

use Interop\Container\ContainerInterface;

class RedirectIfNotAuthenticated
{

    public function __invoke($request, $response, $next)
    {
        session_start();
        if (!isset($_SESSION['logged'])) {
            $response = $response->withRedirect('/admin/login');
        }

        return $next($request, $response);
    }
}