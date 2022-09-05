<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Products;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Image;

class ProductController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('permission: product-list|product-create|product-edit|product-delete', ['only' => ['index','store']]);
//        $this->middleware('permission: product-create', ['only' => ['create','store']]);
//        $this->middleware('permission: product-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission: product-delete', ['only' => ['destroy']]);
//    }

    public function index()
    {
        return view('admin.product.index');
    }

    public function getData()
    {
        $product = Products::select('products.*','users.name as user_name','categories.name as category_name')
                    ->leftJoin('users', function($join){
                        $join->on('products.user_id','=','users.id');
                    })
                    ->leftJoin('categories', function($join){
                        $join->on('products.category_id','=', 'categories.id');
                    })
                    ->orderBy('products.id','desc')
                    ->get();

        return DataTables::of($product)
        ->addIndexColumn()
//        ->addColumn('image',function ($product){
//            $url=asset("assets/admin/uploads/products/small/$product->image");
//            return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
//        })
        ->addColumn('status',function ($product){
            if($product->status == 0)
            {

                return '<div>
                        <label class="switch patch">
                            <input type="checkbox" class="status_toggle" data-value="'.$product->id.'" id="status_change" value="'.$product->id.'">
                            <span class="slider"></span>
                        </label>
                        </div>';
            }else{
                return '<div>
                    <label class="switch patch">
                        <input type="checkbox" id="status_change"  class="status_toggle" data-value="'.$product->id.'"  value="'.$product->id.'" checked>
                        <span class="slider"></span>
                    </label>
                    </div>';
            }

        })
        ->editColumn('action', function ($product) {
            $return = "<div class=\"btn-group\">";
            if (!empty($product->id))
            {
                $return .= "
                        <a href=\"/product/edit/$product->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                        ||
                            <a rel=\"$product->id\" rel1=\"product/destroy\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                                ";
            }
            $return .= "</div>";
            return $return;
        })
        ->rawColumns([
            'action','status'
        ])
        ->make(true);
    }

    public function statusChange($id)
    {
        $product = Products::findOrFail($id);

        if($product->status == 0)
        {
            $product->update(['status' => 1]);

            return response()->json([
                'message' => 'Product is active'
            ],Response::HTTP_OK);
        }else{
            $product->update(['status' => 0]);

            return response()->json([
                'message' => 'Product is inactive'
            ],Response::HTTP_OK);
        }
    }

    public function create()
    {
        $category = Category::latest()->get();

        return view('admin.product.create',compact('category'));
    }

    public function store(Request $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{

                $product = new Products();

                $product->user_id = Auth::id();
                $product->category_id = $request->category_id;
                $product->name = $request->name;
                $product->title = $request->title;
                $product->brand = $request->brand;
                if($request->hasFile('image')){

                    $image_tmp = $request->file('image');
                    if($image_tmp->isValid()){
                        $extenson = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extenson;

                        $original_image_path = public_path().'/assets/admin/uploads/products/original/'.$filename;
                        $large_image_path = public_path().'/assets/admin/uploads/products/large/'.$filename;
                        $medium_image_path = public_path().'/assets/admin/uploads/products/medium/'.$filename;
                        $small_image_path = public_path().'/assets/admin/uploads/products/small/'.$filename;

                        //Resize Image
                        Image::make($image_tmp)->save($original_image_path);
                        Image::make($image_tmp)->resize(1110,680)->save($large_image_path);
                        Image::make($image_tmp)->resize(520,329)->save($medium_image_path);
                        Image::make($image_tmp)->resize(100,75)->save($small_image_path);

                        $product->image = $filename;

                    }
                }

                $product->description = $request->description;
                $product->specification = $request->specification;
                $product->unit_price = $request->unit_price;
                $product->vat_sc_oh = $request->vat_sc_oh;

                if($request->publish == false)
                {
                    $product->publish = 0;
                }else{
                    $product->publish = 1;
                }

                if($request->feature == false)
                {
                    $product->feature = 0;
                }else{
                    $product->feature = 1;
                }

                $product->date = $request->date;

                $product->save();

                DB::commit();

                return response()->json([
                    'message' => 'Product store successful'
                ],Response::HTTP_CREATED);

            }catch(QueryException $e){
                DB::rollBack();

                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function edit($id)
    {
        $category = Category::latest()->get();

        $product = Products::findOrFail($id);

        return view('admin.product.edit', compact('category','product'));
    }

    public function update(Request $request, $id)
    {
        if($request->_method == 'PUT')
        {
            DB::beginTransaction();

            try{
                $product = Products::findOrFail($id);

                $product->user_id = Auth::id();
                $product->category_id = $request->category_id;
                $product->name = $request->name;
                $product->title = $request->title;
                $product->brand = $request->brand;
                $product->unit_price = $request->unit_price;
                $product->vat_sc_oh = $request->vat_sc_oh;

                if($request->hasFile('image')){

                    $image_tmp = $request->file('image');

                    if($product->image == null){

                        $image_name=time().'.'.$image_tmp->getClientOriginalExtension();

                        $original_image_path = public_path().'/assets/admin/uploads/products/original/'.$image_name;
                        $large_image_path = public_path().'/assets/admin/uploads/products/large/'.$image_name;
                        $medium_image_path = public_path().'/assets/admin/uploads/products/medium/'.$image_name;
                        $small_image_path = public_path().'/assets/admin/uploads/products/small/'.$image_name;

                        //Resize Image
                        Image::make($image_tmp)->save($original_image_path);
                        Image::make($image_tmp)->resize(1110,680)->save($large_image_path);
                        Image::make($image_tmp)->resize(520,329)->save($medium_image_path);
                        Image::make($image_tmp)->resize(100,75)->save($small_image_path);

                        $product->image = $image_name;


                    }else{
                        if (file_exists(public_path().'/assets/admin/uploads/products/original/'.$product->image)) {
                            unlink(public_path().'/assets/admin/uploads/products/original/'.$product->image);
                        }
                        if (file_exists(public_path().'/assets/admin/uploads/products/large/'.$product->image)) {
                            unlink(public_path().'/assets/admin/uploads/products/large/'.$product->image);
                        }
                        if (file_exists(public_path().'/assets/admin/uploads/products/medium/'.$product->image)) {
                            unlink(public_path().'/assets/admin/uploads/products/medium/'.$product->image);
                        }
                        if (file_exists(public_path().'/assets/admin/uploads/products/small/'.$product->image)) {
                            unlink(public_path().'/assets/admin/uploads/products/small/'.$product->image);
                        }

                        $image_name=time().'.'.$image_tmp->getClientOriginalExtension();

                        $original_image_path = public_path().'/assets/admin/uploads/products/original/'.$image_name;
                        $large_image_path = public_path().'/assets/admin/uploads/products/large/'.$image_name;
                        $medium_image_path = public_path().'/assets/admin/uploads/products/medium/'.$image_name;
                        $small_image_path = public_path().'/assets/admin/uploads/products/small/'.$image_name;

                        //Resize Image
                        Image::make($image_tmp)->save($original_image_path);
                        Image::make($image_tmp)->resize(1110,680)->save($large_image_path);
                        Image::make($image_tmp)->resize(520,329)->save($medium_image_path);
                        Image::make($image_tmp)->resize(100,75)->save($small_image_path);

                        $product->image = $image_name;
                    }
                }

                if($request->publish == false)
                {
                    $product->publish = 0;
                }else{
                    $product->publish = 1;
                }

                if($request->feature == false)
                {
                    $product->feature = 0;
                }else{
                    $product->feature = 1;
                }

                $product->date = $request->date;

                $product->save();

                DB::commit();

                return response()->json([
                    'message' => 'Product updated successful'
                ],Response::HTTP_CREATED);


            }catch(QueryException $e){
                DB::rollBack();

                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        if($product->image != null)
        {
            $original_image_path = public_path().'/assets/admin/uploads/products/original/'.$product->image;
            $large_image_path = public_path().'/assets/admin/uploads/products/large/'.$product->image;
            $medium_image_path = public_path().'/assets/admin/uploads/products/medium/'.$product->image;
            $small_image_path = public_path().'/assets/admin/uploads/products/small/'.$product->image;

            unlink($original_image_path);
            unlink($large_image_path);
            unlink($medium_image_path);
            unlink($small_image_path);

            $product->delete();
        }else{
            $product->delete();
        }

        return response()->json([
            'message' => 'Product deleted successful'
        ],Response::HTTP_OK);
    }
}
