<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\ProductDistribution;
use App\SellDetails;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SellProductController extends Controller
{

    public function index(){
        return view('seller.today-sale.index');
    }

    public function getData(){
        $seller_id = Auth::user()->id;

        $sales = DB::table('sell_details')
            ->where('seller_id', $seller_id)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->get();

        return DataTables::of($sales)
            ->addIndexColumn()
            ->editColumn('action', function ($sales) {
                $return = "<div class=\"btn-group\">";
                if (!empty($sales->id))
                {
                    $return .= "
                        <a href=\"/seller/sale/show/$sales->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\">This Sale Details</a>
                        ";
                }
                $return .= "</div>";
                return $return;
            })
            ->rawColumns([
                'action'
            ])
            ->make(true);
    }

    public function adminSaleIndex(){
        return view('seller.admin.index');
    }

    public function adminSaleGetData(){
        $seller_id = Auth::user()->id;

        $sales = DB::table('sell_details')
            ->where('seller_type', 'admin')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->get();

        return DataTables::of($sales)
            ->addIndexColumn()
            ->editColumn('action', function ($sales) {
                $return = "<div class=\"btn-group\">";
                if (!empty($sales->id))
                {
                    $return .= "
                        <a href=\"/seller/sale/show/$sales->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\">This Sale Details</a>
                        ";
                }
                $return .= "</div>";
                return $return;
            })
            ->addColumn('update', function ($sales) {
                $roleCheck = Auth::user()->getRoleNames()[0];
                if ($roleCheck === 'admin'){
                    if ($sales->admin_sale_approve === 1){
                        $return = "<div class=\"btn-group\">";
                        if (!empty($sales->id))
                        {
                            $return .= "
                        <span class=\"badge bg-success\">
                        <input id=\"receive_amount\" value=\"$sales->admin_sale_get_amount\" class=\"receive_amount custom_disabled receive_amount_row_$sales->id\" type=\"text\"
                        name=\"receive_amount\"></span>
                        ||
                        <a href=\"javascript:\" onclick='receiveAmountUpdate($sales->id)' style='margin-right: 5px' class=\"btn d-none btn-sm btn-primary\">Update</a>
                        ";
                        }
                        $return .= "</div>";
                        return $return;
                    }else{
                        $return = "<div class=\"btn-group\">";
                        if (!empty($sales->id))
                        {
                            $return .= "
                        <span class=\"badge bg-success\">
                        <input id=\"receive_amount\" value=\"$sales->admin_sale_get_amount\" class=\"receive_amount receive_amount_row_$sales->id\" type=\"text\"
                        name=\"receive_amount\"></span>
                        ||
                        <a href=\"javascript:\" onclick='receiveAmountUpdate($sales->id)' style='margin-right: 5px' class=\"btn btn-sm btn-primary\">Update</a>
                        ";
                        }
                        $return .= "</div>";
                        return $return;
                    }

                }else{

                    $return = "<div class=\"btn-group\">";
                    if ($sales->admin_sale_approve === 1){
                        if (!empty($sales->id))
                        {
                            $return .= "
                        <span class=\"badge bg-success\">
                        <input id=\"receive_amount\" value=\"$sales->admin_sale_get_amount\" class=\"receive_amount custom_disabled\" type=\"text\"
                        name=\"receive_amount\"></span>
                        ||
                        <div>
                        <label class=\"switch patch\">
                            <input type=\"checkbox\" checked class=\"status_toggle\" data-value=\"$sales->id\" id=\"status_change\" value=\"$sales->id\">
                            <span class=\"slider\"></span>
                        </label>
                        </div>
                       ";
                        }
                    }else{
                        if (!empty($sales->id))
                        {
                            $return .= "
                        <span class=\"badge bg-success\">
                        <input id=\"receive_amount\" value=\"$sales->admin_sale_get_amount\" class=\"receive_amount custom_disabled\" type=\"text\"
                        name=\"receive_amount\"></span>
                        ||
                        <div>
                        <label class=\"switch patch\">
                            <input type=\"checkbox\" class=\"status_toggle\" data-value=\"$sales->id\" id=\"status_change\" value=\"$sales->id\">
                            <span class=\"slider\"></span>
                        </label>
                        </div>
                       ";
                        }
                    }

                    $return .= "</div>";
                    return $return;
                }

            })
            ->rawColumns([
                'action', 'update'
            ])
            ->make(true);
    }

    public function adminSaleAmountUpdate(Request $request){

        $saleId = $request->id;
        $amount = $request->amount;

        SellDetails::where(['id' => $saleId, 'seller_type' => 'admin'])->update([
            'admin_sale_get_amount' => $amount,
        ]);

        return response()->json([
            'message' => 'Amount Update'
        ],Response::HTTP_OK);

    }

    public function adminSaleSupperAdminApprove($id)
    {
        $sale = SellDetails::findOrFail($id);
        if ($sale->admin_sale_get_amount > $sale->total_vat_sc_oh_amount){
            $amount = $sale->admin_sale_get_amount - $sale->total_vat_sc_oh_amount;
        }else{
            $amount = $sale->total_vat_sc_oh_amount - $sale->admin_sale_get_amount;
        }

        $products = DB::table('sell_product_details')->where('sell_detail_id', $sale->id)->get();
        $productQuantity = $products->count();
        $perProductGetAmount = $amount/$productQuantity;

        foreach ($products as $product){
            $product = DB::table('sell_product_details')->where('id', $product->id)->update(['sub_total' => $perProductGetAmount]);
        }

        if($sale->admin_sale_approve == 0)
        {
            $sale->update(['admin_sale_approve' => 1]);

            return response()->json([
                'message' => 'Supper Admin Approve'
            ],Response::HTTP_OK);
        }
    }

    public function create()
    {
        $seller_id = Auth::user()->id;

        $admin = DB::table('users')
            ->whereIn('roles.name', ['admin'])
            ->where('users.id', $seller_id)
            ->select(
                'users.id as id',
                'users.name as name',
                'roles.name as role_name'
            )
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->first();


        $query = DB::table('product_distributions')
            ->leftJoin('products', function ($join) {
                $join->on('product_distributions.product_id', '=', 'products.id');
            })
            ->leftJoin('product_stocks', function ($join) {
                $join->on('product_stocks.id', '=', 'product_distributions.stock_id');
            })
            ->leftJoin('attributes', function ($join) {
                $join->on('attributes.id', '=', 'product_stocks.attribute_id');
            })
            ->select(
                'product_distributions.id as product_distribution_id',
                'product_distributions.quantity as stock',
                'product_distributions.branch_id as branch_id',
                'product_distributions.seller_id as seller_id',
                'products.name as product_name',
                'products.id as product_id',
                'products.unit_price as product_price',
                'products.vat_sc_oh',
                'attributes.name as attribute_name'
            );
        if ($admin){
            $products = $query->get();
        }else{
            $products = $query->where('product_distributions.seller_id', $seller_id)
                ->where('product_distributions.quantity', '>', 0)
                ->get();
        }

        return view('seller.today-sale.create', compact('products'));
    }

    public function productSellStore(Request $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                //generate sale code
                $seller_id = Auth::user()->id;
                $seller_code = User::where('id', $seller_id)->first()->seller_code;
                $sale_number = SellDetails::latest()->first()->id;
                $sale_number = $sale_number ?? 1;
                $date = date('Y-m-d');
//                seller code - year month day- sale number
                // MR-75-220531-110
                $sale_code = $seller_code.'-'.date('ymd').'-'.$sale_number;
//                if sale person admin branch all time branch will be add Moruri Restaurant
                $roleCheck = Auth::user()->getRoleNames()[0];
                $admin_sale_product = '';
                if ($roleCheck === 'admin'){
                    $admin_sale_product = 1;
                    $seller_id = Auth::user()->id;
                    $seller_type = $roleCheck;
                    $admin_sale_get_amount = $request->receive_amount ?? 00;
                    $branch_id = 3;
                }else{
                    $seller_id = $request->seller_id;
                    $branch_id = $request->branch_id;
                    $seller_type = $roleCheck;
                    $admin_sale_get_amount = 00;
                }
                //create sell details
                $sellDetails = SellDetails::create([
                    'date' => $date,
                    'branch_id' =>  $branch_id,
                    'seller_id' =>  $seller_id,
                    'seller_type' =>  $seller_type,
                    'admin_sale_get_amount' =>  $admin_sale_get_amount,
                    'total_bill' => $request->total_bill,
                    'sale_code'  => $sale_code,
                    'total_vat_sc_oh_amount' => $request->total_vat_sc_oh_amount,
                    'in_total_bill' => $request->in_total_bill,
                ]);

//                if ($sellDetails){
//                    $this->invoiceMainStructures($sellDetails);
//                }

                foreach ($request->quantity as $key => $quantity) {
                    if (!empty($quantity)) {

                        $distribution_id = $request->product_distribution_id[$key];
                        $this->distributionUpdate($distribution_id, $quantity);
                        DB::table('sell_product_details')->insert([
                            'admin_sale_product' => $admin_sale_product,
                            'product_distribution_id' => $distribution_id,
                            'sell_detail_id' => $sellDetails->id,
                            'product_id' => $request->product_id[$key],
                            'sub_total' => $request->sub_total[$key],
                            'quantity' => $quantity,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }

                DB::commit();

                return response()->json([
                    'message' => 'Sell Successful',
                    'sell_details' => $sellDetails->id

                ], Response::HTTP_CREATED);

            } catch (QueryException $e) {
                DB::rollBack();

                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        }
    }

    public function distributionUpdate($distribution_id, $sell_quantity){
        $product_distribution = ProductDistribution::where('id', $distribution_id)->first();

        $previous_distribution_quantity = $product_distribution->quantity;
        $quantity = $previous_distribution_quantity - $sell_quantity;

        $product_distribution->update([
            'quantity' => $quantity,
        ]);
    }

    public function show($id)
    {

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
            ->where('sell_product_details.sell_detail_id', $id)
            ->get();

        $total_calculation = SellDetails::where('id', $id)->first();

        return view('seller.today-sale.show', compact('products', 'total_calculation'));
    }

//    public function invoiceMainStructures($sellDetails){
//        $bangladesh_date = new DateTime('now', new DateTimezone('Asia/Dhaka'));
//
//        $branch = Branch::where('id', $sellDetails->branch_id)->first();
//        $project_name = "Bangladesh Parjatan Corporation";
//        $branch_name = $branch->name;
//        $branch_address = $branch->address;
//        $vat_reg_no = 18121028032;
//        $date = date("d/m/Y");
//        $time = $bangladesh_date->format('F j, Y, g:i a');
//    }
}
