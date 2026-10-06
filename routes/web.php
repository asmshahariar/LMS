<?php

use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CourseContentController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\CoursePageController;
use App\Http\Controllers\Frontend\EnrolledCourseController;
use App\Http\Controllers\Frontend\FrontendContactController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\HeroController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\StudentDashboardController;
use App\Http\Controllers\Frontend\StudentOrderController;
use Illuminate\Support\Facades\Route;

/**
 * ------------------------------------------------------
 * Frontend Routes
 * ------------------------------------------------------
 */

 Route::get('/', [FrontendController::class, 'index'])->name('home');
 Route::get('/courses', [CoursePageController::class, 'index'])->name('courses.index');
 Route::get('/courses/{slug}', [CoursePageController::class, 'show'])->name('courses.show');


 /** Cart routes */
 Route::get('cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
 Route::post('add-to-cart/{course}', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('auth');
 Route::get('remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('remove-from-cart')->middleware('auth');

 /** Payment Routes */
 Route::get('checkout', CheckoutController::class)->name('checkout.index');

 Route::get('paypal/payment', [PaymentController::class, 'payWithPaypal'])->name('paypal.payment');
 Route::get('paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
 Route::get('paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');

 /** Stripe Routes */
 Route::get('stripe/payment', [PaymentController::class, 'payWithStripe'])->name('stripe.payment');
 Route::get('stripe/success', [PaymentController::class, 'stripeSuccess'])->name('stripe.success');
 Route::get('stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('stripe.cancel');
 /** Razorpay Routes */
 Route::get('razorpay/redirect', [PaymentController::class, 'razorpayRedirect'])->name('razorpay.redirect');
 Route::post('razorpay/payment', [PaymentController::class, 'payWithRazorpay'])->name('razorpay.payment');

 Route::get('order-success', [PaymentController::class, 'orderSuccess'])->name('order.success');
 Route::get('order-failed', [PaymentController::class, 'orderFailed'])->name('order.failed');

 Route::post('newsletter-subscribe', [FrontendController::class, 'subscribe'])->name('newsletter.subscribe');
 /** about route */
 Route::get('about', [FrontendController::class, 'about'])->name('about.index');

 /** Contact route */
 Route::get('contact', [FrontendContactController::class, 'index'])->name('contact.index');
 Route::post('contact', [FrontendContactController::class, 'sendMail'])->name('send.contact');

 /** Review Routes */
 Route::post('review', [CoursePageController::class, 'storeReview'])->name('review.store');

 /** Custom page Routes */
 Route::get('page/{slug}', [FrontendController::class, 'customPage'])->name('custom-page');

 /** Blog Routes */
 Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
 Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
 Route::post('blog/comment/{id}', [BlogController::class, 'storeComment'])->name('blog.comment.store');

/**
 * ------------------------------------------------------
 * Student Routes
 * ------------------------------------------------------
 */
Route::group(['middleware' => ['auth:web', 'verified', 'check_role:student'], 'prefix' => 'student', 'as' => 'student.'], function() {
   Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');


   /** Profile Routes */
   Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
   Route::post('profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
   Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
   Route::post('profile/update-social', [ProfileController::class, 'updateSocial'])->name('profile.update-social');

   /** Enroll Courses Routes */
   Route::get('enrolled-courses', [EnrolledCourseController::class, 'index'])->name('enrolled-courses.index');
   Route::get('course-player/{slug}', [EnrolledCourseController::class, 'payerIndex'])->name('course-player.index');
   Route::get('get-lesson-content', [EnrolledCourseController::class, 'getLessonContent'])->name('get-lesson-content');
   Route::post('update-watch-history', [EnrolledCourseController::class, 'updateWatchHistory'])->name('update-watch-history');
   Route::post('update-lesson-completion', [EnrolledCourseController::class, 'updateLessonCompletion'])->name('update-lesson-completion');
   Route::get('file-download/{id}', [EnrolledCourseController::class, 'fileDownload'])->name('file-download');

   /** Certificate Routes */
   Route::get('certificate/{course}/download', [CertificateController::class, 'download'])->name('certificate.download');

   /** Review Routes */
   Route::get('review', [StudentDashboardController::class, 'review'])->name('review.index');
   Route::delete('review/{id}', [StudentDashboardController::class, 'reviewDestroy'])->name('review.destroy');

   Route::get('orders', [StudentOrderController::class, 'index'])->name('orders.index');
   Route::get('orders/{order}', [StudentOrderController::class, 'show'])->name('orders.show');

});




require __DIR__.'/auth.php';

require __DIR__.'/admin.php';
