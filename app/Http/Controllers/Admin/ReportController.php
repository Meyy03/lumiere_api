<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedRange = $this->normalizeRange(
            $request->query('range', '30')
        );

        $data = $this->getReportData(
            $selectedRange
        );

        return view(
            'admin.reports.index',
            $data
        );
    }


    // =========================================================
    // EXPORT PDF
    // =========================================================

    public function exportPdf(Request $request)
    {
        $selectedRange = $this->normalizeRange(
            $request->query('range', '30')
        );

        $data = $this->getReportData(
            $selectedRange
        );


        // =====================================================
        // LOGO FOR PDF
        // =====================================================

        $logoPath = public_path(
            'images/admin/lumiere-logo.png'
        );

        $data['logoData'] = null;

        if (file_exists($logoPath)) {

            $mimeType = mime_content_type(
                $logoPath
            );

            if (!$mimeType) {
                $mimeType = 'image/png';
            }

            $data['logoData'] =
                'data:' .
                $mimeType .
                ';base64,' .
                base64_encode(
                    file_get_contents(
                        $logoPath
                    )
                );
        }


        // =====================================================
        // CREATE A4 PORTRAIT PDF
        // =====================================================

        $pdf = Pdf::loadView(
            'admin.reports.pdf',
            $data
        );

        $pdf->setPaper(
            'a4',
            'portrait'
        );


        // =====================================================
        // RENDER ONCE
        // =====================================================

        $pdf->render();

        $dompdf =
            $pdf->getDomPDF();

        $canvas =
            $dompdf->getCanvas();

        $fontMetrics =
            $dompdf->getFontMetrics();

        $font =
            $fontMetrics->getFont(
                'Helvetica',
                'normal'
            );


        // =====================================================
        // PAGE NUMBER — REAL BOTTOM RIGHT
        // =====================================================

        $pageWidth =
            $canvas->get_width();

        $pageHeight =
            $canvas->get_height();

        $canvas->page_text(
            $pageWidth - 120,
            $pageHeight - 24,
            'Page {PAGE_NUM} of {PAGE_COUNT}',
            $font,
            7,
            [
                0.45,
                0.40,
                0.43,
            ]
        );


        // =====================================================
        // FILE NAME
        // =====================================================

        $fileRange =
            $selectedRange === 'all'
            ? 'All_Time'
            : $selectedRange . '_Days';


        $fileName =
            'Lumiere_Report_' .
            $fileRange .
            '_' .
            now()->format(
                'Y-m-d_H-i-s'
            ) .
            '.pdf';

        return response(
            $dompdf->output(),
            200,
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'attachment; filename="' .
                    $fileName .
                    '"',
            ]
        );
    }





    // =========================================================
    // NORMALIZE RANGE
    // =========================================================

    private function normalizeRange(
        mixed $range
    ): string {

        $range = (string) $range;

        $allowed = [
            '7',
            '30',
            '90',
            'all',
        ];

        return in_array(
            $range,
            $allowed,
            true
        )
            ? $range
            : '30';
    }


    // =========================================================
    // GET START DATE
    // =========================================================

    private function getStartDate(
        string $range
    ): ?Carbon {

        return match ($range) {

            '7' =>
                now()
                    ->subDays(6)
                    ->startOfDay(),

            '30' =>
                now()
                    ->subDays(29)
                    ->startOfDay(),

            '90' =>
                now()
                    ->subDays(89)
                    ->startOfDay(),

            default =>
                null,
        };
    }


    // =========================================================
    // RANGE LABEL
    // =========================================================

    private function getRangeLabel(
        string $range
    ): string {

        return match ($range) {

            '7' =>
                'Last 7 Days',

            '30' =>
                'Last 30 Days',

            '90' =>
                'Last 90 Days',

            'all' =>
                'All Time',

            default =>
                'Last 30 Days',
        };
    }


    // =========================================================
    // APPLY DATE RANGE
    // =========================================================

    private function applyRange(
        $query,
        ?Carbon $startDate,
        string $column = 'created_at'
    ) {

        if ($startDate) {

            $query->where(
                $column,
                '>=',
                $startDate
            );
        }

        return $query;
    }


    // =========================================================
    // GET REPORT DATA
    // =========================================================

    private function getReportData(
        string $selectedRange
    ): array {

        $startDate = $this->getStartDate(
            $selectedRange
        );

        $rangeLabel = $this->getRangeLabel(
            $selectedRange
        );


        // =====================================================
        // TOTAL ORDERS
        // =====================================================

        $totalOrdersQuery =
            DB::table('orders');

        $this->applyRange(
            $totalOrdersQuery,
            $startDate
        );

        $totalOrders =
            $totalOrdersQuery->count();


        // =====================================================
        // PAID ORDERS
        //
        // Cancelled orders are NOT counted as paid sales.
        // =====================================================

        $paidOrdersQuery =
            DB::table('orders')
                ->where(
                    'payment_status',
                    'paid'
                )
                ->where(
                    'status',
                    '!=',
                    'cancelled'
                );

        $this->applyRange(
            $paidOrdersQuery,
            $startDate
        );

        $paidOrders =
            $paidOrdersQuery->count();


        // =====================================================
        // TOTAL REVENUE
        //
        // Only paid + non-cancelled orders.
        // =====================================================

        $revenueQuery =
            DB::table('orders')
                ->where(
                    'payment_status',
                    'paid'
                )
                ->where(
                    'status',
                    '!=',
                    'cancelled'
                );

        $this->applyRange(
            $revenueQuery,
            $startDate
        );

        $totalRevenue =
            (float) $revenueQuery->sum(
                'total'
            );


        // =====================================================
        // AVERAGE ORDER VALUE
        // =====================================================

        $averageOrderValue =
            $paidOrders > 0
            ? round(
                $totalRevenue /
                $paidOrders,
                2
            )
            : 0.00;


        // =====================================================
        // PROCESSING ORDERS
        // =====================================================

        $processingQuery =
            DB::table('orders')
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'processing',
                    ]
                );

        $this->applyRange(
            $processingQuery,
            $startDate
        );

        $processingOrders =
            $processingQuery->count();


        // =====================================================
        // DELIVERED ORDERS
        // =====================================================

        $deliveredQuery =
            DB::table('orders')
                ->where(
                    'status',
                    'delivered'
                );

        $this->applyRange(
            $deliveredQuery,
            $startDate
        );

        $deliveredOrders =
            $deliveredQuery->count();


        // =====================================================
        // CANCELLED ORDERS
        // =====================================================

        $cancelledQuery =
            DB::table('orders')
                ->where(
                    'status',
                    'cancelled'
                );

        $this->applyRange(
            $cancelledQuery,
            $startDate
        );

        $cancelledOrders =
            $cancelledQuery->count();


        // =====================================================
        // TOTAL CUSTOMERS
        // =====================================================

        $totalCustomers =
            DB::table('users')
                ->where(
                    'role',
                    'customer'
                )
                ->count();


        // =====================================================
        // ITEMS SOLD
        //
        // Exclude cancelled orders.
        // =====================================================

        $itemsQuery =
            DB::table('order_items')
                ->join(
                    'orders',
                    'order_items.order_id',
                    '=',
                    'orders.id'
                )
                ->where(
                    'orders.payment_status',
                    'paid'
                )
                ->where(
                    'orders.status',
                    '!=',
                    'cancelled'
                );

        $this->applyRange(
            $itemsQuery,
            $startDate,
            'orders.created_at'
        );

        $totalItemsSold =
            (int) $itemsQuery->sum(
                'order_items.quantity'
            );


        // =====================================================
        // SALES TREND
        //
        // Paid + non-cancelled only.
        // =====================================================

        $salesTrendQuery =
            DB::table('orders')
                ->where(
                    'payment_status',
                    'paid'
                )
                ->where(
                    'status',
                    '!=',
                    'cancelled'
                );

        $this->applyRange(
            $salesTrendQuery,
            $startDate
        );

        $salesTrend =
            $salesTrendQuery
                ->select(
                    DB::raw(
                        'DATE(created_at) AS sales_date'
                    ),

                    DB::raw(
                        'COUNT(*) AS order_count'
                    ),

                    DB::raw(
                        'SUM(total) AS revenue'
                    )
                )
                ->groupByRaw(
                    'DATE(created_at)'
                )
                ->orderBy(
                    'sales_date'
                )
                ->get();


        // =====================================================
        // PAYMENT METHODS
        //
        // Only successful non-cancelled sales.
        // =====================================================

        $paymentQuery =
            DB::table('orders')
                ->where(
                    'payment_status',
                    'paid'
                )
                ->where(
                    'status',
                    '!=',
                    'cancelled'
                );

        $this->applyRange(
            $paymentQuery,
            $startDate
        );

        $paymentMethods =
            $paymentQuery
                ->select(
                    'payment_method',

                    DB::raw(
                        'COUNT(*) AS order_count'
                    ),

                    DB::raw(
                        'SUM(total) AS revenue'
                    )
                )
                ->groupBy(
                    'payment_method'
                )
                ->orderByDesc(
                    'revenue'
                )
                ->get();


        // =====================================================
        // TOP PRODUCTS
        //
        // Exclude cancelled orders.
        // =====================================================

        $topProductsQuery =
            DB::table('order_items')

                ->join(
                    'orders',
                    'order_items.order_id',
                    '=',
                    'orders.id'
                )

                ->leftJoin(
                    'products',
                    'order_items.product_id',
                    '=',
                    'products.id'
                )

                ->leftJoin(
                    'categories',
                    'products.category_id',
                    '=',
                    'categories.id'
                )

                ->where(
                    'orders.payment_status',
                    'paid'
                )

                ->where(
                    'orders.status',
                    '!=',
                    'cancelled'
                );


        $this->applyRange(
            $topProductsQuery,
            $startDate,
            'orders.created_at'
        );


        $topProducts =
            $topProductsQuery
                ->select(
                    'order_items.product_id',

                    'order_items.product_name',

                    'products.image as product_image',

                    'products.stock',

                    DB::raw(
                        "
                        COALESCE(
                            categories.name,
                            'Uncategorized'
                        ) AS category_name
                        "
                    ),

                    DB::raw(
                        '
                        SUM(
                            order_items.quantity
                        ) AS quantity_sold
                        '
                    ),

                    DB::raw(
                        '
                        SUM(
                            order_items.line_total
                        ) AS sales_amount
                        '
                    )
                )

                ->groupBy(
                    'order_items.product_id',
                    'order_items.product_name',
                    'products.image',
                    'products.stock',
                    'categories.id',
                    'categories.name'
                )

                ->orderByDesc(
                    'quantity_sold'
                )

                ->orderByDesc(
                    'sales_amount'
                )

                ->limit(5)

                ->get();


        // =====================================================
        // CATEGORY PERFORMANCE
        //
        // Exclude cancelled orders.
        // =====================================================

        $categoryQuery =
            DB::table('order_items')

                ->join(
                    'orders',
                    'order_items.order_id',
                    '=',
                    'orders.id'
                )

                ->leftJoin(
                    'products',
                    'order_items.product_id',
                    '=',
                    'products.id'
                )

                ->leftJoin(
                    'categories',
                    'products.category_id',
                    '=',
                    'categories.id'
                )

                ->where(
                    'orders.payment_status',
                    'paid'
                )

                ->where(
                    'orders.status',
                    '!=',
                    'cancelled'
                );


        $this->applyRange(
            $categoryQuery,
            $startDate,
            'orders.created_at'
        );


        $categoryPerformance =
            $categoryQuery
                ->select(

                    DB::raw(
                        "
                        COALESCE(
                            categories.name,
                            'Uncategorized'
                        ) AS category_name
                        "
                    ),

                    DB::raw(
                        '
                        SUM(
                            order_items.quantity
                        ) AS units_sold
                        '
                    ),

                    DB::raw(
                        '
                        SUM(
                            order_items.line_total
                        ) AS revenue
                        '
                    )
                )

                ->groupBy(
                    'categories.id',
                    'categories.name'
                )

                ->orderByDesc(
                    'units_sold'
                )

                ->limit(5)

                ->get();


        $totalCategoryUnits =
            (int) $categoryPerformance->sum(
                'units_sold'
            );


        // =====================================================
        // TOP CUSTOMERS
        //
        // order_count:
        // Counts orders placed by the customer.
        //
        // total_spent:
        // Counts only paid + non-cancelled orders.
        // =====================================================

        $topCustomers =
            DB::table('users')

                ->leftJoin(
                    'orders',

                    function ($join) use ($startDate) {

                        $join->on(
                            'users.id',
                            '=',
                            'orders.user_id'
                        );

                        if ($startDate) {

                            $join->where(
                                'orders.created_at',
                                '>=',
                                $startDate
                            );
                        }
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

                    DB::raw(
                        '
                        COUNT(
                            orders.id
                        ) AS order_count
                        '
                    ),

                    DB::raw(
                        "
                        SUM(
                            CASE
                                WHEN
                                    orders.payment_status = 'paid'
                                    AND
                                    orders.status != 'cancelled'
                                THEN
                                    orders.total
                                ELSE
                                    0
                            END
                        ) AS total_spent
                        "
                    )
                )

                ->groupBy(
                    'users.id',
                    'users.name',
                    'users.email'
                )

                ->orderByDesc(
                    'total_spent'
                )

                ->orderByDesc(
                    'order_count'
                )

                ->limit(5)

                ->get();


        // =====================================================
        // ORDER ITEM COUNTS
        // =====================================================

        $orderItemSummary =
            DB::table('order_items')

                ->select(
                    'order_id',

                    DB::raw(
                        'SUM(quantity) AS items_count'
                    )
                )

                ->groupBy(
                    'order_id'
                );


        // =====================================================
        // RECENT TRANSACTIONS
        // =====================================================

        $recentOrdersQuery =
            DB::table('orders')

                ->leftJoin(
                    'users',
                    'orders.user_id',
                    '=',
                    'users.id'
                )

                ->leftJoinSub(
                    $orderItemSummary,
                    'order_item_summary',

                    function ($join) {

                        $join->on(
                            'orders.id',
                            '=',
                            'order_item_summary.order_id'
                        );
                    }
                );


        $this->applyRange(
            $recentOrdersQuery,
            $startDate,
            'orders.created_at'
        );


        $recentOrders =
            $recentOrdersQuery
                ->select(
                    'orders.*',

                    'users.email as customer_email',

                    DB::raw(
                        '
                        COALESCE(
                            order_item_summary.items_count,
                            0
                        ) AS items_count
                        '
                    )
                )

                ->orderByDesc(
                    'orders.id'
                )

                ->limit(8)

                ->get();


        // =====================================================
        // ORDER STATUS SUMMARY
        // =====================================================

        $statusSummary =
            collect([
                [
                    'name' =>
                        'Processing',

                    'count' =>
                        $processingOrders,
                ],

                [
                    'name' =>
                        'Delivered',

                    'count' =>
                        $deliveredOrders,
                ],

                [
                    'name' =>
                        'Cancelled',

                    'count' =>
                        $cancelledOrders,
                ],
            ]);


        // =====================================================
        // RETURN DATA
        // =====================================================

        return [

            'selectedRange' =>
                $selectedRange,

            'rangeLabel' =>
                $rangeLabel,

            'totalOrders' =>
                $totalOrders,

            'paidOrders' =>
                $paidOrders,

            'totalRevenue' =>
                $totalRevenue,

            'averageOrderValue' =>
                $averageOrderValue,

            'processingOrders' =>
                $processingOrders,

            'deliveredOrders' =>
                $deliveredOrders,

            'cancelledOrders' =>
                $cancelledOrders,

            'totalCustomers' =>
                $totalCustomers,

            'totalItemsSold' =>
                $totalItemsSold,

            'salesTrend' =>
                $salesTrend,

            'paymentMethods' =>
                $paymentMethods,

            'topProducts' =>
                $topProducts,

            'categoryPerformance' =>
                $categoryPerformance,

            'totalCategoryUnits' =>
                $totalCategoryUnits,

            'topCustomers' =>
                $topCustomers,

            'recentOrders' =>
                $recentOrders,

            'statusSummary' =>
                $statusSummary,
        ];
    }
}