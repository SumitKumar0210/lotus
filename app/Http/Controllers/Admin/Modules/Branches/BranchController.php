<?php

namespace App\Http\Controllers\Admin\Modules\Branches;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    public function getBranchList(Request $request)
    {
        if ($request->ajax()) {

            $data = Branch::orderBy('id', 'asc');
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    if ($row->type == 'WAREHOUSE') {
                        return '<span class="badge badge-primary">Warehouse Branch</span>';
                    } elseif ($row->type == 'FACTORY') {
                        return '<span class="badge badge-warning">Factory</span>';
                    }
                    return '<span class="badge badge-success">Branch</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item editProduct" href="javascript:void(0)"><i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Delete </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action', 'type'])
                
                ->make(true);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {

        return view('backend.admin.modules.branches.branches');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        //return response()->json($request->all());

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'branch_name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'digits:10', 'unique:branches'],
                'email' => ['required', 'email', 'max:255', 'unique:branches'],
                'print_slug' => ['required', 'string', 'min:3', 'max:3'],
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $branch = new Branch();
            $branch->branch_name = $request->branch_name;
            $branch->address = $request->address;
            $branch->phone = $request->phone;
            $branch->email = $request->email;
            $branch->print_slug = $request->print_slug;
            $branch->purchase_permission = $request->permissions;
            $branch->product_permission = $request->product_permission;

            if ($branch->save()) {

                $response = response()->json(['success' => 'Branch added successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding Branch, please try again'], 200);
            }
            return $response;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $branch = Branch::find($id);
        if (!empty($branch)) {
            return response()->json($branch);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'branch_name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'email' => [
                    'required', 'email', 'max:255',
                    Rule::unique('branches')->ignore($id),
                ],
                'phone' => [
                    'required', 'numeric', 'digits:10',
                    Rule::unique('branches')->ignore($id),
                ],
                'print_slug' => ['required', 'string', 'min:3', 'max:3'],

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $branch = Branch::where('id', $id)->update(
                [
                    'branch_name' => $request->branch_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'print_slug' => $request->print_slug,
                    'purchase_permission' => $request->permissions,
                    'product_permission' => $request->product_permission,
                ]
            );
            if ($branch) {

                if ($request->permissions == 'no') {

                    $users = User::role('purchase role')->get();
                    //$permissions = Permission::get();
                    foreach ($users as $user) {
                        $user->removeRole('purchase role');
                        //$user->revokePermissionTo($permissions);
                    }
                }
                if ($request->permissions == 'yes') {

                    $users = User::where('branch_id', $id)->get();
                    //$permissions = Permission::get();
                    foreach ($users as $user) {
                        //$user->syncPermissions($permissions);
                        $user->assignRole('purchase role');
                    }
                }




                //product permission*************************************
                if ($request->product_permission == 'no') {

                    $users = User::role('product role')->get();
                    foreach ($users as $user) {
                        $user->removeRole('product role');
                    }
                }
                if ($request->product_permission == 'yes') {

                    $users = User::where('branch_id', $id)->get();
                    foreach ($users as $user) {
                        $user->assignRole('product role');
                    }
                }
                //product permission*************************************





                $response = response()->json(['success' => 'Branch updated successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in updating Branch, please try again'], 200);
            }
            return $response;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);
        if (!empty($branch)) {
            $user = \App\Models\User::where('branch_id', $id)->get();
            if (count($user) > 0) {
                return response()->json(['errors' => 'Error in deleting product, used in stocks']);
            } else {
                $branch_type = $branch->type;
                if ($branch_type == 'WAREHOUSE') {
                    return response()->json(['errors' => 'Cannot delete warehouse']);
                } else {
                    $branch->delete();
                    return response()->json(['success' => 'Branch deleted successfully']);
                }
            }
        }
    }
}
