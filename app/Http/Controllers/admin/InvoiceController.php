<?php

namespace App\Http\Controllers\admin;

use App\Branch;
use App\Http\Controllers\Controller;
use App\SellDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function report()
    {

        $branches = Branch::where('approve', 1)->get();
        return view('admin.reports.report', compact('branches'));
    }


    public function allReport(Request $request)
    {
        $type = $request->type;
        $date_type = $request->date_type;
        $today_date = $request->today_date;
        $f_date = $request->f_date;
        $tod_date = $request->to_date;
        $just_branch = $request->just_branch;
        $seller_base_branch = $request->seller_base_branch;
        $seller = $request->seller;

        if ($date_type == 'today_date') {
            $f_date = $today_date;
            $tod_date = $today_date;
        }
        $total_bill = null;
        $total_vat_sc_oh_amount = null;
        $in_total_bill = null;
        $products = null;

        if ($type == 'all') {
            $total_bill = DB::table('sell_details')
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('total_bill');
            $total_vat_sc_oh_amount = DB::table('sell_details')
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('total_vat_sc_oh_amount');
            $in_total_bill = DB::table('sell_details')
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('in_total_bill');


            $products = DB::table('sell_product_details')
                ->leftJoin('sell_details', function ($join) {
                    $join->on('sell_product_details.sell_detail_id', '=', 'sell_details.id');
                })
                ->leftJoin('products', function ($join) {
                    $join->on('sell_product_details.product_id', '=', 'products.id');
                })
                ->leftJoin('product_distributions', function ($join) {
                    $join->on('sell_product_details.product_distribution_id', '=', 'product_distributions.id');
                })
                ->leftJoin('product_stocks', function ($join) {
                    $join->on('product_distributions.stock_id', '=', 'product_stocks.id');
                })
                ->leftJoin('attributes', function ($join) {
                    $join->on('product_stocks.attribute_id', '=', 'attributes.id');
                })
                ->select(
                    'sell_product_details.quantity',
                    'sell_product_details.sub_total',
                    'sell_product_details.admin_sale_product',
                    'products.unit_price',
                    'products.name',
                    'products.id as product_id',
                    'product_stocks.attribute_id',
                    'attributes.name as attribute_name',
                    'products.vat_sc_oh'
                )
                ->whereBetween('sell_details.date', [$f_date, $tod_date])
                ->get();
        }

        if ($type == 'branch') {

            $total_bill = DB::table('sell_details')
                ->where('branch_id', $just_branch)
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('total_bill');

            $total_vat_sc_oh_amount = DB::table('sell_details')
                ->where('branch_id', $just_branch)
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('total_vat_sc_oh_amount');

            $in_total_bill = DB::table('sell_details')
                ->where('branch_id', $just_branch)
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('in_total_bill');

            $products = DB::table('sell_product_details')
                ->leftJoin('sell_details', function ($join) {
                    $join->on('sell_product_details.sell_detail_id', '=', 'sell_details.id');
                })
                ->leftJoin('products', function ($join) {
                    $join->on('sell_product_details.product_id', '=', 'products.id');
                })
                ->leftJoin('product_distributions', function ($join) {
                    $join->on('sell_product_details.product_distribution_id', '=', 'product_distributions.id');
                })
                ->leftJoin('product_stocks', function ($join) {
                    $join->on('product_distributions.stock_id', '=', 'product_stocks.id');
                })
                ->leftJoin('attributes', function ($join) {
                    $join->on('product_stocks.attribute_id', '=', 'attributes.id');
                })
                ->select(
                    'sell_product_details.quantity',
                    'sell_product_details.sub_total',
                    'sell_product_details.admin_sale_product',
                    'products.unit_price',
                    'products.name',
                    'products.id as product_id',
                    'product_stocks.attribute_id',
                    'attributes.name as attribute_name',
                    'products.vat_sc_oh'
                )
                ->where('sell_details.branch_id', $just_branch)
                ->whereBetween('sell_details.date', [$f_date, $tod_date])
                ->get();
        }
        $seller_code = null;

        if ($type == 'seller') {
            $total_bill = DB::table('sell_details')
                ->where('branch_id', $seller_base_branch)
                ->where('seller_id', $seller)
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('total_bill');

            $total_vat_sc_oh_amount = DB::table('sell_details')
                ->where('branch_id', $seller_base_branch)
                ->where('seller_id', $seller)
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('total_vat_sc_oh_amount');

            $in_total_bill = DB::table('sell_details')
                ->where('branch_id', $seller_base_branch)
                ->where('seller_id', $seller)
                ->whereBetween('date', [$f_date, $tod_date])
                ->sum('in_total_bill');

            $products = DB::table('sell_product_details')
                ->leftJoin('sell_details', function ($join) {
                    $join->on('sell_product_details.sell_detail_id', '=', 'sell_details.id');
                })
                ->leftJoin('products', function ($join) {
                    $join->on('sell_product_details.product_id', '=', 'products.id');
                })
                ->leftJoin('product_distributions', function ($join) {
                    $join->on('sell_product_details.product_distribution_id', '=', 'product_distributions.id');
                })
                ->leftJoin('product_stocks', function ($join) {
                    $join->on('product_distributions.stock_id', '=', 'product_stocks.id');
                })
                ->leftJoin('attributes', function ($join) {
                    $join->on('product_stocks.attribute_id', '=', 'attributes.id');
                })
                ->select(
                    'sell_product_details.quantity',
                    'sell_product_details.sub_total',
                    'sell_product_details.admin_sale_product',
                    'products.unit_price',
                    'products.name',
                    'products.id as product_id',
                    'product_stocks.attribute_id',
                    'attributes.name as attribute_name',
                    'products.vat_sc_oh'
                )
                ->where('sell_details.seller_id', $seller)
                ->where('sell_details.branch_id', $seller_base_branch)
                ->whereBetween('sell_details.date', [$f_date, $tod_date])
                ->get();
            $seller_code = User::where('id', $seller)->first()->seller_code;
        }

        if ($type == 'admin') {
            $seller = User::role('admin')->first()->id;

            $total_bill = DB::table('sell_details')
                ->whereBetween('date', [$f_date, $tod_date])
                ->where('seller_type', 'admin')
                ->sum('total_bill');
            $total_vat_sc_oh_amount = DB::table('sell_details')
                ->whereBetween('date', [$f_date, $tod_date])
                ->where('seller_type', 'admin')
                ->sum('total_vat_sc_oh_amount');
            $in_total_bill = DB::table('sell_details')
                ->whereBetween('date', [$f_date, $tod_date])
                ->where('seller_type', 'admin')
                ->sum('in_total_bill');


            $products = DB::table('sell_product_details')
                ->leftJoin('sell_details', function ($join) {
                    $join->on('sell_product_details.sell_detail_id', '=', 'sell_details.id');
                })
                ->leftJoin('products', function ($join) {
                    $join->on('sell_product_details.product_id', '=', 'products.id');
                })
                ->leftJoin('product_distributions', function ($join) {
                    $join->on('sell_product_details.product_distribution_id', '=', 'product_distributions.id');
                })
                ->leftJoin('product_stocks', function ($join) {
                    $join->on('product_distributions.stock_id', '=', 'product_stocks.id');
                })
                ->leftJoin('attributes', function ($join) {
                    $join->on('product_stocks.attribute_id', '=', 'attributes.id');
                })
                ->select(
                    'sell_product_details.quantity',
                    'sell_product_details.sub_total',
                    'sell_product_details.admin_sale_product',
                    'products.unit_price',
                    'products.name',
                    'products.id as product_id',
                    'product_stocks.attribute_id',
                    'attributes.name as attribute_name',
                    'products.vat_sc_oh'
                )
                ->whereBetween('sell_details.date', [$f_date, $tod_date])
                ->where('sell_details.seller_type', 'admin')
                ->where('sell_details.admin_sale_approve', 1)
                ->get();

            $seller_code = User::where('id', $seller)->first()->seller_code;
        }

        $logo = asset('images/logo/bpc-logo.png');
        $project_name = "Bangladesh Parjatan Corporation";
        $vat_reg_no = 18121028032;
        $date = date("d/m/Y");
        date_default_timezone_set("Asia/Dhaka");
        $time = date("h:i:a");


        $company_name = "Skics Engineering and Technologies Ltd.";
        if ($type == 'branch') {
            $branch = Branch::where('id', $just_branch)->first();
        } else {
            $branch = null;
        }


        return view('invoice.all-invoice', compact(
            'products',
            'total_bill',
            'total_vat_sc_oh_amount',
            'in_total_bill',
            'logo',
            'project_name',
            'vat_reg_no',
            'date',
            'time',
            'seller_code',
            'branch',
            'company_name',
            'f_date',
            'type',
            'today_date',
            'date_type',
            'tod_date'
        ));

    }

    public function salePrint(Request $request)
    {
        $sale_details_id = $request->id;
        $products = DB::table('sell_product_details')
            ->leftJoin('products', function ($join) {
                $join->on('sell_product_details.product_id', '=', 'products.id');
            })
            ->leftJoin('product_distributions', function ($join) {
                $join->on('sell_product_details.product_distribution_id', '=', 'product_distributions.id');
            })
            ->leftJoin('product_stocks', function ($join) {
                $join->on('product_distributions.stock_id', '=', 'product_stocks.id');
            })
            ->leftJoin('attributes', function ($join) {
                $join->on('product_stocks.attribute_id', '=', 'attributes.id');
            })
            ->select(
                'sell_product_details.quantity',
                'sell_product_details.sub_total',
                'products.unit_price',
                'products.name',
                'products.id as product_id',
                'product_distributions.stock_id',
                'product_stocks.attribute_id',
                'attributes.name as attribute_name',
                'products.vat_sc_oh'
            )
            ->where('sell_product_details.sell_detail_id', $sale_details_id)
            ->get();

        $sellDetails = SellDetails::where('id', $sale_details_id)->first();

        $logo = asset('images/logo/bpc-logo.png');
        $project_name = "Bangladesh Parjatan Corporation";
        $vat_reg_no = 18121028032;
        $date = date("d/m/Y");
        date_default_timezone_set("Asia/Dhaka");
        $time = date("h:i:a");


        $company_name = "Skics Engineering and Technologies Ltd.";
        $branch = Branch::where('id', $sellDetails->branch_id)->first();


        return view('invoice.sale-invoice', compact(
            'products',
            'sellDetails',
            'logo',
            'project_name',
            'vat_reg_no',
            'date',
            'time',
            'branch',
            'company_name'
        ));
    }

}
