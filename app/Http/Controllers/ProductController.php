<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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
                // ->addColumn('action', function ($row) {
                //     $actionBtn = '@can("edit category")<a href="javascript:void(0)" id="editCategory" data-id="' . $row->id . '" class="btn btn-sm"><i class="fas fa-edit"></i></a>@endcan
                //  <a href="javascript:void(0)" id="deleteCategory" data-id="' . $row->id . '" style="color:red;"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                //     return $actionBtn;
                // })
                ->addColumn('action', 'products.product-action')
                ->addColumn('detail', function ($row) {
                    return  '<a href="/product-detail-page/' .  $row->id . '">Show Detail</a>';
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
        return view('products.create-product', compact('categories'));
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

        return view('products.show-product', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::where('id', '=', $id)->first();
        $categories = Category::get();
        return view('products.edit-product', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
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
