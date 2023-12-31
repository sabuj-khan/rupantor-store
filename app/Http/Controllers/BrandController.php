<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    function brandListDisplay(){
       $brand = Brand::all();

       return response()->json([
        'message' => 'success',
        'data' => $brand
       ]);
    }

    function productByBrand(){
        return view('pages.brandPage-by-id');
    }


}
