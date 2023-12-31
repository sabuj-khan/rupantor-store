<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Helper\SSLCommerz;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function invoiceCreationAction(Request $request){
        DB::beginTransaction();
        try{
            $userId = $request->header('id');
            $user_email = $request->header('email');
            $tran_id = uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';

            $profile = CustomerProfile::where('user_id', '=', $userId)->first();

            $cus_details = "Name:$profile->cus_name,Address:$profile->cus_add,City:$profile->cus_city,Postcode:$profile->cus_postcode,State:$profile->cus_state,Phone:$profile->cus_phone,Fax:$profile->cus_fax";

            $ship_details = "Name:$profile->ship_name,Address:$profile->ship_add,City:$profile->ship_city,Postcode:$profile->ship_postcode,State:$profile->ship_state,$profile->ship_country,Phone:$profile->ship_phone";

            // Payable calculation 
            $total = 0;
            $cart_list = ProductCart::where('user_id', '=', $userId)->get();

            foreach($cart_list as $single_cart){
                $total = $total + $single_cart->price;
            }
            $vat = ($total*5)/100; // Vat 5% fixed, for example
            $payable = $total + $vat;

            $invoice = Invoice::create([
                'total' => $total,
                'vat' => $vat,
                'payable' => $payable,
                'cus_details' => $cus_details,
                'ship_details' => $ship_details,
                'tran_id' => $tran_id,
                'delivery_status' => $delivery_status,
                'payment_status' => $payment_status,
                'user_id' => $userId
            ]);

            $invoiceId = $invoice->id;
            
            foreach($cart_list as $single_item){
                InvoiceProduct::create([
                    'invoice_id' => $invoiceId,
                    'product_id' => $single_item['product_id'],
                    'user_id' => $userId,
                    'qty' => $single_item['qty'],
                    'sale_price' => $single_item['price'],

                ]);
            }

            $paymentMethod = SSLCommerz::InitiatePayment($profile,$payable,$tran_id,$user_email);

            DB::commit();

            return response()->json([
                'status'=>'success',
                'data'=>[
                    'paymentMethod'=>$paymentMethod,
                    'payable'=>$payable,
                    'vat'=>$vat,
                    'total'=>$total
                ],
            ]);
            
        }
        catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status'=>'fail',
                'message' =>$e->getmessage(),
            ]);
        }

    }


    function invoiceListAction(Request $request){
        try{
            $user_id = $request->header('id');

            $allInvoice = Invoice::where('user_id', '=', $user_id)->get();

            return response()->json([
                'status' => 'success',
                'data' => $allInvoice
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ]);
        }
    }


    function invoiceProductListAction(Request $request){
        try{
            $user_id = $request->header('id');
            $invoice_id = $request->invoice_id;

            $invoice_products = InvoiceProduct::where('user_id', '=', $user_id)->where('invoice_id', '=', $invoice_id)->with('product')->get();

            return response()->json([
                'status' => 'success',
                'data' => $invoice_products
            ], 201);
        
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ]);
        }

    }


    function PaymentSuccess(Request $request){
        SSLCommerz::InitiateSuccess($request->query('tran_id'));
        return redirect('/profile');
    }

    function PaymentFail(Request $request){
        SSLCommerz::InitiateSuccess($request->query('tran_id'));
        return redirect('/profile');
    }

    function PaymentCancel(Request $request){
        SSLCommerz::InitiateSuccess($request->query('tran_id'));
        return redirect('/profile');
    }


    function PaymentIPN(Request $request){
        SSLCommerz::PaymentIPN($request->input('tran_id'),$request->input('status'),$request->input('val_id'));
    }
    





}
