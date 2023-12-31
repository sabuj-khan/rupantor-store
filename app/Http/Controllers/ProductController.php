<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductDetail;
use App\Models\ProductReview;
use App\Models\ProductSlider;
use App\Models\ProductWish;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function products(Request $request){
        return Product::all();
    }

    function productPage(){
        return view('pages.product-detail-page');
    }

    function wishListPage(){
        return view('pages.wishList-page');
    }

    function cartListPage(){
        return view('pages.cart-product-page');
    }


    function productListByCategory(Request $request){
        $categoryProducts = Product::where('category_id', '=', $request->id)->with('brand', 'category')->get();

        return response()->json([
            'message' => 'success',
            'data' => $categoryProducts
        ], 200);
    }

    function productListByBrand(Request $request){
        $brandProducts = Product::where('brand_id', '=', $request->id)->with('brand', 'category')->get();

        return response()->json([
            'message' => 'success',
            'data' => $brandProducts
        ], 200);

    }

    function productListByRemark(Request $request){
        $remakProducts = Product::where('remark', '=', $request->remark)->with('brand', 'category')->get();

        return response()->json([
            'message' => 'success',
            'data' => $remakProducts
        ], 200);
    }

    function productSlidersList(Request $request){
        $productSlider = ProductSlider::all();

        return response()->json([
            'message' => 'success',
            'data' => $productSlider
        ], 200);

    }

    function productDetailsByID(Request $request){
        $productDetails = ProductDetail::where('id', '=', $request->id)->with('product', 'product.brand', 'product.category')->first();

        return response()->json([
            'message' => 'success',
            'data' => $productDetails
        ], 200);
    }

    function productReviewByProductId(Request $request){
        $productReview = ProductReview::where('product_id', '=', $request->product_id)->with(['profile'=>function($query){
            $query->select('id', 'cus_name');
        }])->get();

        return response()->json([
            'message' => 'success',
            'data' => $productReview
        ], 200);
    }

    function productReviewCreation(Request $request){
        try{
            $userId = $request->header('id');
            $request->merge(['user_id'=>$userId]);

            $profile = CustomerProfile::where('user_id', '=', $userId)->first();

            if($profile){
                $review = ProductReview::updateOrCreate(
                    ['customer_id' => $profile->id, 'product_id' => $request->input('product_id')],
                    $request->input(),
                );
                return response()->json([
                    'message' => 'success',
                    'data' => $review
                ], 200);

            }else{
                return response()->json([
                    'status'=> 'error',
                    'message'=> 'You do not have any user ptofile to leave a review to a product'
                ], 401);
            }
        }
        catch(Exception $e){
            return response()->json([
                'status'=> 'error',
                'message'=> 'Something went wrong from ontroller'
            ]);
        }


    }

    function wishListDisplay(Request $request){
        $userId = $request->header('id');

        $wishProducts = ProductWish::where('user_id', '=', $userId)->with('product')->get();

        return response()->json([
            'status' => 'success',
            'data' => $wishProducts
        ]);
    }

    function wishListCreation(Request $request){
        try{
            $userId = $request->header('id');
            $productId = $request->product_id;

            $wishAddProduct = ProductWish::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $productId],
                ['user_id' => $userId, 'product_id' => $productId]
            );

            return response()->json([
                'status' => 'success',
                'data' => $wishAddProduct
            ], 201);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'data' => 'Request fail to add product to wishlist'
            ], 401);
        }

    }

    function wishListDeleting(Request $request){
        $userId = $request->header('id');
        $productId = $request->product_id;

        ProductWish::where('user_id', '=', $userId)->where('product_id', '=', $productId)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'The product has been removed successfully from wish list'
        ]);
    }

    function cartListDisplay(Request $request){
        try{
            $userId = $request->header('id');
            $cartData = ProductCart::with('product')->where('user_id', '=', $userId)->get();
            

            return response()->json([
                'status' => 'success',
                'data' => $cartData
            ], 200);

        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'data' => 'Something went wrong'
            ], 401);
        }
    }

    function cartCountAction(Request $request){
        $userId = $request->header('id');
        $cartCount = ProductCart::where('user_id', '=', $userId)->count();

        return response()->json([
            'count' => $cartCount
        ], 200);
    }

    function createCartProductAction(Request $request){
        try{
            $userId = $request->header('id');
            $productId = $request->input('product_id');
            $color = $request->input('color');
            $size = $request->input('size');
            $qty = $request->input('qty');
            $unitPrice = 0;

            $productDetails = Product::where('id', '=', $productId)->first();

            if($productDetails->discount == 1){
                $unitPrice = $productDetails->discount_price;
            }else{
                $unitPrice = $productDetails->price;
            }

            $totalPrice = $unitPrice*$qty;

            $cartProduct = ProductCart::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $productId],
                [
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'color' => $color,
                    'size' => $size,
                    'qty' => $qty,
                    'price' => $totalPrice
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Product has been added to cart successfully',
                'data'=> $cartProduct
            ], 201);


        }
        catch(Exception $e){
            return response()->json([
                'status'=> 'fail',
                'message'=> 'Something went wrong'
            ], 401);
        }
    }

    function deleteProductCartAction(Request $request){
        try{
            $userId = $request->header('id');
            $productId = $request->product_id;

            ProductCart::where('user_id', '=', $userId)->where('product_id', '=', $productId)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'The product has been deleted from cart list'
            ]);
        
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ]);
        }

    }




}
