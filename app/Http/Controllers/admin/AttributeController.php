<?php

namespace App\Http\Controllers\admin;

use App\Attribute;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AttributeController extends Controller
{

//    public function __construct()
//    {
//        $this->middleware('permission: attribute-list|attribute-create|attribute-edit|attribute-delete', ['only' => ['index','store']]);
//        $this->middleware('permission: attribute-create', ['only' => ['create','store']]);
//        $this->middleware('permission: attribute-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission: attribute-delete', ['only' => ['destroy']]);
//    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.attributes.index');
    }


    public function getData()
    {
        $attribute = Attribute::latest()->get();

        return DataTables::of($attribute)
            ->addIndexColumn()
            ->editColumn('action', function ($attribute) {
                $return = "<div class=\"btn-group\">";
                if (!empty($attribute->id))
                {
                    $return .= "
                        <a href=\"/attribute/edit/$attribute->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                        ||
                            <a rel=\"$attribute->id\" rel1=\"attribute/destroy\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();
            try{

                $attribute = new Attribute();

                $attribute->name = $request->name;

                $attribute->save();

                DB::commit();

                return response()->json([
                    'message' => 'Attribute store successful'
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('admin.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->_method == 'PUT')
        {
            DB::beginTransaction();

            try{

                $attribute = Attribute::findOrFail($id);

                $attribute->name = $request->name;

                $attribute->save();

                DB::commit();

                return response()->json([
                    'message' => 'Attribute updated successful'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return response()->json([
            'message' => 'Attribute destroy successful'
        ],Response::HTTP_OK);
    }
}
