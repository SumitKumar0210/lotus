<?php

namespace App\Http\Controllers\Admin\Modules\Products;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function getCategoryList(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item editProduct" href="javascript:void(0)"><i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Delete </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action'])
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
        return view('backend.admin.modules.products.categories');
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
                'category_name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $category = new Category();
            $category->category_name = $request->category_name;
            if ($category->save()) {
                $response = response()->json(['success' => 'Category added successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding Category, please try again'], 200);
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
        $category = Category::find($id);
        if (!empty($category)) {
            return response()->json($category);
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
                'category_name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $category = Category::where('id', $id)->update(
                [
                    'category_name' => $request->category_name,
                ]
            );
            if ($category) {
                $response = response()->json(['success' => 'Category updated successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in updating Category, please try again'], 200);
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
        $category = Category::find($id);
        if (!empty($category)) {
            $product = Product::where('category_id', $id)->get();
            if (count($product) > 0) {
                return response()->json(['errors' => 'Error in deleting category,  used in products']);

            } else {
                Category::find($id)->delete();
                return response()->json(['success' => 'Category deleted successfully']);
            }
        }
    }

}
