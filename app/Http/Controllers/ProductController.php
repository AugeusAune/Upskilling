<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
        $data = [
            'products' => $products,
            'title' => 'Shop'
        ];
        return view('product', $data);
    }

    public function detail(Product $products, $id) {
        $product = $products->find($id);
        return view('detail', compact('product'));
    }

    public function store(Product $product, Request $request, ProductImage $image){
        $request->validate([
            'name' => 'required',
            'price' => ['required','numeric'],
            'images' => 'nullable',
            'images.*' => 'mimetypes:images/jpeg,image/png,images/jpg'
        ]);

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

        return redirect('/')->with('success', 'Success Add Data');
    }

    public function update(Product $product, Request $request, ProductImage $image, $id) {
        $request->validate([
            'name' => 'required',
            'price' => ['required','numeric'],
            'file' => 'nullable',
            'file.*' => 'mimetypes:images/jpeg,image/png,images/jpg'
        ]);

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

        $attr = $request->all();
        $data->update($attr);
        return redirect("/product/$id")->with('success', 'success Update Data');
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
        ]);
        // return redirect('/')->with('success', 'Success Delete');
    }

    public function delete_image(ProductImage $image, $id){
        $data = $image->find($id);
        Storage::delete("product_image/$data->file");
        $data->delete();
        return back()->with('success', 'Success delete Image');
    }

}
