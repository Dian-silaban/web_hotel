<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\BlogController;

use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\RoomController;
use App\Http\Controllers\Front\BookingController;

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminAmenityController;
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminDatewiseRoomController;


use App\Http\Controllers\Customer\CustomerHomeController;
use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerOrderController;

/* Front */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/post/{id}', [BlogController::class, 'single_post'])->name('post');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send-email', [ContactController::class, 'send_email'])->name('contact_send_email');

Route::get('/room', [RoomController::class, 'index'])->name('room');
Route::get('/room/{id}', [RoomController::class, 'single_room'])->name('room_detail');
Route::post('/booking/submit', [BookingController::class, 'cart_submit'])->name('cart_submit');
Route::get('/cart', [BookingController::class, 'cart_view'])->name('cart');
Route::get('/cart/delete/{id}', [BookingController::class, 'cart_delete'])->name('cart_delete');
Route::get('/checkout', [BookingController::class, 'checkout'])->name('checkout');
Route::post('/payment', [BookingController::class, 'payment'])->name('payment');

Route::get('/payment/paypal/{price}', [BookingController::class, 'paypal'])->name('paypal');
Route::post('/payment/stripe/{price}', [BookingController::class, 'stripe'])->name('stripe');


/* Admin */
Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin_login');
Route::post('/admin/login-submit', [AdminLoginController::class, 'login_submit'])->name('admin_login_submit');
Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin_logout');
Route::get('/admin/forget-password', [AdminLoginController::class, 'forget_password'])->name('admin_forget_password');
Route::post('/admin/forget-password-submit', [AdminLoginController::class, 'forget_password_submit'])->name('admin_forget_password_submit');
Route::get('/admin/reset-password/{token}/{email}', [AdminLoginController::class, 'reset_password'])->name('admin_reset_password');
Route::post('/admin/reset-password-submit', [AdminLoginController::class, 'reset_password_submit'])->name('admin_reset_password_submit');

Route::middleware(['guest:customer'])->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'login'])->name('customer_login');
    Route::post('/login-submit', [CustomerAuthController::class, 'login_submit'])->name('customer_login_submit');
});

// Customer Authentication Routes
Route::middleware(['guest:customer'])->group(function () {

    // Route for the signup form
    Route::get('/signup', [CustomerAuthController::class, 'signup'])->name('customer_signup');
    
    // Route for submitting the signup form
    Route::post('/signup-submit', [CustomerAuthController::class, 'signup_submit'])->name('customer_signup_submit');
    
    // Route for verifying the email after signup
    Route::get('/signup-verify/{email}/{token}', [CustomerAuthController::class, 'signup_verify'])->name('customer_signup_verify');
    
    // Route for displaying the forgot password form
    Route::get('/forget-password', [CustomerAuthController::class, 'forget_password'])->name('customer_forget_password');
    
    // Route for submitting the forgot password request
    Route::post('/forget-password-submit', [CustomerAuthController::class, 'forget_password_submit'])->name('customer_forget_password_submit');
    
    // Route for resetting the password
    Route::get('/reset-password/{token}/{email}', [CustomerAuthController::class, 'reset_password'])->name('customer_reset_password');
    Route::post('/reset-password-submit', [CustomerAuthController::class, 'reset_password_submit'])->name('customer_reset_password_submit');
});

// Route for customer logout
Route::get('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer_logout');

// Customer routes with middleware applied (for logged-in users)
Route::middleware(['customer:customer'])->group(function () {
    // Route for customer home page
    Route::get('/customer/home', [CustomerHomeController::class, 'index'])->name('customer_home');
    
    // Route for editing customer profile
    Route::get('/customer/edit-profile', [CustomerProfileController::class, 'index'])->name('customer_profile');
    Route::post('/customer/edit-profile-submit', [CustomerProfileController::class, 'profile_submit'])->name('customer_profile_submit');
    
    // Route for viewing customer orders
    Route::get('/customer/order/view', [CustomerOrderController::class, 'index'])->name('customer_order_view');
    Route::get('/customer/invoice/{id}', [CustomerOrderController::class, 'invoice'])->name('customer_invoice');
});



