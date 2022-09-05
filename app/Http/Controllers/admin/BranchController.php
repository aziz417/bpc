<?php

namespace App\Http\Controllers\admin;

use App\Branch;
use App\BranchType;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('permission: branch-list|branch-create|branch-edit|branch-delete', ['only' => ['index','store']]);
//        $this->middleware('permission: branch-create', ['only' => ['create','store']]);
//        $this->middleware('permission: branch-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission: branch-delete', ['only' => ['destroy']]);
//    }

    public function index()
    {
        return view('admin.branch.index');
    }

    public function getData()
    {
        $branch = Branch::select('branches.*','branch_types.branch_type_name as branch_type_name')
                    ->leftJoin('branch_types', function($join){
                        $join->on('branches.branch_type_id','=','branch_types.id');
                    })
                    ->orderBy('branches.id','desc')
                    ->get();

        return DataTables::of($branch)
        ->addIndexColumn()
        ->addColumn('approve',function ($branch){
            if($branch->approve == 0)
            {

                return '<div>
                        <label class="switch patch">
                            <input type="checkbox" class="status_toggle" data-value="'.$branch->id.'" id="status_change" value="'.$branch->id.'">
                            <span class="slider"></span>
                        </label>
                        </div>';
            }else{
                return '<div>
                    <label class="switch patch">
                        <input type="checkbox" id="status_change"  class="status_toggle" data-value="'.$branch->id.'"  value="'.$branch->id.'" checked>
                        <span class="slider"></span>
                    </label>
                    </div>';
            }

        })
        ->editColumn('action', function ($branch) {
            $return = "<div class=\"btn-group\">";
            if (!empty($branch->id))
            {
                $return .= "
                        <a href=\"/branch/edit/$branch->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                        ||
                            <a rel=\"$branch->id\" rel1=\"branch/destroy\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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

    public function create()
    {
        $branch_type = BranchType::latest()->get();

        return view('admin.branch.create', compact('branch_type'));
    }

    public function approve($id)
    {
        $branch = Branch::findOrFail($id);

        if($branch->approve == 0)
        {
            $branch->update(['approve' => 1]);

            return response()->json([
                'message' => 'Branch is active'
            ],Response::HTTP_OK);
        }else{
            $branch->update(['approve' => 0]);

            return response()->json([
                'message' => 'Branch is Inactive'
            ],Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        if($request->isMethod('post'))
        {
            DB::beginTransaction();

            try{

                $branch = new Branch();

                $branch->branch_type_id = $request->branch_type_id;
                $branch->name = $request->name;
                $branch->address = $request->address;

                $branch->save();

                DB::commit();

                return response()->json([
                    'message' => 'Branch store successful'
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
        $branch_type = BranchType::latest()->get();
        $branch = Branch::findOrFail($id);

        return view('admin.branch.edit', compact('branch_type','branch'));
    }

    public function update(Request $request, $id)
    {
        if($request->_method == 'PUT')
        {
            DB::beginTransaction();

            try{

                $branch = Branch::findOrFail($id);

                $branch->branch_type_id = $request->branch_type_id;
                $branch->name = $request->name;
                $branch->address = $request->address;

                $branch->save();

                DB::commit();

                return response()->json([
                    'message' => 'Branch updated successful'
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
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json([
            'message' => 'Branch destroy successful'
        ],Response::HTTP_OK);
    }
}
