<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // =========================================================
    // ADMIN ORDER LIST
    // GET /admin/orders
    // =========================================================

    public function index(Request $request)
    {
        $search = trim(
            (string) $request->query(
                'search',
                ''
            )
        );

        $status = strtolower(
            trim(
                (string) $request->query(
                    'status',
                    ''
                )
            )
        );

        $paymentStatus = strtolower(
            trim(
                (string) $request->query(
                    'payment_status',
                    ''
                )
            )
        );


        // =====================================================
        // TOTAL QUANTITY PER ORDER
        // =====================================================

        $itemCounts = DB::table('order_items')
            ->select(
                'order_id',
                DB::raw(
                    'SUM(quantity) AS item_count'
                )
            )
            ->groupBy('order_id');


        // =====================================================
        // MAIN QUERY
        // =====================================================

        $query = DB::table('orders')
            ->leftJoin(
                'users',
                'orders.user_id',
                '=',
                'users.id'
            )
            ->leftJoinSub(
                $itemCounts,
                'order_item_counts',
                function ($join) {
                    $join->on(
                        'orders.id',
                        '=',
                        'order_item_counts.order_id'
                    );
                }
            )
            ->select(
                'orders.*',
                'users.email as customer_email',
                DB::raw(
                    'COALESCE(
                        order_item_counts.item_count,
                        0
                    ) AS item_count'
                )
            );


        // =====================================================
        // SEARCH
        // =====================================================

        if ($search !== '') {
            $query->where(
                function ($q) use ($search) {
                    $q->where(
                        'orders.order_number',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'orders.customer_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'orders.customer_phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'orders.delivery_phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'users.email',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }


        // =====================================================
        // ORDER STATUS FILTER
        // =====================================================

        if ($status !== '') {

            if ($status === 'processing') {

                $query->whereIn(
                    'orders.status',
                    [
                        'pending',
                        'processing',
                    ]
                );

            } else {

                $query->where(
                    'orders.status',
                    $status
                );
            }
        }


        // =====================================================
        // PAYMENT STATUS FILTER
        // =====================================================

        if ($paymentStatus !== '') {

            $query->where(
                'orders.payment_status',
                $paymentStatus
            );
        }


        // =====================================================
        // PAGINATION
        // =====================================================

        $orders = $query
            ->orderByDesc('orders.id')
            ->paginate(10)
            ->withQueryString();


        // =====================================================
        // STATISTICS
        // =====================================================

        $totalOrders = DB::table('orders')
            ->count();


        $processingOrders = DB::table('orders')
            ->whereIn(
                'status',
                [
                    'pending',
                    'processing',
                ]
            )
            ->count();


        $deliveredOrders = DB::table('orders')
            ->where(
                'status',
                'delivered'
            )
            ->count();


        $cancelledOrders = DB::table('orders')
            ->where(
                'status',
                'cancelled'
            )
            ->count();

        $paidRevenue = (float) DB::table('orders')
            ->where(
                'payment_status',
                'paid'
            )
            ->where(
                'status',
                '!=',
                'cancelled'
            )
            ->sum('total');


        // =====================================================
        // VIEW
        // =====================================================

        return view(
            'admin.orders.index',
            compact(
                'orders',
                'search',
                'status',
                'paymentStatus',
                'totalOrders',
                'processingOrders',
                'deliveredOrders',
                'cancelledOrders',
                'paidRevenue'
            )
        );
    }


    // =========================================================
    // ADMIN ORDER DETAILS
    // GET /admin/orders/{order}
    // =========================================================

    public function show(int $order)
    {
        $orderData = DB::table('orders')
            ->leftJoin(
                'users',
                'orders.user_id',
                '=',
                'users.id'
            )
            ->select(
                'orders.*',
                'users.name as account_name',
                'users.email as customer_email'
            )
            ->where(
                'orders.id',
                $order
            )
            ->first();


        if (!$orderData) {

            abort(
                404,
                'Order not found.'
            );
        }


        // =====================================================
        // ORDER ITEMS
        // =====================================================

        $items = DB::table('order_items')
            ->leftJoin(
                'products',
                'order_items.product_id',
                '=',
                'products.id'
            )
            ->select(
                'order_items.*',
                'products.image as product_image',
                'products.is_active as product_is_active'
            )
            ->where(
                'order_items.order_id',
                $order
            )
            ->orderBy(
                'order_items.id'
            )
            ->get();


        $itemCount = (int) $items->sum(
            'quantity'
        );


        return view(
            'admin.orders.show',
            [
                'order' => $orderData,
                'items' => $items,
                'itemCount' => $itemCount,
            ]
        );
    }


    // =========================================================
    // UPDATE ORDER STATUS
    // PATCH /admin/orders/{order}/status
    // =========================================================

    public function updateStatus(
        Request $request,
        int $order
    ) {
        // =====================================================
        // VALIDATION
        // =====================================================

        $validated = $request->validate(
            [
                'status' => [
                    'required',

                    Rule::in([
                        'processing',
                        'delivered',
                        'cancelled',
                    ]),
                ],
            ],
            [
                'status.required' =>
                    'Please select an order status.',

                'status.in' =>
                    'Invalid order status selected.',
            ]
        );


        $newStatus =
            $validated['status'];


        // =====================================================
        // UPDATE INSIDE TRANSACTION
        // =====================================================

        DB::transaction(
            function () use ($order, $newStatus) {

                $orderData = DB::table('orders')
                    ->where(
                        'id',
                        $order
                    )
                    ->lockForUpdate()
                    ->first();


                if (!$orderData) {

                    abort(
                        404,
                        'Order not found.'
                    );
                }


                $currentStatus = strtolower(
                    trim(
                        (string) $orderData->status
                    )
                );


                $paymentMethod = strtolower(
                    trim(
                        (string) $orderData->payment_method
                    )
                );


                // =================================================
                // PROTECT FINAL ORDER STATES
                // =================================================
    
                if (
                    $currentStatus === 'cancelled' &&
                    $newStatus !== 'cancelled'
                ) {

                    throw ValidationException::withMessages([
                        'status' =>
                            'A cancelled order cannot be reopened.',
                    ]);
                }

                if (
                    $currentStatus === 'delivered' &&
                    $newStatus !== 'delivered'
                ) {

                    throw ValidationException::withMessages([
                        'status' =>
                            'A delivered order cannot be changed to another status.',
                    ]);
                }


                // =================================================
                // CANCEL ORDER → RESTORE STOCK
                // =================================================
    
                if (
                    $newStatus === 'cancelled' &&
                    $currentStatus !== 'cancelled'
                ) {

                    $items = DB::table('order_items')
                        ->where(
                            'order_id',
                            $order
                        )
                        ->get([
                            'product_id',
                            'quantity',
                        ]);


                    foreach ($items as $item) {

                        $productId =
                            (int) $item->product_id;

                        $quantity =
                            (int) $item->quantity;


                        if (
                            $productId <= 0 ||
                            $quantity <= 0
                        ) {
                            continue;
                        }

                        DB::table('products')
                            ->where(
                                'id',
                                $productId
                            )
                            ->increment(
                                'stock',
                                $quantity,
                                [
                                    'updated_at' =>
                                        now(),
                                ]
                            );
                    }
                }


                // =================================================
                // PAYMENT STATUS
                // =================================================
    
                $paymentStatus =
                    $orderData->payment_status;

                if (
                    $newStatus === 'delivered' &&
                    $paymentMethod === 'cod'
                ) {

                    $paymentStatus =
                        'paid';
                }


                // =================================================
                // UPDATE ORDER
                // =================================================
    
                DB::table('orders')
                    ->where(
                        'id',
                        $order
                    )
                    ->update([
                        'status' =>
                            $newStatus,

                        'payment_status' =>
                            $paymentStatus,

                        'updated_at' =>
                            now(),
                    ]);
            }
        );


        // =====================================================
        // REDIRECT
        // =====================================================

        return redirect()
            ->route(
                'admin.orders.show',
                $order
            )
            ->with(
                'success',
                'Order status updated successfully.'
            );
    }
}