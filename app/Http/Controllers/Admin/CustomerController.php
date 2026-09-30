<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $search = trim(
            (string) $request->get('search', '')
        );

        // =====================================================
        // ORDER STATISTICS PER CUSTOMER
        // =====================================================

        $orderSummary = DB::table('orders')
            ->select(
                'user_id',
                DB::raw('COUNT(*) AS order_count'),
                DB::raw(
                    "SUM(
                        CASE
                            WHEN payment_status = 'paid'
                            THEN total
                            ELSE 0
                        END
                    ) AS total_spent"
                )
            )
            ->groupBy('user_id');

        // =====================================================
        // FAVORITES COUNT PER CUSTOMER
        // =====================================================

        $favoriteSummary = DB::table('favorites')
            ->select(
                'user_id',
                DB::raw('COUNT(*) AS favorite_count')
            )
            ->groupBy('user_id');

        // =====================================================
        // CUSTOMER QUERY
        // =====================================================

        $query = DB::table('users')
            ->leftJoinSub(
                $orderSummary,
                'order_summary',
                function ($join) {
                    $join->on(
                        'users.id',
                        '=',
                        'order_summary.user_id'
                    );
                }
            )
            ->leftJoinSub(
                $favoriteSummary,
                'favorite_summary',
                function ($join) {
                    $join->on(
                        'users.id',
                        '=',
                        'favorite_summary.user_id'
                    );
                }
            )
            ->where(
                'users.role',
                'customer'
            )
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.profile_image',
                'users.shipping_address',
                'users.province',
                'users.email_verified_at',
                'users.created_at',
                DB::raw(
                    'COALESCE(
                        order_summary.order_count,
                        0
                    ) AS order_count'
                ),
                DB::raw(
                    'COALESCE(
                        order_summary.total_spent,
                        0
                    ) AS total_spent'
                ),
                DB::raw(
                    'COALESCE(
                        favorite_summary.favorite_count,
                        0
                    ) AS favorite_count'
                )
            );

        // =====================================================
        // SEARCH
        // =====================================================

        if ($search !== '') {
            $query->where(
                function ($q) use ($search) {
                    $q->where(
                        'users.name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'users.email',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'users.phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'users.id',
                            '=',
                            is_numeric($search)
                            ? (int) $search
                            : 0
                        );
                }
            );
        }

        $customers = $query
            ->orderByDesc('users.id')
            ->paginate(10)
            ->withQueryString();

        // =====================================================
        // DASHBOARD STATISTICS
        // =====================================================

        $totalCustomers = DB::table('users')
            ->where(
                'role',
                'customer'
            )
            ->count();

        $customersWithOrders = DB::table('orders')
            ->join(
                'users',
                'orders.user_id',
                '=',
                'users.id'
            )
            ->where(
                'users.role',
                'customer'
            )
            ->distinct()
            ->count(
                'orders.user_id'
            );

        $totalCustomerOrders = DB::table('orders')
            ->join(
                'users',
                'orders.user_id',
                '=',
                'users.id'
            )
            ->where(
                'users.role',
                'customer'
            )
            ->count();

        $totalCustomerRevenue = (float) 
            DB::table('orders')
                ->join(
                    'users',
                    'orders.user_id',
                    '=',
                    'users.id'
                )
                ->where(
                    'users.role',
                    'customer'
                )
                ->where(
                    'orders.payment_status',
                    'paid'
                )
                ->sum(
                    'orders.total'
                );

        return view(
            'admin.customers.index',
            compact(
                'customers',
                'search',
                'totalCustomers',
                'customersWithOrders',
                'totalCustomerOrders',
                'totalCustomerRevenue'
            )
        );
    }

    // =========================================================
    // CUSTOMER DETAILS
    // GET /admin/customers/{customer}
    // =========================================================

    public function show(int $customer)
    {
        $customerData = DB::table('users')
            ->where(
                'id',
                $customer
            )
            ->where(
                'role',
                'customer'
            )
            ->first();

        if (!$customerData) {
            abort(
                404,
                'Customer not found.'
            );
        }

        // =====================================================
        // ORDERS
        // =====================================================

        $orders = DB::table('orders')
            ->where(
                'user_id',
                $customer
            )
            ->orderByDesc('id')
            ->get();

        // =====================================================
        // CUSTOMER STATISTICS
        // =====================================================

        $orderCount = $orders->count();

        $paidOrders = $orders
            ->where(
                'payment_status',
                'paid'
            );

        $totalSpent = (float) 
            $paidOrders->sum('total');

        $favoriteCount = DB::table('favorites')
            ->where(
                'user_id',
                $customer
            )
            ->count();

        return view(
            'admin.customers.show',
            [
                'customer' =>
                    $customerData,

                'orders' =>
                    $orders,

                'orderCount' =>
                    $orderCount,

                'totalSpent' =>
                    $totalSpent,

                'favoriteCount' =>
                    $favoriteCount,
            ]
        );
    }
}