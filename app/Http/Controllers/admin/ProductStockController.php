<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\ProductStock;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\True_;
use Yajra\DataTables\Facades\DataTables;

class ProductStockController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('permission: productStock-list|productStock-create|productStock-edit|productStock-delete', ['only' => ['index','store']]);
//        $this->middleware('permission: productStock-create', ['only' => ['create','store']]);
//        $this->middleware('permission: productStock-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission: productStock-delete', ['only' => ['destroy']]);
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products')->select('id', 'name')->latest()->get();
        $attributes = DB::table('attributes')->select('id', 'name')->latest()->get();

        // $stocks = ProductStock::all();
        // foreach($stocks as $stock){
        //     $stock->update([
        //         'quantity' => 100000,
        //         ]);
        // }
        // dd($stocks);

        $products = $products->filter(function ($product) {
            $stockExist = ProductStock::where('product_id', $product->id)->first();
            if (!$stockExist) {
                return $product;
            }
        });
        return view('admin.stock.index', compact('products', 'attributes'));
    }


    public function getData()
    {

        $stock = ProductStock::select(
            'product_stocks.*',
            'products.name as product_name',
            'attributes.name as attribute_name'
        )
            ->leftJoin('products', function ($join) {
                $join->on('product_stocks.product_id', '=', 'products.id');
            })
            ->leftJoin('attributes', function ($join) {
                $join->on('product_stocks.attribute_id', '=', 'attributes.id');
            })
            ->orderBy('product_stocks.id', 'desc')
            ->get();

        return DataTables::of($stock)
            ->addIndexColumn()
            ->editColumn('action', function ($stock) {
                $return = "<div class=\"btn-group\">";
//                if (!empty($stock->id))
//                {
//                    $return .= "
//                        <a href=\"/stock/edit/$stock->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
//                        ||
//                          <a rel=\"$stock->id\" rel1=\"stock/destroy\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
//                                ";
//                }
                if (!empty($stock->id)) {
                    $return .= "
                        <a href=\"/stock/edit/$stock->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
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


                foreach ($request->product_id as $key => $product) {
                    // if already exist then update
                    $stock = ProductStock::where(['product_id' => $product, 'attribute_id' => $request->attribute_id[$key]])->first();
                    if ($stock) {
                        $stock->update([
                            'quantity' => $request->quantity[$key],
                        ]);
                    } else {
                        ProductStock::create([
                            'product_id' => $product,
                            'attribute_id' => $request->attribute_id[$key],
                            'quantity' => $request->quantity[$key],
                        ]);
                    }
                }


                DB::commit();

                return response()->json([
                    'message' => 'Stock store successful'
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
        $products = DB::table('products')->select('id', 'name')->latest()->get();
        $attributes = DB::table('attributes')->select('id', 'name')->latest()->get();
        $stock = ProductStock::findOrFail($id);
        return view('admin.stock.edit', compact('stock', 'products', 'attributes'));
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

                $stock = ProductStock::findOrFail($id);

                $stock->product_id = $request->product_id;
                $stock->attribute_id = $request->attribute_id;
                $stock->quantity = $request->quantity;

                $stock->save();

                DB::commit();

                return response()->json([
                    'message' => 'Stock updated successful'
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = ProductStock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'message' => 'Stock destroy successful'
        ], Response::HTTP_OK);
    }

    public function checkStocked(Request $request)
    {
        $product_id = $request->product_id;
        $attribute_id = $request->attribute_id;
        if (ProductStock::where(['product_id' => $product_id, 'attribute_id' => $attribute_id])->first()) {
            return true;
        } else {
            return false;
        }
    }
}
