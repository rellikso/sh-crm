<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class WidgetController extends Controller
{
    /**
     * Render the external isolated iframe widget.
     */
    public function __invoke(): View
    {
        $apiBase = rtrim(config('app.url'), '/') . '/api/v1';

        return view('widget.iframe', [
            'apiBase' => $apiBase
        ]);
    }
}