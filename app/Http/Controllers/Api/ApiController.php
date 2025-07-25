
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{
    /**
     * Login api
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }


    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required|string|email|max:255",
                "password" => "required|string|max:255|min:8",
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('token-name', ['server:update'])->plainTextToken;
            $success['user'] = $user;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Email or password is wrong']);
        }
    }



    public function getBranchStockListSearch(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "search_term" => "required",

            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        //
        $stocks = [];
        if ($request->has('search_term')) {
            $special_characters = [':', '-', '/', '%', '#', '&', '@', '$', '*', ' ', ')', '(', '!', '^', '-', '+', '_', '=', '{', '}', '[', ']', '', '<', '>' . '?'];
            $id = str_replace($special_characters, '', $request->search_term);
            $products = DB::table('products')
                ->where('product_code_search', 'like', '%' . $id . '%')
                ->orwhere('product_name_search', 'like', '%' . $id . '%')
                //->orderByRaw("CHARINDEX('$id',product_code_search, 1) DESC, product_code_search ASC")
                ->orderByRaw("IF(product_code_search = '{$id}',2,IF(product_code_search LIKE '{$id}%',1,0)) ASC")
                ->get();

            $product_code = [];
            foreach ($products as $p) {
                $product_code[] = $p->product_code;
            }

            $stocks = Product::with('category_id,category_name')
                ->select(['product_name', 'product_code', 'size', 'color_code', 'category_id', 'id'])
                ->whereIn('product_code', $product_code)
                ->get();
        }

        if ($stocks) {
            $success['stock_data'] = $stocks;
            return $this->sendResponse($success, 'Stock data fetched succesfully');
        }
        return $this->sendError('Error.', ['error' => 'Error in fetching searched data, please try again']);
        // return response()->json($stocks);
    }




    public function getBranchStockListBySearch(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                "product_id" => "required",

            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        $branches_id = Branch::get()->pluck('id');
        $usersQuery = Stock::query();

        $usersQuery
            ->where('status', 'IN STOCK')
            ->whereIn('branch_in', $branches_id)
            ->where('product_id', $request->product_id)
            ->latest();
        $data = $usersQuery->groupBy('branch_in')->get();

        $myArray = [];

        foreach ($data as $row) {

            $stock_qty_data = "";
            $branch_id = $row->branch_in;
            $product_id = $row->product_id;
            $stock_qty = StockQty::where('branch_id', $branch_id)
                ->where('product_id', $product_id)
                ->first();

            if (!empty($stock_qty)) {
                $stock_qty_data = $stock_qty->qty;
            } else {
                $stock_qty_data = 0;
            }

            array_push($myArray, (object)[
                'branch_name' => $row->branchTo->branch_name,
                'brand_name' => $row->product->brand->brand_name,
                'category' => $row->product->category->category_name,
                'product_name' =>  $row->product->product_name,
                'model_no' => $row->product->product_code,
                'color' => $row->product->color_code,
                'size' => $row->product->size,
                'stock_qty' => $stock_qty_data,
                'id' => $row->product->id,
            ]);
        }


        if ($myArray) {

            $success['searched_data'] = $myArray;
            return $this->sendResponse($success, 'Searched data fetched succesfully');
        }
        return $this->sendError('Error.', ['error' => 'Error in fetching searched data, please try again']);
    }



    public function getConsolidateList()
    {
        $usersQuery = Stock::query();
        $usersQuery->latest();
        $data1 = $usersQuery->groupBy('product_id')->get();

        $stocks_id = [];
        foreach ($data1 as $row) {
            $usersQuery2 = StockQty::query();
            $stock_qty = $usersQuery2->where('product_id', $row->product_id)
                ->get();
            if (!empty($stock_qty)) {
                foreach ($stock_qty as $row2) {
                    if ($row2->qty > 0) {
                        $stocks_id[] = $row->id;
                    }
                }
            }
        }


        $usersQuery3 = Stock::query();
        $data2 = $usersQuery3->whereIn('id', $stocks_id)->latest()
            ->groupBy('product_id')
            ->get();

        $myArray = [];
        foreach ($data2 as $row) {
            $product_id = $row->product_id;
            $stock_qty = StockQty::where('product_id', $product_id)->get();
            $stock_qty_data =  $stock_qty->sum('qty');
            array_push($myArray, (object)[
                'brand_name' => $row->product->brand->brand_name,
                'category' => $row->product->category->category_name,
                'model_no' => $row->product->product_code,
                'product_name' =>  $row->product->product_name,
                'stock_qty' => $stock_qty_data,
                'color' => $row->product->color_code,
                'size' => $row->product->size,
                'product_id' => $row->product->id,
            ]);
        }

        if ($myArray) {
            $success['consolidate_list'] = $myArray;
            return $this->sendResponse($success, 'consolidate list fetched succesfully');
        }
        return $this->sendError('Error.', ['error' => 'error in fetching consolidate list data, please try again']);
    }
}