Route::middleware(['guest:admin'])->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin_login');
    Route::post('/admin/login-submit', [AdminLoginController::class, 'login_submit'])->name('admin_login_submit');
});


/* Admin - Middleware untuk halaman admin yang memerlukan autentikasi */
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/home', [AdminHomeController::class, 'index'])->name('admin_home');
    Route::get('/admin/edit-profile', [AdminProfileController::class, 'index'])->name('admin_profile');
    Route::post('/admin/edit-profile-submit', [AdminProfileController::class, 'profile_submit'])->name('admin_profile_submit');

    Route::get('/admin/setting', [AdminSettingController::class, 'index'])->name('admin_setting');
    Route::post('/admin/setting/update', [AdminSettingController::class, 'update'])->name('admin_setting_update');

    Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin_logout');
    Route::get('/admin/datewise-rooms', [AdminDatewiseRoomController::class, 'index'])->name('admin_datewise_rooms');
    Route::post('/admin/datewise-rooms/submit', [AdminDatewiseRoomController::class, 'show'])->name('admin_datewise_rooms_submit');

    Route::get('/admin/customers', [AdminCustomerController::class, 'index'])->name('admin_customer');
    Route::get('/admin/customer/change-status/{id}', [AdminCustomerController::class, 'change_status'])->name('admin_customer_change_status');

    Route::get('/admin/order/view', [AdminOrderController::class, 'index'])->name('admin_orders');
    Route::get('/admin/order/invoice/{id}', [AdminOrderController::class, 'invoice'])->name('admin_invoice');
    Route::get('/admin/order/delete/{id}', [AdminOrderController::class, 'delete'])->name('admin_order_delete');

    Route::get('/admin/slide/view', function () {
        return view('admin.slide_view');
    })->name('admin_slide_view');
    

    Route::get('/admin/feature/view', [AdminFeatureController::class, 'index'])->name('admin_feature_view');
    Route::get('/admin/feature/add', [AdminFeatureController::class, 'add'])->name('admin_feature_add');
    Route::post('/admin/feature/store', [AdminFeatureController::class, 'store'])->name('admin_feature_store');
    Route::get('/admin/feature/edit/{id}', [AdminFeatureController::class, 'edit'])->name('admin_feature_edit');
    Route::post('/admin/feature/update/{id}', [AdminFeatureController::class, 'update'])->name('admin_feature_update');
    Route::get('/admin/feature/delete/{id}', [AdminFeatureController::class, 'delete'])->name('admin_feature_delete');

    Route::get('/admin/testimonial/view', [AdminTestimonialController::class, 'index'])->name('admin_testimonial_view');
    Route::get('/admin/testimonial/add', [AdminTestimonialController::class, 'add'])->name('admin_testimonial_add');
    Route::post('/admin/testimonial/store', [AdminTestimonialController::class, 'store'])->name('admin_testimonial_store');
    Route::get('/admin/testimonial/edit/{id}', [AdminTestimonialController::class, 'edit'])->name('admin_testimonial_edit');
    Route::post('/admin/testimonial/update/{id}', [AdminTestimonialController::class, 'update'])->name('admin_testimonial_update');
    Route::get('/admin/testimonial/delete/{id}', [AdminTestimonialController::class, 'delete'])->name('admin_testimonial_delete');

    Route::get('/admin/post/view', [AdminPostController::class, 'index'])->name('admin_post_view');
    Route::get('/admin/post/add', [AdminPostController::class, 'add'])->name('admin_post_add');
    Route::post('/admin/post/store', [AdminPostController::class, 'store'])->name('admin_post_store');
    Route::get('/admin/post/edit/{id}', [AdminPostController::class, 'edit'])->name('admin_post_edit');
    Route::post('/admin/post/update/{id}', [AdminPostController::class, 'update'])->name('admin_post_update');
    Route::get('/admin/post/delete/{id}', [AdminPostController::class, 'delete'])->name('admin_post_delete');











    Route::get('/admin/page/contact', [AdminPageController::class, 'contact'])->name('admin_page_contact');
    Route::post('/admin/page/contact/update', [AdminPageController::class, 'contact_update'])->name('admin_page_contact_update');




    Route::get('/admin/page/blog', [AdminPageController::class, 'blog'])->name('admin_page_blog');
    Route::post('/admin/page/blog/update', [AdminPageController::class, 'blog_update'])->name('admin_page_blog_update');

    Route::get('/admin/page/room', [AdminPageController::class, 'room'])->name('admin_page_room');
    Route::post('/admin/page/room/update', [AdminPageController::class, 'room_update'])->name('admin_page_room_update');

    Route::get('/admin/page/cart', [AdminPageController::class, 'cart'])->name('admin_page_cart');
    Route::post('/admin/page/cart/update', [AdminPageController::class, 'cart_update'])->name('admin_page_cart_update');

    Route::get('/admin/page/checkout', [AdminPageController::class, 'checkout'])->name('admin_page_checkout');
    Route::post('/admin/page/checkout/update', [AdminPageController::class, 'checkout_update'])->name('admin_page_checkout_update');

    Route::get('/admin/page/payment', [AdminPageController::class, 'payment'])->name('admin_page_payment');
    Route::post('/admin/page/payment/update', [AdminPageController::class, 'payment_update'])->name('admin_page_payment_update');

    Route::get('/admin/page/signup', [AdminPageController::class, 'signup'])->name('admin_page_signup');
    Route::post('/admin/page/signup/update', [AdminPageController::class, 'signup_update'])->name('admin_page_signup_update');

    Route::get('/admin/page/signin', [AdminPageController::class, 'signin'])->name('admin_page_signin');
    Route::post('/admin/page/signin/update', [AdminPageController::class, 'signin_update'])->name('admin_page_signin_update');

    Route::get('/admin/page/forget_password', [AdminPageController::class, 'forget_password'])->name('admin_page_forget_password');
    Route::post('/admin/page/forget_password/update', [AdminPageController::class, 'forget_password_update'])->name('admin_page_forget_password_update');

    Route::get('/admin/page/reset_password', [AdminPageController::class, 'reset_password'])->name('admin_page_reset_password');
    Route::post('/admin/page/reset_password/update', [AdminPageController::class, 'reset_password_update'])->name('admin_page_reset_password_update');




    Route::get('/admin/amenity/view', [AdminAmenityController::class, 'index'])->name('admin_amenity_view');
    Route::get('/admin/amenity/add', [AdminAmenityController::class, 'add'])->name('admin_amenity_add');
    Route::post('/admin/amenity/store', [AdminAmenityController::class, 'store'])->name('admin_amenity_store');
    Route::get('/admin/amenity/edit/{id}', [AdminAmenityController::class, 'edit'])->name('admin_amenity_edit');
    Route::post('/admin/amenity/update/{id}', [AdminAmenityController::class, 'update'])->name('admin_amenity_update');
    Route::get('/admin/amenity/delete/{id}', [AdminAmenityController::class, 'delete'])->name('admin_amenity_delete');


    Route::get('/admin/room/view', [AdminRoomController::class, 'index'])->name('admin_room_view');
    Route::get('/admin/room/add', [AdminRoomController::class, 'add'])->name('admin_room_add');
    Route::post('/admin/room/store', [AdminRoomController::class, 'store'])->name('admin_room_store');
    Route::get('/admin/room/edit/{id}', [AdminRoomController::class, 'edit'])->name('admin_room_edit');
    Route::post('/admin/room/update/{id}', [AdminRoomController::class, 'update'])->name('admin_room_update');
    Route::get('/admin/room/delete/{id}', [AdminRoomController::class, 'delete'])->name('admin_room_delete');

    Route::get('/admin/room/gallery/{id}', [AdminRoomController::class, 'gallery'])->name('admin_room_gallery');
    Route::post('/admin/room/gallery/store/{id}', [AdminRoomController::class, 'gallery_store'])->name('admin_room_gallery_store');
    Route::get('/admin/room/gallery/delete/{id}', [AdminRoomController::class, 'gallery_delete'])->name('admin_room_gallery_delete');
});

