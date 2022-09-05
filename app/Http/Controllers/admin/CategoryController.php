<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('permission: category-list|category-create|category-edit|category-delete', ['only' => ['index','store']]);
//        $this->middleware('permission: category-create', ['only' => ['create','store']]);
//        $this->middleware('permission: category-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission: category-delete', ['only' => ['destroy']]);
//    }

    public function index()
    {
        return view('admin.category.index');
    }

    public function getData()
    {
        $category = Category::latest()->get();

        return DataTables::of($category)
        ->addIndexColumn()
        ->addColumn('status',function ($category){
            if($category->status == 0)
            {

                return '<div>
                        <label class="switch patch">
                            <input type="checkbox" class="status_toggle" data-value="'.$category->id.'" id="status_change" value="'.$category->id.'">
                            <span class="slider"></span>
                        </label>
                        </div>';
            }else{
                return '<div>
                    <label class="switch patch">
                        <input type="checkbox" id="status_change"  class="status_toggle" data-value="'.$category->id.'"  value="'.$category->id.'" checked>
                        <span class="slider"></span>
                    </label>
                    </div>';
            }

        })
        ->editColumn('action', function ($category) {
            $return = "<div class=\"btn-group\">";
            if (!empty($category->id))
            {
                $return .= "
                        <a href=\"/category/edit/$category->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                        ||
                            <a rel=\"$category->id\" rel1=\"category/destroy\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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
        $category = Category::findOrFail($id);

        if($category->status == 0)
        {
            $category->update(['status' => 1]);

            return response()->json([
                'message' => 'category is active'
            ],Response::HTTP_OK);
        }else{
            $category->update(['status' => 0]);

            return response()->json([
                'message' => 'category is inactive'
            ],Response::HTTP_OK);
        }
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{

                $category = new Category();

                $category->name = $request->name;

                $category->save();

                DB::commit();

                return response()->json([
                    'message' => 'Category store successful'
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
        $category = Category::findOrFail($id);

        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        if($request->_method == 'PUT')
        {
            DB::beginTransaction();

            try{

                $category = Category::findOrFail($id);

                $category->name = $request->name;

                $category->save();

                DB::commit();

                return response()->json([
                    'message' => 'Category updated successful'
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
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category destroy successful'
        ],Response::HTTP_OK);
    }
}
