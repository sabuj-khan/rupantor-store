<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\TokenAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Front End Route

// Hom Page
Route::get('/', [HomeController::class, 'homePage']);
// Category Products
Route::get('/categoryPage', [CategoryController::class, 'categoryProductsByid']);
// Brand Page
Route::get('/brandPage', [BrandController::class, 'productByBrand']);
// Policy Page
Route::get('/policyPage', [PolicyController::class, 'policyPageShow']);
// Product Details Page
Route::get('/detailProduct', [ProductController::class, 'productPage']);
// User Login Page
Route::get('/userLogin', [UserController::class, 'userLoginPage']);
// OTP Verify Page
Route::get('/verifyOTP', [UserController::class, 'otpVerification']);
// Product Wish List
Route::get('/wishList', [ProductController::class, 'wishListPage'])->middleware([TokenAuthMiddleware::class]);
// Cart Product page
Route::get('/cartPage', [ProductController::class, 'cartListPage'])->middleware([TokenAuthMiddleware::class]);
// Profile Page
Route::get('/profile', [UserController::class, 'profilePage'])->middleware([TokenAuthMiddleware::class]);



// Brand APIs
Route::get('/brand-list', [BrandController::class, 'brandListDisplay']);

// Category APIs
Route::get('/category-list', [CategoryController::class, 'categoryListDisplay']);

// Products APIs
Route::get('/products', [ProductController::class, 'products']);
Route::get('/productlistbycategory/{id}', [ProductController::class, 'productListByCategory']);
Route::get('/productlistbybrand/{id}', [ProductController::class, 'productListByBrand']);
Route::get('/productlistbyremark/{remark}', [ProductController::class, 'productListByRemark']);
Route::get('/productdetails/{id}', [ProductController::class, 'productDetailsByID']);

// Slider APIs
Route::get('/productsliders', [ProductController::class, 'productSlidersList']);

// Product Reviews APIs
Route::get('/productreview/{product_id}', [ProductController::class, 'productReviewByProductId']);
Route::post('/createProductReview', [ProductController::class, 'productReviewCreation'])->middleware([TokenAuthMiddleware::class]);


// Policy APIs 
Route::get('/policytype/{type}', [PolicyController::class, 'policyShowByType']);


// User Authentication APIs
Route::get('/userlogin/{userEmail}', [UserController::class, 'userLoginAction']);
Route::get('/verifyloginotp/{userEmail}/{otp}', [UserController::class, 'userLoginOTPVerify']);
Route::get('/userlogout', [UserController::class, 'userLogoutAction']);

// Profile APIs
Route::post('/createuserProfile', [ProfileController::class, 'profileCreationAction'])->middleware([TokenAuthMiddleware::class]);
Route::get('/readuserProfile', [ProfileController::class, 'userProfileRead'])->middleware([TokenAuthMiddleware::class]);
//Route::get('/Profile', [ProfileController::class, 'profile'])->middleware([TokenAuthMiddleware::class]);

// Cart APIs
Route::get('/cart-list', [ProductController::class, 'cartListDisplay'])->middleware([TokenAuthMiddleware::class]);
Route::post('/create-productcart', [ProductController::class, 'createCartProductAction'])->middleware([TokenAuthMiddleware::class]);
Route::get('/cart-count', [ProductController::class, 'cartCountAction'])->middleware([TokenAuthMiddleware::class]);
Route::get('/delete_cart-product/{product_id}', [ProductController::class, 'deleteProductCartAction'])->middleware([TokenAuthMiddleware::class]);


// Wish APIs
Route::get('/wish-list', [ProductController::class, 'wishListDisplay'])->middleware([TokenAuthMiddleware::class]);
Route::get('/wish-product-create/{product_id}', [ProductController::class, 'wishListCreation'])->middleware([TokenAuthMiddleware::class]);
Route::get('/wish-product-delete/{product_id}', [ProductController::class, 'wishListDeleting'])->middleware([TokenAuthMiddleware::class]);

// Invoice APIs
Route::post('/create-invoice', [InvoiceController::class, 'invoiceCreationAction'])->middleware([TokenAuthMiddleware::class]);
Route::get('/invoice-list', [InvoiceController::class, 'invoiceListAction'])->middleware([TokenAuthMiddleware::class]);
Route::get('/invoice-product-list/{invoice_id}', [InvoiceController::class, 'invoiceProductListAction'])->middleware([TokenAuthMiddleware::class]);


// Payment APIs
Route::post('/PaymentSuccess', [InvoiceController::class, 'PaymentSuccess']);
Route::post('/PaymentFail', [InvoiceController::class, 'PaymentFail']);
Route::post('/PaymentCancel', [InvoiceController::class, 'PaymentCancel']);

