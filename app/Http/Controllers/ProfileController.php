<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    function profileCreationAction(Request $request){
        try{
            $userId = $request->header('id');
            $request->merge(['user_id'=>$userId]);

            $profile_info = CustomerProfile::updateOrCreate(
                ["user_id" => $userId],
                [
                    "cus_name"=>$request->input('cus_name'),
                    "cus_add"=>$request->input('cus_add'),
                    "cus_city"=>$request->input('cus_city'),
                    "cus_state"=>$request->input('cus_state'),
                    "cus_postcode"=>$request->input('cus_postcode'),
                    "cus_country"=>$request->input('cus_country'),
                    "cus_phone"=>$request->input('cus_phone'),
                    "cus_fax"=>$request->input('cus_fax'),
                    "ship_name"=>$request->input('ship_name'),
                    "ship_add"=>$request->input('ship_add'),
                    "ship_city"=>$request->input('ship_city'),
                    "ship_state"=>$request->input('ship_state'),
                    "ship_postcode"=>$request->input('ship_postcode'),
                    "ship_country"=>$request->input('ship_country'),
                    "ship_phone"=>$request->input('ship_phone'),
                    "user_id"=>$request->input('user_id'),
                    ]
            );
                return response()->json([
                    'status' => 'success',
                    'message'=>'Profile created successfully',
                    'data'=> $profile_info
                ],201);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message'=> 'Something went wrong to create customer profile',
                'error'=>$e->getMessage()
            ],405);
        }

    }

    function userProfileRead(Request $request){
        $userId = $request->header('id');
        $profile_info = CustomerProfile::where('user_id', '=', $userId)->first();

        return response()->json([
            'status' => 'success',
            'data' => $profile_info
        ]);
    }


   




}
