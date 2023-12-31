<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    function userLoginPage(){
        return view('pages.login-page');
    }

    function otpVerification(){
        return view('pages.verifyOTP-page');
    }
    function userLoginAction(Request $request){
        try{
            $email = $request->userEmail;
            $otp = rand(100000, 999999);
            $details = ['code'=>$otp];

            // Sending OTP to email
            // Mail::to($email)->send(new OTPMail($details));

            // Send or update OTP to database
            User::updateOrCreate(
                ['email'=>$email],
                ['email'=>$email, 'otp'=> $otp]
            );

            return response()->json([
                'status'=> 'success',
                'message'=> 'A new OTP code has been sent to your email'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status'=> 'fail',
                'message'=> 'Request fail to send OTP',
                'error'=>$e->getMessage()
            ]);
        }
    }

    function userLoginOTPVerify(Request $request){
        try{
            $email = $request->userEmail;
            $otp = $request->otp;

            $verification = User::where('email', '=', $email)->where('otp', '=', $otp)->first();

            if($verification){
                User::where('email', '=', $email)->where('otp', '=', $otp)->update(['otp'=>0]);
                $token = JWTToken::createJWTToken($email, $verification->id);

                return response()->json([
                    'status'=> 'success',
                    'message'=> 'OTP code verified successfully',
                    'token' => $token,
                    'id' => $verification->id
                ], 200)->cookie('token', $token, 60*24*30);

            }else{
                return response()->json([
                    'status'=>'fail',
                    'message'=> 'This OTP is incorrect',
                ]);
            }
        }
        catch(Exception $e){
            return response()->json([
                'status'=>'fail',
                'message'=> 'Something went wrong',
                'error'=>$e->getMessage()
            ],401);
        }
    }

    function profilePage(){
        return view('pages.profile-page');
    }

    function userLogoutAction(Request $request){
        return redirect('/userLogin')->cookie('token', '', -1);
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'successfully loged out'
        // ])->cookie('token', '', -1);
    }



}
