<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function show(string $filename)
    {
        // Prevent directory traversal.
        $filename = basename($filename);

        $relativePath = 'products/' . $filename;

        /*
        |--------------------------------------------------------------------------
        | Check Laravel public storage
        |--------------------------------------------------------------------------
        */

        if (!Storage::disk('public')->exists($relativePath)) {
            abort(404, 'Product image not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Physical file
        |--------------------------------------------------------------------------
        */

        $absolutePath = Storage::disk('public')->path($relativePath);

        if (!is_file($absolutePath)) {
            abort(404, 'Product image file not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Return image
        |--------------------------------------------------------------------------
        */

        return response()->file(
            $absolutePath,
            [
                'Access-Control-Allow-Origin' => '*',
                'Cross-Origin-Resource-Policy' => 'cross-origin',
                'Cache-Control' => 'public, max-age=86400',
            ]
        );
    }
}