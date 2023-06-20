<?php

namespace App\Http\Controllers\orders;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Validator;



class OrderController extends Controller
{

    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {
            $msg = 'all orders are Right Here';
            $data = Order::with('user')->get();
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|integer',
                'product_id' => 'required|array',
                'product_id.*' => 'integer',
                'quantity' => 'required|array',
                'quantity.*' => 'integer',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {

            $products_id = $request->input('product_id');
            $quantities = $request->input('quantity');
            if (sizeof($products_id) != sizeof($quantities)) {
                return $this->errorResponse('the products must equals quantities', 422);
            }
            $user = User::findOrFail($request->input('user_id'));
            $order = Order::create($request->all());
            $order->user()->associate($user);
            for ($i = 0; $i < sizeof($products_id); $i++) {
                $order->products()->attach($products_id[$i], ['quantity' => $quantities[$i]]);
            }
            $data = $order->with('products')->get();
            $msg = 'order is created successfully';
            return $this->successResponse($data, $msg, 201);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
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

        try {
            $data = Order::with('products')->find($id);

            if (!$data)
                return $this->errorResponse('No order with such id', 404);

            $this->authorize('view', $data);
            $msg = 'Got you the order you are looking for';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $data = Order::find($id);

            if (!$data)
                return $this->errorResponse('No order with such id', 404);

            $this->authorize('delete', $data);
            $data->delete();
            $msg = 'The order is deleted successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
}