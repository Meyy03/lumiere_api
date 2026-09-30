<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{

    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount([
                'products' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderBy('id')
            ->get()
            ->map(function (Category $category) {
                return [
                    'id' =>
                        $category->id,

                    'name' =>
                        $category->name,

                    'slug' =>
                        $category->slug,

                    /*
                     * Keep old Flutter SVG field temporarily.
                     */
                    'icon' =>
                        $category->icon,

                    /*
                     * Database storage path.
                     */
                    'image' =>
                        $category->image,

                    /*
                     * URL Flutter can request.
                     *
                     * Example:
                     * http://127.0.0.1:8000/
                     * category-images/example.png
                     */
                    'image_url' =>
                        $this->categoryImageUrl(
                            $category->image
                        ),

                    'products_count' =>
                        $category->products_count,

                    'created_at' =>
                        $category->created_at,

                    'updated_at' =>
                        $category->updated_at,
                ];
            })
            ->values();

        return response()->json(
            $categories
        );
    }


    public function products(
        int $id
    ): JsonResponse {
        $category = Category::query()
            ->findOrFail($id);

        $products = $category
            ->products()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'category' => [
                'id' =>
                    $category->id,

                'name' =>
                    $category->name,

                'slug' =>
                    $category->slug,

                'icon' =>
                    $category->icon,

                'image' =>
                    $category->image,

                'image_url' =>
                    $this->categoryImageUrl(
                        $category->image
                    ),

                'products_count' =>
                    $products->count(),
            ],

            'products' => $products,
        ]);
    }


    /**
     * Build a category image URL for Flutter.
     */
    private function categoryImageUrl(
        ?string $image
    ): ?string {
        if (
            $image === null ||
            trim($image) === ''
        ) {
            return null;
        }

        $image = trim($image);


        /*
         * -----------------------------------------------------
         * FULL HTTP / HTTPS URL
         * -----------------------------------------------------
         */

        if (
            str_starts_with(
                $image,
                'http://'
            ) ||
            str_starts_with(
                $image,
                'https://'
            )
        ) {
            $path = parse_url(
                $image,
                PHP_URL_PATH
            );

            if (is_string($path)) {

                if (
                    str_starts_with(
                        $path,
                        '/category-images/'
                    )
                ) {
                    $filename =
                        basename($path);

                    return url(
                        '/category-images/' .
                        rawurlencode($filename)
                    );
                }


                /*
                 * Convert old storage URL:
                 *
                 * /storage/categories/example.png
                 *
                 * to:
                 *
                 * /category-images/example.png
                 */
                if (
                    str_starts_with(
                        $path,
                        '/storage/categories/'
                    )
                ) {
                    $filename =
                        basename($path);

                    return url(
                        '/category-images/' .
                        rawurlencode($filename)
                    );
                }
            }
            return $image;
        }


        /*
         * -----------------------------------------------------
         * DATABASE PATH
         * -----------------------------------------------------
         */

        if (
            str_starts_with(
                $image,
                'categories/'
            )
        ) {
            $filename =
                basename($image);

            return url(
                '/category-images/' .
                rawurlencode($filename)
            );
        }


        /*
         * -----------------------------------------------------
         * OLD STORAGE PATH
         * -----------------------------------------------------
         */

        if (
            str_starts_with(
                $image,
                '/storage/categories/'
            ) ||
            str_starts_with(
                $image,
                'storage/categories/'
            )
        ) {
            $filename =
                basename($image);

            return url(
                '/category-images/' .
                rawurlencode($filename)
            );
        }

        return null;
    }
}