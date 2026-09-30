<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CategoryImageController extends Controller
{
    public function show(
        string $filename
    ): BinaryFileResponse {
        $filename = basename($filename);


        /*
         * Extra filename protection.
         */
        if (
            !preg_match(
                '/^[A-Za-z0-9._-]+$/',
                $filename
            )
        ) {
            abort(404);
        }


        /*
         * Images are stored inside:
         *
         * storage/app/public/categories
         */
        $relativePath =
            'categories/' . $filename;


        /*
         * Get Laravel public disk.
         */
        $disk = Storage::disk('public');


        /*
         * Make sure the image exists.
         */
        if (
            !$disk->exists($relativePath)
        ) {
            abort(404);
        }


        /*
         * Get the real physical file path.
         */
        $absolutePath =
            $disk->path($relativePath);


        /*
         * Send image to browser / Flutter.
         */
        return response()->file(
            $absolutePath,
            [
                'Access-Control-Allow-Origin' =>
                    '*',

                'Cache-Control' =>
                    'public, max-age=86400',
            ]
        );
    }
}