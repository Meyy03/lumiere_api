<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $favorites = Favorite::with('product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->pluck('product')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Favorites retrieved successfully.',
            'data' => $favorites,
        ]);
    }

    /**
     * Add a product to favorites.
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to favorites.',
            'data' => $product,
        ], $favorite->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Remove a product from favorites.
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from favorites.',
        ]);
    }
}