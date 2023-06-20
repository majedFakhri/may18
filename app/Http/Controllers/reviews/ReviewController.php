<?php

namespace App\Http\Controllers\reviews;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;


use App\Http\Traits\GeneralTrait;
use Validator;


class ReviewController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
   
        try{
            $msg='all reviews are Right Here';
            $data=Review::with(['user','product'])->get();
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
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

        $validator=Validator::make($request->all(),[
            'user_id'=>'required|integer',
                'product_id'=>'required|integer',
                'comment'=>'required|regex:/[a-zA-Z\s]+/',
                'rating'=>'required|integer'
            ]
        );
                if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }
      try {
            $user = User::find($request->user_id);
            $product = Product::find($request->product_id);
            $review = Review::create($request->all());
            $review->user()->associate($user);
            $review->product()->associate($product);
           $data=$review;
           $msg='review is created successfully';
            return $this->successResponse($data,$msg,201);
        }
        catch (\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
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
        try{
            $data=Review::with(['user','product'])->find($id);
            if(!$data)
                return $this->errorResponse('No Review with such id',404);

            $this->authorize('view', $data);
            $msg='Got you the Review you are looking for';
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
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
        
        try{
            $data=Review::find($id);
            if(!$data)
                return $this->errorResponse('No Review with such id',404);
 
            $this->authorize('update', $data);
            $data->update($request->all());
            $msg='The Review is updated successfully';
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
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
    
        try{
            $data=Review::find($id);
            if(!$data)
                return $this->errorResponse('No Review with such id',404);

            $this->authorize('delete', $data);
            $data->delete();
            $msg='The Review is deleted successfully';
            return $this->successResponse($data,$msg);
        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }
}
