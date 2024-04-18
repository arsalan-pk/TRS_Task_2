<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (\request()->ajax()) {
            $data = Category::get(['id', 'name']);

            return DataTables::of($data)
                ->addColumn('action', 'category.category-action')
                ->addColumn('product', function ($row) {
                    return  '<a href="/show-product/' .  $row->id . '">Show Products</a>';
                })
                ->rawColumns(['action', 'product'])
                ->make(true);
        }
        
        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $cat = new Category();
        $cat->name = $request->input('category_name');
        $cat->parent_id = Null;
        $ok = $cat->save();

        if (!$ok) {
            return response()->json(['status' => 0, 'msg' => 'something goes wrong']);
        } else {
            return response()->json(['status' => 1, 'msg' => 'created  Successfully'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $product = Category::where('id', '=', $request->id)->first();
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $query = Category::find($request->category_id)->update([
                'name' => $request->category_name,
            ]);
        }

        if (!$query) {
            return response()->json(['status' => 0, 'msg' => 'some thing wrong']);
        } else {
            return response()->json(['status' => 1, 'msg' => 'successful updated'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $Category = Category::findOrfail($id);
        $Category->delete();
        return response()->json(['message' => 'delete successfully'], 200);
    }
}
