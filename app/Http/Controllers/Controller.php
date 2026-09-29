<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\View\View;

abstract class Controller
{
    /**
     * Shared scaffolding for every public page. Navigation and footer data
     * come from a view composer in AppServiceProvider.
     *
     * @param  array<string, mixed>  $seo
     * @param  array<string, mixed>  $data
     */
    protected function publicView(string $view, array $seo = [], array $data = []): View
    {
        return view($view, array_merge($data, [
            'seo' => Seo::make($seo['title'] ?? null, $seo['description'] ?? null, $seo['image'] ?? null),
        ]));
    }
}
