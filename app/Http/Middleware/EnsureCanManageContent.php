<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin/editor gate for content management (plan.md #31).
 * Viewers can browse the admin but cannot mutate anything.
 */
class EnsureCanManageContent
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->canManageContent(),
            403,
            'Your account does not have permission to change content.'
        );

        return $next($request);
    }
}
