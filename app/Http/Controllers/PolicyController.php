<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    function policyShowByType(Request $request){
        $policyPage = Policy::where('type', '=', $request->type)->first();

        return response()->json([
            'message' => 'success',
            'data' => $policyPage
        ], 200);
    }

    function policyPageShow(){
        return view('pages.policy-page');
    }

}
