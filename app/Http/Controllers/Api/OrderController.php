<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private const SHIPPING_FEE = 2.00;

    private const DISCOUNT = 0.00;


    // =========================================================
    // GET /api/orders
    // =========================================================

    public function index(
        Request $request
    ): JsonResponse {

        $orders = Order::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->with([
                'items.product',
            ])
            ->latest('ordered_at')
            ->latest('id')
            ->get();

        return response()->json([
            'data' => $orders,
        ]);
    }


    // =========================================================
    // POST /api/orders
    //
    // CREATE ORDER FROM FLUTTER
    // =========================================================

    public function store(
        Request $request
    ): JsonResponse {

        $request->merge([
            'payment_method' => strtolower(
                trim(
                    (string) $request->input(
                        'payment_method'
                    )
                )
            ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'customer_name' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'customer_phone' => [
                    'required',
                    'string',
                    'max:30',
                    'regex:/^\+?[0-9\s\-]{8,30}$/',
                ],

                'delivery_phone' => [
                    'required',
                    'string',
                    'max:30',
                    'regex:/^\+?[0-9\s\-]{8,30}$/',
                ],

                'delivery_address' => [
                    'required',
                    'string',
                    'max:1000',
                ],

                'delivery_province' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'contact_via_telegram' => [
                    'nullable',
                    'boolean',
                ],

                'payment_method' => [
                    'required',
                    'string',
                    'in:cod,aba,acleda,card',
                ],

                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.product_id' => [
                    'required',
                    'integer',
                    'exists:products,id',
                ],

                'items.*.selected_size' => [
                    'required',
                    'string',
                    'max:50',
                ],

                'items.*.quantity' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:99',
                ],
            ],
            [
                'customer_name.required' =>
                    'Customer name is required.',

                'customer_phone.required' =>
                    'Customer phone number is required.',

                'customer_phone.regex' =>
                    'Enter a valid customer phone number.',

                'delivery_phone.required' =>
                    'Delivery phone number is required.',

                'delivery_phone.regex' =>
                    'Enter a valid delivery phone number.',

                'delivery_address.required' =>
                    'Delivery address is required.',

                'delivery_province.required' =>
                    'Province or city is required.',

                'payment_method.required' =>
                    'Payment method is required.',

                'payment_method.in' =>
                    'The selected payment method is invalid.',

                'items.required' =>
                    'The order must contain at least one product.',

                'items.min' =>
                    'The order must contain at least one product.',
            ]
        );


        $user = $request->user();


        /*
        |--------------------------------------------------------------------------
        | CREATE ORDER TRANSACTION
        |--------------------------------------------------------------------------
        */

        $createdOrder = DB::transaction(
            function () use ($validated, $user) {

                $preparedItems = [];

                $subtotal = 0.00;


                // =================================================
                // VALIDATE PRODUCTS + SERVER PRICES
                // =================================================
    
                foreach (
                    $validated['items']
                    as $index => $requestedItem
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lock product row while processing order.
                    |--------------------------------------------------------------------------
                    */

                    $product = Product::query()
                        ->where(
                            'id',
                            $requestedItem['product_id']
                        )
                        ->lockForUpdate()
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Product must exist.
                    |--------------------------------------------------------------------------
                    */

                    if (!$product) {

                        throw ValidationException::withMessages([
                            "items.$index.product_id" =>
                                'The selected product does not exist.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Product must be active.
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isset($product->is_active) &&
                        !$product->is_active
                    ) {

                        throw ValidationException::withMessages([
                            "items.$index.product_id" =>
                                "{$product->name} is currently unavailable.",
                        ]);
                    }


                    $quantity = (int) 
                        $requestedItem['quantity'];


                    /*
                    |--------------------------------------------------------------------------
                    | Check available stock.
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $quantity >
                        (int) $product->stock
                    ) {

                        throw ValidationException::withMessages([
                            "items.$index.quantity" =>
                                "Only {$product->stock} item(s) of {$product->name} are available.",
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Validate selected size.
                    |--------------------------------------------------------------------------
                    */

                    $selectedSize = trim(
                        (string) 
                        $requestedItem['selected_size']
                    );


                    $sizes = $this->sizeOptions(
                        $product
                    );


                    $sizeIndex = array_search(
                        $selectedSize,
                        $sizes,
                        true
                    );


                    if ($sizeIndex === false) {

                        throw ValidationException::withMessages([
                            "items.$index.selected_size" =>
                                "The selected size is not available for {$product->name}.",
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Calculate price on Laravel server.
                    |--------------------------------------------------------------------------
                    */

                    $unitPrice =
                        $this->priceForSize(
                            $product,
                            $sizeIndex,
                            count($sizes)
                        );


                    $lineTotal = round(
                        $unitPrice * $quantity,
                        2
                    );


                    $subtotal += $lineTotal;


                    // =================================================
                    // REDUCE PRODUCT STOCK
                    // =================================================
    
                    $product->stock =
                        (int) $product->stock -
                        $quantity;

                    $product->save();


                    // =================================================
                    // PREPARE ORDER ITEM
                    // =================================================
    
                    $preparedItems[] = [

                        'product_id' =>
                            $product->id,

                        'product_name' =>
                            $product->name,

                        'selected_size' =>
                            $selectedSize,

                        'quantity' =>
                            $quantity,

                        'unit_price' =>
                            $unitPrice,

                        'line_total' =>
                            $lineTotal,
                    ];
                }


                // =================================================
                // ORDER TOTAL
                // =================================================
    
                $subtotal = round(
                    $subtotal,
                    2
                );


                $shippingFee =
                    self::SHIPPING_FEE;


                $discount =
                    self::DISCOUNT;


                $total = round(
                    $subtotal +
                    $shippingFee -
                    $discount,
                    2
                );


                // =================================================
                // PAYMENT METHOD
                // =================================================
    
                $paymentMethod =
                    $validated['payment_method'];


                // =================================================
                // PAYMENT STATUS
                // =================================================
                $paymentStatus =
                    $paymentMethod === 'cod'
                    ? 'pending'
                    : 'paid';


                // =================================================
                // UNIQUE ORDER NUMBER
                // =================================================
    
                $orderNumber =
                    $this->generateOrderNumber();


                $now = now();


                // =================================================
                // CREATE ORDER
                // =================================================
    
                $orderId = DB::table('orders')
                    ->insertGetId([

                        'order_number' =>
                            $orderNumber,

                        'user_id' =>
                            $user->id,

                        'customer_name' =>
                            trim(
                                $validated[
                                    'customer_name'
                                ]
                            ),

                        'customer_phone' =>
                            trim(
                                $validated[
                                    'customer_phone'
                                ]
                            ),

                        'delivery_phone' =>
                            trim(
                                $validated[
                                    'delivery_phone'
                                ]
                            ),

                        'delivery_address' =>
                            trim(
                                $validated[
                                    'delivery_address'
                                ]
                            ),

                        'delivery_province' =>
                            trim(
                                $validated[
                                    'delivery_province'
                                ]
                            ),

                        'contact_via_telegram' =>
                            !empty(
                            $validated[
                                'contact_via_telegram'
                            ]
                        )
                            ? 1
                            : 0,

                        'payment_method' =>
                            $paymentMethod,

                        'payment_status' =>
                            $paymentStatus,


                        /*
                        |--------------------------------------------------------------------------
                        | ORDER STATUS
                        |--------------------------------------------------------------------------
                        */

                        'status' =>
                            'pending',

                        'subtotal' =>
                            $subtotal,

                        'shipping_fee' =>
                            $shippingFee,

                        'discount' =>
                            $discount,

                        'total' =>
                            $total,

                        'ordered_at' =>
                            $now,

                        'created_at' =>
                            $now,

                        'updated_at' =>
                            $now,
                    ]);


                // =================================================
                // CREATE ORDER ITEMS
                // =================================================
    
                foreach (
                    $preparedItems as $item
                ) {

                    DB::table('order_items')
                        ->insert([

                            'order_id' =>
                                $orderId,

                            'product_id' =>
                                $item[
                                    'product_id'
                                ],

                            'product_name' =>
                                $item[
                                    'product_name'
                                ],

                            'selected_size' =>
                                $item[
                                    'selected_size'
                                ],

                            'quantity' =>
                                $item[
                                    'quantity'
                                ],

                            'unit_price' =>
                                $item[
                                    'unit_price'
                                ],

                            'line_total' =>
                                $item[
                                    'line_total'
                                ],

                            'created_at' =>
                                $now,

                            'updated_at' =>
                                $now,
                        ]);
                }


                // =================================================
                // RETURN COMPLETE ORDER
                // =================================================
    
                return Order::query()
                    ->where(
                        'id',
                        $orderId
                    )
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->with([
                        'items.product',
                    ])
                    ->firstOrFail();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | API RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json(
            [
                'message' =>
                    'Order created successfully.',

                'data' =>
                    $createdOrder,
            ],
            201
        );
    }


    // =========================================================
    // GET /api/orders/{id}
    //
    // USER MAY ONLY VIEW THEIR OWN ORDER
    // =========================================================

    public function show(
        Request $request,
        int $id
    ): JsonResponse {

        $order = Order::query()
            ->where(
                'id',
                $id
            )
            ->where(
                'user_id',
                $request->user()->id
            )
            ->with([
                'items.product',
            ])
            ->first();


        if (!$order) {

            return response()->json(
                [
                    'message' =>
                        'Order not found.',
                ],
                404
            );
        }


        return response()->json([
            'data' => $order,
        ]);
    }


    // =========================================================
    // PRODUCT SIZE OPTIONS
    // =========================================================

    private function sizeOptions(
        Product $product
    ): array {

        $availableSizes =
            $product->available_sizes;

        if (is_array($availableSizes)) {

            $sizes = array_values(
                array_filter(
                    array_map(
                        fn($size) =>
                            trim(
                                (string) $size
                            ),
                        $availableSizes
                    ),
                    fn($size) =>
                        $size !== ''
                )
            );


            if (!empty($sizes)) {

                return $sizes;
            }
        }

        if (
            is_string($availableSizes) &&
            trim($availableSizes) !== ''
        ) {

            $decoded = json_decode(
                $availableSizes,
                true
            );


            if (is_array($decoded)) {

                $sizes = array_values(
                    array_filter(
                        array_map(
                            fn($size) =>
                                trim(
                                    (string) $size
                                ),
                            $decoded
                        ),
                        fn($size) =>
                            $size !== ''
                    )
                );


                if (!empty($sizes)) {

                    return $sizes;
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback to default product size.
        |--------------------------------------------------------------------------
        */

        $defaultSize = trim(
            (string) $product->size
        );


        return $defaultSize !== ''
            ? [$defaultSize]
            : [];
    }


    // =========================================================
    // SERVER-SIDE SIZE PRICE
    // =========================================================

    private function priceForSize(
        Product $product,
        int $sizeIndex,
        int $sizeCount
    ): float {

        $basePrice =
            (float) $product->price;


        /*
        |--------------------------------------------------------------------------
        | Only one size.
        |--------------------------------------------------------------------------
        */

        if ($sizeCount <= 1) {

            return round(
                $basePrice,
                2
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Size price multiplier.
        |--------------------------------------------------------------------------
        */

        $multiplier = match ($sizeIndex) {

            0 => 0.75,

            1 => 1.00,

            2 => 1.40,

            default => 1.00,
        };


        return round(
            $basePrice * $multiplier,
            2
        );
    }


    // =========================================================
    // UNIQUE ORDER NUMBER
    // =========================================================

    private function generateOrderNumber(): string
    {
        do {

            $orderNumber =
                'LUM-' .
                now()->format('Ymd') .
                '-' .
                Str::upper(
                    Str::random(6)
                );


            $exists = Order::query()
                ->where(
                    'order_number',
                    $orderNumber
                )
                ->exists();

        } while ($exists);


        return $orderNumber;
    }
}