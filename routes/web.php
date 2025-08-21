<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLoginController;
use App\Models\SubscriptionPlan;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('/about', [HomeController::class, 'about'])->name('about');
  Route::get('/blog', [HomeController::class, 'blog'])->name('blog');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);

    Route::resource('courses', CourseController::class);
    Route::post('/courses/{course}/approve', [CourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/archive', [CourseController::class, 'archive'])->name('courses.archive');

    Route::resource('plans', SubscriptionPlanController::class);
});

// Instructor Routes
Route::prefix('instructor')->middleware(['auth', 'role:Instructor'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Welcome Instructor';
    });
});

// Learner Routes
Route::prefix('learner')->middleware(['auth', 'role:Learner'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Welcome Learner';
    });
});

Route::get('login/{provider}', [SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('login/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');

require __DIR__ . '/auth.php';
