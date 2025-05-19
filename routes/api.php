<?php

use App\Http\Controllers\Backend\AboutPageController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\GatewayController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\SiteSettingController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/sliders', [SliderController::class, 'ApiAllSlider']);

/// Service api //
Route::get('/services', [ServiceController::class, 'AllServices']);
Route::get('/service/{slug}', [ServiceController::class, 'getServiceBySlug']);

/// Gateway one api //
Route::get('/gatewayone', [GatewayController::class, 'ApiGatewayOne']);
/// Gateway Two api //
Route::get('/gatewaytwo', [GatewayController::class, 'ApiGatewayTwo']);

Route::get('/gettestimonial',[TestimonialController::class, 'ApiGetTestimonial']);


///  Blog Category api //
Route::get('/blogcat',[BlogController::class, 'ApiBlogCat']);
 
///  Blog Post api //
Route::get('/allblog',[BlogController::class, 'ApiAllBlog']);

Route::get('/allblog/{slug}',[BlogController::class, 'ApiAllBlogSlug']);

Route::get('/sitesetting',[SiteSettingController::class, 'ApiSiteSetting']);

Route::get('/aboutpage',[AboutPageController::class, 'ApiAboutPage']);

Route::get('/category/{category_id}/blogs',[BlogController::class, 'getBlogsByCategory']);

Route::post('/contact',[AboutPageController::class, 'ContactSubmit']);
