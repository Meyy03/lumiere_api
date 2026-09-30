<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with('category')
            ->where('is_active', true);

        /**
         * Optional Search
         */
        if ($request->filled('search')) {
            $search = trim(
                $request
                    ->string('search')
                    ->toString()
            );

            $query->where(
                function ($q) use ($search) {
                    $q->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'description',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }

        /**
         * Optional Category
         */
        if ($request->filled('category')) {
            $category = trim(
                $request
                    ->string('category')
                    ->toString()
            );

            $query->whereHas(
                'category',
                function ($q) use ($category) {
                    $q->where(
                        'slug',
                        $category
                    );
                }
            );
        }

        $products = $query
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully.',
            'data' => $products,
        ]);
    }

    public function recommended(Request $request): JsonResponse
    {
        $user = $request->user();

        $preference = $user->shopping_preference;

        if ($preference === null) {
            return response()->json([
                'success' => true,
                'message' => 'Shopping preference has not been selected yet.',
                'shopping_preference' => null,
                'requires_preference' => true,
                'data' => [],
            ]);
        }

        $query = Product::query()
            ->with('category')
            ->where('is_active', true);

        if ($preference === 'female') {
            $query
                ->whereIn(
                    'target_audience',
                    [
                        'female',
                        'all',
                    ]
                )
                ->orderByRaw(
                    "
                    CASE
                        WHEN target_audience = 'female' THEN 0
                        WHEN target_audience = 'all' THEN 1
                        ELSE 2
                    END
                    "
                );
        } elseif ($preference === 'male') {
            $query
                ->whereIn(
                    'target_audience',
                    [
                        'male',
                        'all',
                    ]
                )
                ->orderByRaw(
                    "
                    CASE
                        WHEN target_audience = 'male' THEN 0
                        WHEN target_audience = 'all' THEN 1
                        ELSE 2
                    END
                    "
                );
        } elseif ($preference === 'all') {
            // Show all active products.
        }

        $products = $query
            ->orderByDesc('rating')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Recommended products retrieved successfully.',
            'shopping_preference' => $preference,
            'requires_preference' => false,
            'data' => $products,
        ]);
    }

    public function bestSelling(): JsonResponse
    {
        $products = Product::query()

            /**
             * Include Category
             */
            ->with('category')

            /**
             * Only active products
             */
            ->where(
                'is_active',
                true
            )

            /**
             * Only products that actually
             * appear in a non-cancelled order.
             */
            ->whereHas(
                'orderItems',
                function ($query) {
                    $query->whereHas(
                        'order',
                        function ($orderQuery) {
                            $orderQuery->where(
                                'status',
                                '!=',
                                'cancelled'
                            );
                        }
                    );
                }
            )

            ->withSum(
                [
                    'orderItems as total_sold' =>
                        function ($query) {
                            $query->whereHas(
                                'order',
                                function ($orderQuery) {
                                    $orderQuery->where(
                                        'status',
                                        '!=',
                                        'cancelled'
                                    );
                                }
                            );
                        },
                ],
                'quantity'
            )

            /**
             * Highest sales first.
             */
            ->orderByDesc('total_sold')

            /**
             * If two products have the same
             * sales, higher rating comes first.
             */
            ->orderByDesc('rating')

            /**
             * Home screen only needs top 10.
             */
            ->limit(10)

            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Best selling products retrieved successfully.',
            'data' => $products,
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * PRODUCT DETAILS
     * --------------------------------------------------------------------------
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with('category')
            ->where(
                'is_active',
                true
            )
            ->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully.',
            'data' => $product,
        ]);
    }
}
