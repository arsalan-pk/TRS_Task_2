<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (\request()->ajax()) {
            $data = Product::get();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    if (auth()->user()->hasAnyPermission(['edit category', 'delete category'])) {
                        $action_btn =
                            '<a href="' . route('products.edit', $row->id) . '" id="categories-edit" data-id="' . $row->id . '" style="color:green;"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" id="products-destroy" data-id="' . $row->id . '" style="color:red;"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    } else {
                        $action_btn = '<h5>No Permission</h5>';
                    }

                    return $action_btn;
                })

                ->addColumn('detail', function ($row) {
                    return  '<a href="/products/' .  $row->id . '">Show Detail</a>';
                })

                ->rawColumns(['action', 'detail'])
                ->make(true);
        }
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::get(['name', 'id']);
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            // 'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        $product->categories()->attach($request->categories);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $storeUrl = 'storage/images/' . $imageName;

                $compressedImage = Image::make($image)
                    ->encode('jpg', 80);

                $compressedImage->save(storage_path('app/public/images/' . $imageName));

                $product->images()->create([
                    'url' => $storeUrl,
                ]);
            }
        }

        return response()->json(['status' => 1, 'message' => 'Product created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $products = Category::with('products')->whereId($id)->get();
        // return view('products.show-product',compact('products'));

        $category = Category::findOrFail($id);
        $products = $category->products()->paginate(10);

        return view('products.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with('categories')->where('id', '=', $id)->first();
        $categories = Category::get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $product = Product::findOrFail($request->product_id);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        $product->categories()->sync($request->categories);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $storeUrl = 'storage/images/' . $imageName;

                $compressedImage = Image::make($image)
                    ->encode('jpg', 75);
                $compressedImage->save(storage_path('app/public/images/' . $imageName));

                $product->images()->create([
                    'url' => $storeUrl,
                ]);
            }
        }

        return response()->json(['status' => 1, 'message' => 'Product created successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->categories()->detach();

        foreach ($product->images as $image) {

            if (File::exists($image->url)) {
                File::delete($image->url);
            }

            $image->delete();
        }

        $product->delete();
        return response()->json(['message' => 'delete successfully'], 200);
    }
}
