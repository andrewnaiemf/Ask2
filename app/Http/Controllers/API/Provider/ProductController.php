<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->header('per_page', 10);

        $products = Product::where(['provider_id' => auth()->user()->provider->id])
        ->simplePaginate($perPage);
        return $this->returnData($products);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateProductRequest($request);

        if ($validator->fails()) {
            return $this->returnValidationError(401, $validator->errors()->all());
        }
        $product_data = $request->except('image');
        $product = Product::create($product_data);

        if ($request->image) {
            $this->productImage($request->image, $product);
        }

        return $this->returnSuccessMessage( trans("api.product.createdSuccessfully") );

    }

    public function productImage($images,$product){

        $userId = auth()->user()->id;

        $path = 'Provider/' .$userId. '/products/';
        $product_images = json_decode($product->images);
        if ( isset($product_images)) {
            foreach ( $product_images as $existingImagePath) {
                if (Storage::disk('public')->exists($existingImagePath)) {

                    $product_images = array_filter($product_images, function ($pathItem) use ($existingImagePath) {
                        return $pathItem !== $existingImagePath;
                    });
                    Storage::disk('public')->delete($existingImagePath);
                }
            }
        }else{
            $product_images = [];
        }

        foreach ($images as $image) {
            $imageName = $image->hashName();
            $image->storeAs('public/'.$path,$imageName);
            $full_path = $path.$imageName;
            array_push($product_images , $full_path);
        }

        $product->update(['images' => json_encode($product_images)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_product(Request $request, $id)
    {
        $validator = $this->validateProductRequest($request);

        if ($validator->fails()) {
            return $this->returnValidationError(401, $validator->errors()->all());
        }

        $product_data = $request->except('image');
        $product = Product::findOrFail($id);
        $product->update($product_data);

        if ($request->image) {
            $this->productImage($request->image, $product);
        }

        return $this->returnSuccessMessage( trans("api.product.updatedSuccessfully") );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = Product::findOrFail($id);
        $room->delete();

        return $this->returnSuccessMessage(trans("api.product.deletedSuccessfully"));
    }

    protected function validateProductRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'info' => 'required|string',
            'ar.name' => 'required|string',
            'en.name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:providers,id',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

}
