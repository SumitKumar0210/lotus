<?php

namespace App\Http\Controllers\Branch\Modules\Products;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BrandBranchController extends Controller
{

    public function getBrandsList(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::get();
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
        return view('backend.branch.modules.products.brands');
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
                'brand_name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $brand = new Brand();
            $brand->brand_name = $request->brand_name;
            if ($brand->save()) {
                $response = response()->json(['success' => 'Brand added successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding Brand, please try again'], 200);
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
        $brand = Brand::find($id);
        if (!empty($brand)) {
            return response()->json($brand);
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
                'brand_name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $brand = Brand::where('id', $id)->update(
                [
                    'brand_name' => $request->brand_name,
                ]
            );
            if ($brand) {
                $response = response()->json(['success' => 'Brand updated successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in updating Brand, please try again'], 200);
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
        $brand = Brand::find($id);
        if (!empty($brand)) {
            $product = Product::where('brand_id', $id)->get();
            if (count($product) > 0) {
                return response()->json(['errors' => 'Error in deleting Brand, used in products']);
            } else {
                Brand::find($id)->delete();
                return response()->json(['success' => 'Brand deleted successfully']);
            }
        }
    }
}
