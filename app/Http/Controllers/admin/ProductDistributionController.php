<?php

namespace App\Http\Controllers\admin;

use App\Branch;
use App\BranchType;
use App\Http\Controllers\Controller;
use App\ProductDistribution;
use App\Products;
use App\ProductStock;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductDistributionController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('permission: productDistribution-list|productDistribution-create|productDistribution-edit|productDistribution-delete', ['only' => ['index','store']]);
//        $this->middleware('permission: productDistribution-create', ['only' => ['create','store']]);
//        $this->middleware('permission: productDistribution-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission: productDistribution-delete', ['only' => ['destroy']]);
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branch_type_names = BranchType::where('approve', 1)->select('id', 'branch_type_name')->latest()->get();

        // $ProductDistributions = ProductDistribution::first();
        // dd($ProductDistributions);
        // foreach($ProductDistributions as $ProductDistribution){
        //     $ProductDistribution->update([
        //         'quantity' => 10000,
        //         ]);
        // }
        // dd($ProductDistributions);

        return view('admin.product_distribution.index', compact(
            'branch_type_names'
        ));
    }


    public function getData()
    {
        $product_distribution = DB::table('product_distributions')->select(
            'product_distributions.id as id',
            'product_distributions.quantity',
            'product_distributions.date',
            'products.name as product_name',
            'users.name as seller_name',
            'branch_types.branch_type_name as branch_type_name',
            'branches.name as branch_name',
            DB::raw("CONCAT(attributes.name, '-', product_stocks.quantity) AS attribute_name_with_quantity")
        )
            ->leftJoin('products', function ($join) {
                $join->on('product_distributions.product_id', '=', 'products.id');
            })
            ->leftJoin('users', function ($join) {
                $join->on('product_distributions.seller_id', '=', 'users.id');
            })
            ->leftJoin('branch_types', function ($join) {
                $join->on('product_distributions.branch_type_id', '=', 'branch_types.id');
            })
            ->leftJoin('branches', function ($join) {
                $join->on('product_distributions.branch_id', '=', 'branches.id');
            })
            ->leftJoin('product_stocks', function ($join) {
                $join->on('product_stocks.id', '=', 'product_distributions.stock_id');
            })
            ->leftJoin('attributes', function ($join) {
                $join->on('attributes.id', '=', 'product_stocks.attribute_id');
            })
            ->orderBy('product_distributions.id', 'desc')
            ->get();

        return DataTables::of($product_distribution)
            ->addIndexColumn()
            ->editColumn('action', function ($product_distribution) {
                $return = "<div class=\"btn-group\">";
                if (!empty($product_distribution->id)) {
                    $return .= "
                        <a href=\"/product_distribution/edit/$product_distribution->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                        ||
                            <a rel=\"$product_distribution->id\" rel1=\"product_distribution/destroy\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {

                foreach ($request->seller_id as $key => $seller) {

                    $product_id = $request->product_id[$key];
                    $stock_id = $request->stock_id[$key];
                    $branch_type_id = $request->branch_type_id[$key];
                    $branch_id = $request->branch_id[$key];
                    $quantity = $request->quantity[$key];
                    $date = $request->date[$key];

                    // quantity update
                    $stock = ProductStock::where('id', $stock_id)->first();
                    $previous_quantity = $stock->quantity;
                    $distribution_quantity = $previous_quantity - $quantity;
                    $stock->update([
                        'quantity' => $distribution_quantity,
                    ]);


                    $distribution = ProductDistribution::where(
                        [
                            'seller_id' => $seller,
                            'product_id' => $product_id,
                            'stock_id' => $stock_id,
                            'branch_type_id' => $branch_type_id,
                            'branch_id' => $branch_id,
                        ])->first();

                    if ($distribution) {
                        $distribution->update([
                            'quantity' => $quantity,
                            'date' => $date
                        ]);

                        $flag = 'update';

                        $this->productDistributionDetailsAndCalculation(
                            $flag,
                            $distribution->id,
                            $product_id,
                            $stock_id,
                            $quantity
                        );
                    } else {

                        $product_distribution = ProductDistribution::create([
                            'seller_id' => $seller,
                            'product_id' => $product_id,
                            'stock_id' => $stock_id,
                            'branch_type_id' => $branch_type_id,
                            'branch_id' => $branch_id,
                            'quantity' => $quantity,
                            'date' => $date,
                        ]);

                        $flag = 'create';

                        $this->productDistributionDetailsAndCalculation(
                            $flag,
                            $product_distribution->id,
                            $product_id,
                            $stock_id,
                            $quantity
                        );
                    }
                }

                DB::commit();

                return response()->json([
                    'message' => 'Product distribution store successful'
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_distribution = DB::table('product_distributions')->select(
            'product_distributions.id as product_distribution_id',
            'product_distributions.quantity',
            'product_distributions.date',
            'products.id as product_id',
            'users.id as seller_id',
            'branch_types.id as branch_type_id',
            'branches.id as branch_id',
            'product_stocks.id as stock_id',
            DB::raw("CONCAT(attributes.name, '-', product_stocks.quantity) AS attribute_name_with_quantity")

        )
            ->leftJoin('products', function ($join) {
                $join->on('product_distributions.product_id', '=', 'products.id');
            })
            ->leftJoin('users', function ($join) {
                $join->on('product_distributions.seller_id', '=', 'users.id');
            })
            ->leftJoin('branch_types', function ($join) {
                $join->on('product_distributions.branch_type_id', '=', 'branch_types.id');
            })
            ->leftJoin('branches', function ($join) {
                $join->on('product_distributions.branch_id', '=', 'branches.id');
            })
            ->leftJoin('product_stocks', function ($join) {
                $join->on('product_stocks.id', '=', 'product_distributions.stock_id');
            })
            ->leftJoin('attributes', function ($join) {
                $join->on('attributes.id', '=', 'product_stocks.attribute_id');
            })
            ->where('product_distributions.id', $id)
            ->first();

        $products = DB::table('products')->select('id', 'name')->latest()->get();
        $sellers = DB::table('users')
            ->where('roles.name', 'seller')
            ->where('users.status', 1)
            ->where('branch_id', $product_distribution->branch_id)
            ->select(
                'users.id as id',
                'users.name as name',
                'roles.name as role_name'
            )
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('users.id', '!=', Auth::id())
            ->orderBy('users.id', 'desc')
            ->get();

        $branch_type_names = BranchType::where('approve', 1)->select('id', 'branch_type_name')->latest()->get();

        $branches = Branch::where('branch_type_id', $product_distribution->branch_type_id)->get();
        $stocks = DB::table('product_stocks')
            ->leftJoin('attributes', 'product_stocks.attribute_id', '=', 'attributes.id')
            ->select(
                'product_stocks.id as stock_id',
                'product_stocks.quantity',
                'attributes.name as attribute_name'
            )
            ->where('product_id', $product_distribution->product_id)
            ->groupBy('attribute_id')
            ->get();

        return view('admin.product_distribution.edit', compact(
            'product_distribution',
            'products',
            'sellers',
            'branch_type_names',
            'branches',
            'stocks'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->_method == 'PUT') {

            DB::beginTransaction();
            try {

                $product_distribution = ProductDistribution::findOrFail($id);

                $seller_id = $request->seller_id;
                $product_id = $request->product_id;
                $stock_id = $request->stock_id;
                $branch_type_id = $request->branch_type_id;
                $branch_id = $request->branch_id;
                $quantity = $request->quantity;
                $date = $request->date;

                $product_distribution->update([
                    'seller_id' => $seller_id,
                    'product_id' => $product_id,
                    'stock_id' => $stock_id,
                    'branch_type_id' => $branch_type_id,
                    'branch_id' => $branch_id,
                    'quantity' => $quantity,
                    'date' => $date
                ]);

                $flag = 'update';

                $this->productDistributionDetailsAndCalculation(
                    $flag,
                    $id,
                    $product_id,
                    $stock_id,
                    $quantity
                );

                DB::commit();

                return response()->json([
                    'message' => 'Product Distribution updated successful'
                ], Response::HTTP_OK);

            } catch (QueryException $e) {
                DB::rollBack();
                $error = $e->getMessage();
                return \response()->json([
                    'error' => $error
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product_distribution = ProductDistribution::findOrFail($id);

        $product_distribution->delete();

        DB::table('product_distribution_details')
            ->where('product_distribution_id', $id)->delete();

        DB::table('product_calculations')
            ->where('product_distribution_id', $id)->delete();

        return response()->json([
            'message' => 'Product Distribution destroy successful'
        ], Response::HTTP_OK);
    }

    // get branch type wise branch
    public function branchTypeBranches(Request $request)
    {
        $id = $request->id;
        $branches = DB::table('branches')
            ->where('branch_type_id', $id)
            ->where('approve', 1)
            ->select('id', 'name')
            ->get();

        return $branches;
    }

    //get product attribute wise stock
    public function productAttributeStock(Request $request)
    {

        $id = $request->id;
        $stocks = DB::table('product_stocks')
            ->leftJoin('attributes', 'product_stocks.attribute_id', '=', 'attributes.id')
            ->select(
                'product_stocks.id as stock_id',
                'product_stocks.quantity',
                'attributes.name as attribute_name'
            )
            ->where('product_id', $id)
            ->groupBy('attribute_id')
            ->get();

        return $stocks;
    }

    //get product attribute wise stock
    public function branchSeller(Request $request)
    {
        $id = $request->id;
        $sellers = DB::table('users')
            ->where('roles.name', 'seller')
            ->where('users.status', 1)
            ->where('users.branch_id', $id)
            ->select(
                'users.id as id',
                'users.name as name',
                'roles.name as role_name'
            )
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '!=', 'supper-admin')
            ->where('roles.name', '!=', 'admin')
            ->orderBy('users.id', 'desc')
            ->get();

        return $sellers;
    }

    public function branchSellerReport(Request $request)
    {
        $id = $request->id;
        $sellers = DB::table('users')
            ->where('roles.name', 'seller')
            ->where('users.status', 1)
            ->where('users.branch_id', $id)
            ->select(
                'users.id as id',
                'users.name as name',
                'roles.name as role_name'
            )
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '!=', 'supper-admin')
            ->where('roles.name', '!=', 'admin')
            ->orderBy('users.id', 'desc')
            ->get();

        $userRole = Auth::user()->roles[0]->name;

        return ['sellers' => $sellers, 'userRole' => $userRole];
    }

    //get product attribute wise stock
    public function sellerProducts(Request $request)
    {
        $id = $request->id;
        $category_id = User::where('id', $id)->first()->category_id;

        $products = DB::table('products')
            ->leftJoin('product_stocks', function ($join) {
                $join->on('products.id', '=', 'product_stocks.product_id');
            })
            ->where('product_stocks.quantity', '>', 0)
            ->where('products.category_id', $category_id)
            ->select('products.id', 'products.name')
            ->orderBy('product_stocks.quantity', 'desc')
            ->groupBy('product_stocks.product_id')
            ->get();

        return $products;
    }

    //get product attribute wise stock
    public function getProductStock(Request $request)
    {
        $id = $request->id;
        $stocks = DB::table('product_stocks')
            ->where('id', $id)
            ->first()->quantity;

        return $stocks;
    }

    public function productDistributionDetailsAndCalculation(
        $flag,
        $productDistributionId,
        $productId,
        $stock_id,
        $quantity
    )
    {

        $product = Products::select(
            'products.*',
            'users.name as user_name',
            'categories.name as category_name'
        )
            ->leftJoin('users', function ($join) {
                $join->on('products.user_id', '=', 'users.id');
            })
            ->leftJoin('categories', function ($join) {
                $join->on('products.category_id', '=', 'categories.id');
            })
            ->where('products.id', $productId)
            ->first();

        $stock_quantity = DB::table('product_stocks')
            ->where('id', $stock_id)
            ->select('quantity')
            ->first()->quantity;

        $left_quantity = (int)$stock_quantity - (int)$quantity;

        if ($flag === 'create') {
            DB::table('product_distribution_details')->insert([
                'product_distribution_id' => $productDistributionId,
                'user_name' => Auth::user()->name,
                'category' => $product->category_name,
                'product_name' => $product->name,
                'brand' => $product->brand,
                'image' => $product->image,
                'unit_price' => $product->unit_price
            ]);

            DB::table('product_calculations')->insert([
                'product_distribution_id' => $productDistributionId,
                'product_id' => $productId,
                'product_quantity' => $stock_quantity,
                'product_quantity_out' => $quantity,
                'product_left_quantity' => $left_quantity
            ]);

        } elseif ($flag === 'update') {

            DB::table('product_distribution_details')
                ->where('product_distribution_id', $productDistributionId)
                ->update(array(
                    'user_name' => Auth::user()->name,
                    'category' => $product->category_name,
                    'product_name' => $product->name,
                    'brand' => $product->brand,
                    'image' => $product->image,
                    'unit_price' => $product->unit_price
                ));

            DB::table('product_calculations')
                ->where('product_distribution_id', $productDistributionId)
                ->update(array(
                    'product_id' => $productId,
                    'product_quantity' => $stock_quantity,
                    'product_quantity_out' => $quantity,
                    'product_left_quantity' => $left_quantity
                ));
        }

        return true;
    }
}
