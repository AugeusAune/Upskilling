<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiProductController extends Controller
{
    public function index(Request $request){
        Paginator::useBootstrap();

        $search = $request->search;
        switch($request->sort) {
            case 1: 
                $sort = ['id', 'ASC'];
                break;
            case 2: 
                $sort = ['name', 'ASC'];
                break;
            case 3: 
                $sort = ['name', 'DESC'];
                break;
            case 4: 
                $sort = ['price', 'ASC'];
                break;
            case 5: 
                $sort = ['price', 'DESC'];
                break;
            default:
                $sort = ['id', 'ASC'];
                break;
        }

        $products = Product::where('name', 'like', "%$search%")->with('images')->orderBy($sort[0], $sort[1])->paginate(6)->withQueryString();
        return response()->json([
            'success' => true,
            'path' =>  'http://localhost:8000/api/public/product_image',
            'products' => $products,
            'title' => 'Shop'
        ], 200);
    }

    public function detail(Product $products, $id) {
        $product = $products->with('images')->find($id);
        return response()->json([
            'success' => true,
            'products' => $product,
        ], 202);
    }

    public function store(Product $product, Request $request, ProductImage $image){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'images' => 'nullable',
            'images.*' => 'image:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 406);
        }

        $data = $product->create([
            'name' => $request->name,
            'price' => $request->price
        ]);

        if($request->file('images')) {
            $nameImage = [];
            for($i = 0; $i < count($request->file('images')); $i++) {
                $file = $request->file('images')[$i]->store('product_image');
                $nameImage[] = basename($file);
            }

            if($data) {
                for($i = 0; $i < count($nameImage); $i++){
                    $image->create([
                        'product_id' => $data->id,
                        'file' => $nameImage[$i]
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product Successfuly Created' 
        ], 201);
    }

    public function update(Request $request, Product $product,  ProductImage $image, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'images' => 'nullable',
            'images.*' => 'image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 406);
        }

        $data = $product->find($id);
        if($request->file('images')) {
            $nameImage = [];
            for($i = 0; $i < count($request->file('images')); $i++) {
                $file = $request->file('images')[$i]->store('product_image');
                $nameImage[] = basename($file);
            }
            if($nameImage) {
                for($i = 0; $i < count($nameImage); $i++){
                    $image->create([
                        'product_id' => $data->id,
                        'file' => $nameImage[$i]
                    ]);
                }
            }
        }

        $data->update([
            'name' => $request->name,
            'price' => $request->price
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Success Updated Product'
        ], 202);
    }

    public function destroy(Product $product, $id) {
        $data = $product->find($id);
        if(count($data->images) > 0) {
            foreach($data->images as $items) {
                Storage::delete("/product_image/$items->file");
                $items->delete();
            }
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success deleting data'
        ], 202);
    }

    public function delete_image(ProductImage $image, $id){
        $data = $image->find($id);
        Storage::delete("product_image/$data->file");
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success Deleting Image'
        ], 201);
    }

}
