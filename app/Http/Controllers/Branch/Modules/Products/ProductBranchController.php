<?php

namespace App\Http\Controllers\Branch\Modules\Products;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProductBranchController extends Controller
{
    public function getProductList(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('brand_id', function ($row) {
                    return $row->brand->brand_name ?? '';
                })
                ->addColumn('category_id', function ($row) {
                    return $row->category->category_name ?? '';
                })
                 ->addColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    if ($row->status == "Active") {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item editProduct" href="javascript:void(0)"><i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Delete </a>
                                <a data-id="' . $row->id . '" data-status="Not Active" class="dropdown-item changeStatus" href="javascript:void(0)"><i class="far fa fa-print text-warning"></i> Make Deactive </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                    } else {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item editProduct" href="javascript:void(0)"><i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Delete </a>
                                <a data-id="' . $row->id . '" data-status="Active" class="dropdown-item changeStatus" href="javascript:void(0)"><i class="far fa fa-print text-warning"></i> Make Active </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Active') {
                        return '<span class="badge badge-primary">Active</span>';
                    }
                    return '<span class="badge badge-success">Not Active</span>';
                })
                ->rawColumns(['action', 'status'])
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
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('backend.branch.modules.products.products', compact('brands', 'categories'));
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
                'product_name' => ['required', 'string', 'max:255'],
                'product_code' => ['required', 'string', 'max:255'],
                'brand_id' => ['required', 'numeric'],
                'category_id' => ['required', 'numeric'],
                'maximum_retail_price' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }


            $special_characters = [':', '-', '/', '%', '#', '&', '@', '$', '*', ' ', ')', '(', '!', '^', '-', '+', '_', '=', '{', '}', '[', ']', '', '<', '>' . '?'];
            $product_code_search = str_replace($special_characters, '', $request->product_code);
            $product_name_search = str_replace($special_characters, '', $request->product_name);


            $description = $request->description;
            $color_code = $request->color_code;
            $size = $request->size;
            if (empty($description)) {
                $description = ' ';
            }
            if (empty($color_code)) {
                $color_code = ' ';
            }
            if (empty($size)) {
                $size = ' ';
            }

            $product = new Product();
            $product->product_name = $request->product_name;
            $product->product_code = $request->product_code;
            $product->brand_id = $request->brand_id;
            $product->product_type = 'READY PRODUCT';
            $product->category_id = $request->category_id;
            $product->description = $description;
            $product->color_code = $color_code;
            $product->size = $size;
            $product->maximum_retail_price = $request->maximum_retail_price;
            $product->status = 'Active';
            $product->product_code_search = $product_code_search;
            $product->product_name_search = $product_name_search;

            if ($product->save()) {
                $response = response()->json(['success' => 'Product added successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding Product, please try again'], 200);
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
        $product = Product::with('brand', 'category')->find($id);
        if (!empty($product)) {
            return response()->json($product);
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
                'product_name' => ['required', 'string', 'max:255'],
                'product_code' => ['required', 'string', 'max:255'],
                'brand_id' => ['required', 'numeric'],
                'category_id' => ['required', 'numeric'],
                'maximum_retail_price' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }


            $special_characters = [':', '-', '/', '%', '#', '&', '@', '$', '*', ' ', ')', '(', '!', '^', '-', '+', '_', '=', '{', '}', '[', ']', '', '<', '>' . '?'];
            $product_code_search = str_replace($special_characters, '', $request->product_code);
            $product_name_search = str_replace($special_characters, '', $request->product_name);



            $description = $request->description;
            $color_code = $request->color_code;
            $size = $request->size;
            if (empty($description)) {
                $description = ' ';
            }
            if (empty($color_code)) {
                $color_code = ' ';
            }
            if (empty($size)) {
                $size = ' ';
            }


            $product = Product::where('id', $id)->update(
                [
                    'product_name' => $request->product_name,
                    'product_code' => $request->product_code,
                    'brand_id' => $request->brand_id,
                    'category_id' => $request->category_id,
                    'description' => $description,
                    'color_code' => $color_code,
                    'size' => $size,
                    'maximum_retail_price' => $request->maximum_retail_price,
                    'product_code_search' => $product_code_search,
                    'product_name_search' => $product_name_search,
                ]
            );
            if ($product) {
                $response = response()->json(['success' => 'Product updated successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in updating Product, please try again'], 200);
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
        $product = Product::find($id);
        if (!empty($product)) {
            $stock = Stock::where('product_id', $id)->get();
            if (count($stock) > 0) {
                return response()->json(['errors' => 'Error in deleting product, used in stocks']);
            } else {
                Product::find($id)->delete();
                return response()->json(['success' => 'Product deleted successfully']);
            }
        }
    }


    public function productStatusChange(Request $request)
    {
        $product = Product::where('id', $request->update_id)->update([
            'status' => $request->status
        ]);
        if ($product) {
            return response()->json(['success' => 'Product status changed successfully']);
        }
    }
}
