<?php

namespace App\Http\Controllers\admin;

use App\BranchType;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BranchTypeController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('permission: branchType-list|branchType-create|branchType-edit|branchType-delete', ['only' => ['index','store']]);
//        $this->middleware('permission: branchType-create', ['only' => ['create','store']]);
//        $this->middleware('permission: branchType-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission: branchType-delete', ['only' => ['destroy']]);
//    }

    public function index()
    {
        return view('admin.branch_type.index');
    }

    public function getData()
    {
        $branch_type = BranchType::latest()->get();

        return DataTables::of($branch_type)
        ->addIndexColumn()
        ->addColumn('approve',function ($branch_type){
            if($branch_type->approve == 0)
            {

                return '<div>
                        <label class="switch patch">
                            <input type="checkbox" class="status_toggle" data-value="'.$branch_type->id.'" id="status_change" value="'.$branch_type->id.'">
                            <span class="slider"></span>
                        </label>
                      </div>';
            }else{
                return '<div>
                    <label class="switch patch">
                        <input type="checkbox" id="status_change"  class="status_toggle" data-value="'.$branch_type->id.'"  value="'.$branch_type->id.'" checked>
                        <span class="slider"></span>
                    </label>
                  </div>';
            }

        })
        ->editColumn('action', function ($branch_type) {
            $return = "<div class=\"btn-group\">";
            if (!empty($branch_type->id))
            {
                $return .= "
                        <a href=\"/branch_type/edit/$branch_type->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                        ||
                            <a rel=\"$branch_type->id\" rel1=\"branch_type/destroy\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                                ";
            }
            $return .= "</div>";
            return $return;
        })
        ->rawColumns([
            'action','approve'
        ])
        ->make(true);
    }

    public function statusChange($id)
    {
        $branch_type = BranchType::findOrFail($id);

        if($branch_type->approve == 0)
        {
            $branch_type->update(['approve' => 1]);

            return response()->json([
                'message' => 'Branch is active'
            ],Response::HTTP_OK);
        }else{
            $branch_type->update(['approve' => 0]);

            return response()->json([
                'message' => 'Branch is Inactive'
            ],Response::HTTP_OK);
        }
    }

    public function create()
    {
        return view('admin.branch_type.create');
    }

    public function store(Request $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();

            try {
                //code...
                $branch_type = new BranchType();

                $branch_type->branch_type_name = $request->branch_type_name;

                $branch_type->save();

                DB::commit();

                return response()->json([
                    'message' => 'Branch type store successful'
                ],Response::HTTP_CREATED);

            } catch (QueryException $e) {
                //throw $e;

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
        $branch_type = BranchType::findOrFail($id);

        return view('admin.branch_type.edit', compact('branch_type'));
    }

    public function update(Request $request, $id)
    {
        if($request->_method == 'PUT')
        {
            DB::beginTransaction();

            try{

                $branch_type = BranchType::findOrFail($id);

                $branch_type->branch_type_name = $request->branch_type_name;

                $branch_type->save();

                DB::commit();

                return response()->json([
                    'message' => 'Branch type updated successful'
                ],Response::HTTP_OK);

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
        $branch_type = BranchType::findOrFail($id);
        $branch_type->delete();

        return response()->json([
            'message' => 'Branch type destroy successful'
        ],Response::HTTP_OK);
    }
}
