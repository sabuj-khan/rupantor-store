<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function categoryListDisplay(){
        $categories = Category::all();

        return response()->json([
            'message' => 'success',
            'data' => $categories
        ]);
    }


    function categoryProductsByid(Request $request){
        return view('pages.products-by-category');
    }








}
