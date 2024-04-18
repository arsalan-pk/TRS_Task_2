<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }
    public function detail($id)
    {
        $product = Product::with(['images', 'comments', 'comments.user', 'reviews', 'reviews.user'])->findOrFail($id);
        $hasImage = $product->images->isNotEmpty();
        return view('user.product-detail-page', compact('product', 'hasImage'));
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
    public function storeComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $cat = new Comment();
        $cat->product_id = $request->input('product_id');
        $cat->user_id = auth()->user()->id;
        $cat->comment =  $request->comment;
        $ok = $cat->save();


        if (!$ok) {
            return response()->json(['status' => 0, 'msg' => 'something goes wrong']);
        } else {
            return response()->json(['status' => 1, 'username' => auth()->user()->name], 200);
        }
    }

    public function storeReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'review' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $cat = new Review();
        $cat->product_id = $request->input('product_id');
        $cat->user_id = auth()->user()->id;
        $cat->rating =  $request->rating;
        $cat->review =  $request->review;
        $ok = $cat->save();


        if (!$ok) {
            return response()->json(['status' => 0, 'msg' => 'something goes wrong']);
        } else {
            return response()->json(['status' => 1, 'username' => auth()->user()->name], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
