<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Products;
use App\User;
use Carbon\Carbon;
use App\ProductStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
//        $products = Products::count();
//        $users = User::count();
//        $categories = Category::count();
        $seller_id = Auth::user()->id;
//        dd($seller_id);


        $products = DB::table('sell_product_details')
            ->select('sell_product_details.*',
                'products.name as product_name',
                'sell_details.*',
                'product_calculations.product_quantity_out as distribution_quantity'
            )
            ->selectRaw('sum(sell_product_details.sub_total) as product_sub_total, sell_product_details.product_id')
            ->selectRaw('sum(sell_product_details.quantity) as product_quantity, sell_product_details.product_id')
            ->leftJoin('products', function ($join) {
                $join->on('sell_product_details.product_id', '=', 'products.id');
            })
            ->leftJoin('product_calculations', function ($join) {
                $join->on('sell_product_details.product_distribution_id', '=', 'product_calculations.product_distribution_id');
            })
            ->leftJoin('product_distributions', function ($join) {
                $join->on('sell_product_details.product_distribution_id', '=', 'product_distributions.id');
            })
            ->leftJoin('sell_details', function ($join) {
                $join->on('sell_product_details.sell_detail_id', '=', 'sell_details.id');
            })
            ->where('sell_details.seller_id', $seller_id)
            ->whereDate('sell_product_details.created_at', Carbon::today())
            ->groupBy('products.name')
            ->orderBy('sell_product_details.sub_total', 'desc')
            ->get();

        $todaySaleTotalAmount = DB::table('sell_details')
            ->whereDate('created_at', Carbon::today())
            ->where('sell_details.seller_id', $seller_id)

            ->select(
                DB::raw("SUM(total_bill) as total_bill"),
                DB::raw("SUM(total_vat_sc_oh_amount) as total_vat_sc_oh_amount"),
                DB::raw("SUM(in_total_bill) as in_total_bill")
            )
            ->get();
        $todaySaleTotalAmount = $todaySaleTotalAmount[0];

        return view('admin.dashboard', compact('products','todaySaleTotalAmount'));
    }

    public function sellerDashboard()
    {
        $seller_id = Auth::user()->id;

        $products = DB::table('sell_product_details')
            ->select('sell_product_details.*',
                'products.name as product_name',
                'sell_details.*',
                'product_calculations.product_quantity_out as distribution_quantity'
            )
            ->selectRaw('sum(sell_product_details.sub_total) as product_sub_total, sell_product_details.product_id')
            ->selectRaw('sum(sell_product_details.quantity) as product_quantity, sell_product_details.product_id')
            ->leftJoin('products', function ($join) {
                $join->on('sell_product_details.product_id', '=', 'products.id');
            })
            ->leftJoin('product_calculations', function ($join) {
                $join->on('sell_product_details.product_distribution_id', '=', 'product_calculations.product_distribution_id');
            })
            ->leftJoin('product_distributions', function ($join) {
                $join->on('sell_product_details.product_distribution_id', '=', 'product_distributions.id');
            })
            ->leftJoin('sell_details', function ($join) {
                $join->on('sell_product_details.sell_detail_id', '=', 'sell_details.id');
            })
            ->where('sell_details.seller_id', $seller_id)
            ->whereDate('sell_product_details.created_at', Carbon::today())
            ->groupBy('products.name')
            ->orderBy('sell_product_details.sub_total', 'desc')
            ->get();

        $todaySaleTotalAmount = DB::table('sell_details')
            ->where('seller_id', $seller_id)
            ->whereDate('created_at', Carbon::today())
            ->select(
                DB::raw("SUM(total_bill) as total_bill"),
                DB::raw("SUM(total_vat_sc_oh_amount) as total_vat_sc_oh_amount"),
                DB::raw("SUM(in_total_bill) as in_total_bill")
            )
            ->get();
        $todaySaleTotalAmount = $todaySaleTotalAmount[0];

        return view('seller.dashboard', compact('products','todaySaleTotalAmount'));
    }
}
