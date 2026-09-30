<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403
        );

        // Total number of products.
        $totalProducts = DB::table('products')->count();

        // Total number of orders.
        $totalOrders = DB::table('orders')->count();

        // Count customer accounts only.
        $totalCustomers = DB::table('users')
            ->where('role', 'customer')
            ->count();

        // Revenue from paid orders only.
        $totalRevenue = DB::table('orders')
            ->whereRaw('LOWER(payment_status) = ?', ['paid'])
            ->sum('total');

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}