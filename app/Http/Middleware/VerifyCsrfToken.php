<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // CKEditor's bundled CKFinder upload adapter sends its own client-generated
        // "ckCsrfToken" form field instead of Laravel's CSRF token, so this route
        // can never pass the normal check. It's still behind the admin "auth" middleware.
        'admin/editor/image/upload',
    ];
}
