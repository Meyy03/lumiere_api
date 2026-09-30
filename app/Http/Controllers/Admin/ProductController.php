<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403
        );
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $search = trim((string) $request->get('search'));
        $categoryId = $request->get('category');

        // NEW: Audience filter
        $audience = trim((string) $request->get('audience'));

        $query = DB::table('products')
            ->leftJoin(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )
            ->select(
                'products.*',
                'categories.name as category_name'
            );

        /**
         * Search
         */
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where(
                    'products.name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'products.id',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'products.size',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /**
         * Category Filter
         */
        if (!empty($categoryId)) {
            $query->where(
                'products.category_id',
                $categoryId
            );
        }

        /**
         * NEW: Audience Filter
         *
         * female = Women
         * male   = Men
         * all    = All / Unisex
         */
        if (
            in_array(
                $audience,
                ['female', 'male', 'all'],
                true
            )
        ) {
            $query->where(
                'products.target_audience',
                $audience
            );
        } else {
            $audience = '';
        }

        /**
         * Pagination
         */
        $products = $query
            ->orderBy(
                'products.id',
                'asc'
            )
            ->paginate(12)
            ->withQueryString();

        $categories = DB::table('categories')
            ->select(
                'id',
                'name'
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.index',
            compact(
                'products',
                'categories',
                'search',
                'categoryId',
                'audience'
            )
        );
    }

    /**
     * Create Product Page
     */
    public function create()
    {
        $this->ensureAdmin();

        $categories = DB::table('categories')
            ->select(
                'id',
                'name'
            )
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.create',
            compact('categories')
        );
    }

    /**
     * Store Product
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $validated = $this->validateProduct(
            $request,
            true
        );

        $availableSizes = $this->prepareAvailableSizes(
            $validated['available_sizes'] ?? null,
            $validated['size']
        );

        /**
         * Upload product image
         */
        $imagePath = $request
            ->file('image_file')
            ->store(
                'products',
                'public'
            );

        DB::table('products')->insert([
            'category_id' =>
                $validated['category_id'],

            'target_audience' =>
                $validated['target_audience'],

            'name' =>
                trim($validated['name']),

            'image' =>
                $imagePath,

            'size' =>
                trim($validated['size']),

            'available_sizes' =>
                json_encode(
                    $availableSizes,
                    JSON_UNESCAPED_UNICODE
                ),

            'price' =>
                $validated['price'],

            'rating' =>
                $validated['rating'] ?? 0,

            'stock' =>
                $validated['stock'],

            'description' =>
                $this->nullableTrim(
                    $validated['description'] ?? null
                ),

            'skin_type' =>
                trim(
                    $validated['skin_type']
                ),

            'product_details' =>
                $this->nullableTrim(
                    $validated['product_details'] ?? null
                ),

            'ingredients' =>
                $this->nullableTrim(
                    $validated['ingredients'] ?? null
                ),

            'is_active' =>
                $request->boolean('is_active'),

            'created_at' =>
                now(),

            'updated_at' =>
                now(),
        ]);

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Product created successfully.'
            );
    }

    /**
     * Product Details
     */
    public function show(string $id)
    {
        $this->ensureAdmin();

        $product = DB::table('products')
            ->leftJoin(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )
            ->select(
                'products.*',
                'categories.name as category_name'
            )
            ->where(
                'products.id',
                $id
            )
            ->first();

        abort_if(
            !$product,
            404
        );

        $product->available_sizes_array =
            $this->decodeAvailableSizes(
                $product->available_sizes
            );

        /**
         * Check whether product is used in an order.
         */
        $usedInOrders = DB::table('order_items')
            ->where(
                'product_id',
                $product->id
            )
            ->exists();

        return view(
            'admin.products.show',
            compact(
                'product',
                'usedInOrders'
            )
        );
    }

    /**
     * Edit Product Page
     */
    public function edit(string $id)
    {
        $this->ensureAdmin();

        $product = DB::table('products')
            ->where(
                'id',
                $id
            )
            ->first();

        abort_if(
            !$product,
            404
        );

        $categories = DB::table('categories')
            ->select(
                'id',
                'name'
            )
            ->orderBy('name')
            ->get();

        $product->available_sizes_text =
            implode(
                ', ',
                $this->decodeAvailableSizes(
                    $product->available_sizes
                )
            );

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories'
            )
        );
    }

    /**
     * Update Product
     */
    public function update(
        Request $request,
        string $id
    ) {
        $this->ensureAdmin();

        $product = DB::table('products')
            ->where(
                'id',
                $id
            )
            ->first();

        abort_if(
            !$product,
            404
        );

        $validated = $this->validateProduct(
            $request,
            false
        );

        $availableSizes = $this->prepareAvailableSizes(
            $validated['available_sizes'] ?? null,
            $validated['size']
        );

        /**
         * Keep old image by default.
         */
        $imagePath = $product->image;

        /**
         * If admin uploads a new image.
         */
        if ($request->hasFile('image_file')) {
            $newImagePath = $request
                ->file('image_file')
                ->store(
                    'products',
                    'public'
                );

            if (
                $this->isLaravelUploadedImage(
                    $product->image
                )
            ) {
                Storage::disk('public')
                    ->delete(
                        $product->image
                    );
            }

            $imagePath = $newImagePath;
        }

        DB::table('products')
            ->where(
                'id',
                $id
            )
            ->update([
                'category_id' =>
                    $validated['category_id'],

                'target_audience' =>
                    $validated['target_audience'],

                'name' =>
                    trim(
                        $validated['name']
                    ),

                'image' =>
                    $imagePath,

                'size' =>
                    trim(
                        $validated['size']
                    ),

                'available_sizes' =>
                    json_encode(
                        $availableSizes,
                        JSON_UNESCAPED_UNICODE
                    ),

                'price' =>
                    $validated['price'],

                'rating' =>
                    $validated['rating'] ?? 0,

                'stock' =>
                    $validated['stock'],

                'description' =>
                    $this->nullableTrim(
                        $validated['description'] ?? null
                    ),

                'skin_type' =>
                    trim(
                        $validated['skin_type']
                    ),

                'product_details' =>
                    $this->nullableTrim(
                        $validated['product_details'] ?? null
                    ),

                'ingredients' =>
                    $this->nullableTrim(
                        $validated['ingredients'] ?? null
                    ),

                'is_active' =>
                    $request->boolean(
                        'is_active'
                    ),

                'updated_at' =>
                    now(),
            ]);

        return redirect()
            ->route(
                'admin.products.show',
                $id
            )
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    /**
     * Delete Product
     */
    public function destroy(string $id)
    {
        $this->ensureAdmin();

        $product = DB::table('products')
            ->where(
                'id',
                $id
            )
            ->first();

        abort_if(
            !$product,
            404
        );

        $usedInOrders = DB::table('order_items')
            ->where(
                'product_id',
                $id
            )
            ->exists();

        if ($usedInOrders) {
            return redirect()
                ->route(
                    'admin.products.index'
                )
                ->with(
                    'error',
                    'This product cannot be deleted because it is used in an existing order. Mark it inactive instead.'
                );
        }

        DB::transaction(
            function () use ($id, $product) {
                /**
                 * Delete favorite relationships.
                 */
                DB::table('favorites')
                    ->where(
                        'product_id',
                        $id
                    )
                    ->delete();

                /**
                 * Delete uploaded product image.
                 */
                if (
                    $this->isLaravelUploadedImage(
                        $product->image
                    )
                ) {
                    Storage::disk('public')
                        ->delete(
                            $product->image
                        );
                }

                /**
                 * Delete product.
                 */
                DB::table('products')
                    ->where(
                        'id',
                        $id
                    )
                    ->delete();
            }
        );

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }

    /**
     * Product Validation
     */
    private function validateProduct(
        Request $request,
        bool $creating
    ): array {
        $imageRules = [
            $creating
            ? 'required'
            : 'nullable',

            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ];

        return $request->validate([
            /**
             * Category
             */
            'category_id' => [
                'required',
                'integer',
                Rule::exists(
                    'categories',
                    'id'
                ),
            ],

            /**
             * Product Target Audience
             */
            'target_audience' => [
                'required',
                'string',
                Rule::in([
                    'female',
                    'male',
                    'all',
                ]),
            ],

            /**
             * Product Name
             */
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            /**
             * Product Image
             */
            'image_file' =>
                $imageRules,

            /**
             * Default Size
             */
            'size' => [
                'required',
                'string',
                'max:50',
            ],

            /**
             * Available Sizes
             */
            'available_sizes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            /**
             * Price
             */
            'price' => [
                'required',
                'numeric',
                'gt:0',
                'max:99999999.99',
            ],

            /**
             * Rating
             */
            'rating' => [
                'nullable',
                'numeric',
                'between:0,5',
            ],

            /**
             * Stock
             */
            'stock' => [
                'required',
                'integer',
                'min:0',
                'max:999999999',
            ],

            /**
             * Description
             */
            'description' => [
                'nullable',
                'string',
            ],

            /**
             * Skin Type
             */
            'skin_type' => [
                'required',
                'string',
                'max:255',
            ],

            /**
             * Product Details
             */
            'product_details' => [
                'nullable',
                'string',
            ],

            /**
             * Ingredients
             */
            'ingredients' => [
                'nullable',
                'string',
            ],
        ], [
            /**
             * Category
             */
            'category_id.required' =>
                'Please select a category.',

            'category_id.exists' =>
                'The selected category is invalid.',

            /**
             * Target Audience
             */
            'target_audience.required' =>
                'Please select the target audience.',

            'target_audience.in' =>
                'Target audience must be Women, Men, or All / Unisex.',

            /**
             * Product Name
             */
            'name.required' =>
                'Product name is required.',

            /**
             * Product Image
             */
            'image_file.required' =>
                'Please choose a product image.',

            'image_file.image' =>
                'The selected file must be an image.',

            'image_file.mimes' =>
                'Product image must be JPG, JPEG, PNG, or WEBP.',

            'image_file.max' =>
                'Product image must not be larger than 5 MB.',

            /**
             * Size
             */
            'size.required' =>
                'Default product size is required.',

            /**
             * Price
             */
            'price.required' =>
                'Product price is required.',

            'price.gt' =>
                'Price must be greater than 0.',

            /**
             * Stock
             */
            'stock.required' =>
                'Product stock is required.',

            'stock.min' =>
                'Stock cannot be negative.',

            /**
             * Rating
             */
            'rating.between' =>
                'Rating must be between 0 and 5.',

            /**
             * Skin Type
             */
            'skin_type.required' =>
                'Skin type is required.',
        ]);
    }

    /**
     * Prepare Available Sizes
     */
    private function prepareAvailableSizes(
        ?string $sizes,
        string $defaultSize
    ): array {
        /**
         * If admin does not enter available sizes,
         * use default size only.
         */
        if (
            $sizes === null ||
            trim($sizes) === ''
        ) {
            return [
                trim($defaultSize)
            ];
        }

        /**
         * Convert:
         *
         * 50ml, 100ml, 150ml
         *
         * into an array.
         */
        $items = array_map(
            'trim',
            explode(
                ',',
                $sizes
            )
        );

        /**
         * Remove empty and duplicate sizes.
         */
        $items = array_values(
            array_unique(
                array_filter(
                    $items,
                    fn($item) =>
                        $item !== ''
                )
            )
        );

        /**
         * Make sure default size exists
         * in available sizes.
         */
        if (
            !in_array(
                trim($defaultSize),
                $items,
                true
            )
        ) {
            $items[] =
                trim($defaultSize);
        }

        return $items;
    }

    /**
     * Decode Available Sizes
     */
    private function decodeAvailableSizes(
        mixed $sizes
    ): array {
        if (empty($sizes)) {
            return [];
        }

        if (is_array($sizes)) {
            return $sizes;
        }

        $decoded = json_decode(
            $sizes,
            true
        );

        return is_array($decoded)
            ? $decoded
            : [];
    }

    /**
     * Trim nullable strings.
     */
    private function nullableTrim(
        ?string $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        return $value === ''
            ? null
            : $value;
    }

    private function isLaravelUploadedImage(
        ?string $image
    ): bool {
        if (empty($image)) {
            return false;
        }

        return str_starts_with(
            $image,
            'products/'
        );
    }
}