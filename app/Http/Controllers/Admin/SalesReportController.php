<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfMonth();

        $query = Transaction::with('products')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('method')) {
            $query->where('purchase_method', $request->method);
        }

        if ($request->filled('product_id')) {
            $query->whereHas('products', fn($q) => $q->where('products.id', $request->product_id));
        }

        if ($request->filled('category_id')) {
            $query->whereHas('products.category', fn($q) => $q->where('categories.id', $request->category_id));
        }

        $transactions = $query->get();

        // Summary
        $totalTransactions = $transactions->count();
        $totalRevenue = $transactions->sum('total_price');
        $totalItemsSold = 0;
        $productSales = [];

        foreach ($transactions as $trx) {
            foreach ($trx->products as $product) {
                $totalItemsSold += $product->pivot->quantity;
                $productSales[$product->id]['name'] = $product->name;
                $productSales[$product->id]['quantity'] = ($productSales[$product->id]['quantity'] ?? 0) + $product->pivot->quantity;
                $productSales[$product->id]['total'] = ($productSales[$product->id]['total'] ?? 0) + ($product->pivot->price * $product->pivot->quantity);
            }
        }

        $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        $products = Product::all();
        $categories = Category::all();

        return view('admin.reports.sales.index', compact(
            'transactions', 'productSales', 'products', 'categories',
            'totalTransactions', 'totalRevenue', 'avgTransaction', 'totalItemsSold',
            'startDate', 'endDate'
        ));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new SalesReportExport($request), 'laporan-penjualan.xlsx');
    }
}
