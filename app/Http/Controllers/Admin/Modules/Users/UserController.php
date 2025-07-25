<?php

namespace App\Http\Controllers\Admin\Modules\Users;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function getUserList(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with([
                    'branch:id,branch_name'
                ])
                ->where('id', '!=', auth()->id());

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('branch_id', function ($row) {
                    return optional($row->branch)->branch_name ?? '';
                })
                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item editProduct" href="javascript:void(0)">
                                    <i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)">
                                    <i class="far fa-trash-alt text-danger"></i> Delete </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action'])
                ->filterColumn('branch_id', function ($query, $keyword) {
                    $query->whereHas('branch', fn($q) => $q->where('branch_name', 'like', "%$keyword%"));
                })
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
        $branches = Branch::where('type', null)->latest()->get();
        return view('backend.admin.modules.users.users', compact('branches'));
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
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'type' => ['required', 'string', 'max:255'],
                'password' => ['required', 'confirmed', 'min:6'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            if ($request->type == 'WAREHOUSE') {
                $warehouse = Branch::where('type', 'WAREHOUSE')->first();
                $branch_id = $warehouse->id;
            } else if ($request->type == 'BRANCH') {
                $branch_id = $request->branch_id;
            } else {
                $branch_id = null;
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->type = $request->type;
            $user->branch_id = $branch_id;

            if ($user->save()) {

               $branch = Branch::find($branch_id);
               if($branch->purchase_permission == 'yes'){
                   //$permissions = Permission::get();
                  // $user->syncPermissions($permissions);
                   $user->assignRole('purchase role');
               }
                $response = response()->json(['success' => 'User added successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding User, please try again'], 200);
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
        $user = User::find($id);
        if (!empty($user)) {
            return response()->json($user);
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
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required', 'email', 'max:255',
                    Rule::unique('users')->ignore($id),
                ],
                'password' => ['nullable', 'confirmed', 'min:6'],

            ]);


            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $user = User::where('id', $id)->update(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]
            );
            if ($user) {
                $response = response()->json(['success' => 'User updated successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in updating User, please try again'], 200);
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
        $user = User::find($id);
        if (!empty($user)) {
            $stock = Stock::where('branch_user_in', $id)->get();
            if (count($stock) > 0) {
                return response()->json(['errors' => 'Error in deleting user, used in stocks']);
            } else {
                $user->delete();
                return response()->json(['success' => 'User deleted successfully']);
            }
        }
    }
}
