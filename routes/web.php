<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\SubscriptionAssignmentController;
use App\Http\Controllers\Admin\SubscriptionReportController;
use App\Http\Controllers\Admin\VideoLibraryController as AdminVideoLibraryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLoginController;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\ParentCourseController;
use App\Http\Controllers\ParentChildController;
use App\Http\Controllers\ChildDashboardController;
use App\Http\Controllers\ChildQuizController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VideoLibraryController as SubscriberVideoLibraryController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('/program', [HomeController::class, 'program'])->name('program');
  Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
  Route::get('/about', [HomeController::class, 'about'])->name('about');
  Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
  Route::get('/membership', [HomeController::class, 'membership'])->name('membership');
  Route::get('/lesson', [HomeController::class, 'lesson'])->name('lesson');
  Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
  Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Subscription purchase routes (separate from course management)
Route::middleware('auth')->group(function () {
    Route::get('/subscriptions/me', [SubscriptionController::class, 'me'])->name('subscriptions.me');
    Route::post('/subscriptions/{plan}/checkout', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::get('/subscriptions/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
    Route::get('/subscriptions/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});

Route::middleware(['auth', 'subscribed'])->group(function () {
    Route::get('/video-library', [SubscriberVideoLibraryController::class, 'index'])->name('video-library.index');
    Route::get('/video-library/{videoLibraryItem}', [SubscriberVideoLibraryController::class, 'show'])->name('video-library.show');
});

Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);

    Route::resource('courses', CourseController::class);
    Route::post('/courses/{course}/approve', [CourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/archive', [CourseController::class, 'archive'])->name('courses.archive');

    Route::get('courses/{course}/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('courses/{course}/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('modules/{module}/quiz', [ModuleController::class, 'createQuiz'])->name('modules.quiz.create');
    Route::post('modules/{module}/quiz', [ModuleController::class, 'storeQuiz'])->name('modules.quiz.store');
    Route::get('modules/{module}/quiz/edit', [ModuleController::class, 'editQuiz'])->name('modules.quiz.edit');
    Route::put('modules/{module}/quiz', [ModuleController::class, 'updateQuiz'])->name('modules.quiz.update');
    Route::get('modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::get('modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
    Route::post('courses/{course}/modules/reorder', [ModuleController::class, 'reorder'])->name('modules.reorder');

    Route::resource('plans', SubscriptionPlanController::class);
    Route::resource('video-library', AdminVideoLibraryController::class)->names('admin.video-library');

    // Manual Subscription Assignment
    Route::get('subscriptions/assign', [SubscriptionAssignmentController::class, 'create'])->name('admin.subscriptions.assign.create');
    Route::post('subscriptions/assign', [SubscriptionAssignmentController::class, 'store'])->name('admin.subscriptions.assign.store');

    // Subscriptions reports
    Route::get('subscriptions', [SubscriptionReportController::class, 'index'])->name('admin.subscriptions.index');
    Route::get('subscriptions/stats', [SubscriptionReportController::class, 'stats'])->name('admin.subscriptions.stats');

    Route::resource('categories', CategoryController::class);
});



// Parent Routes (renamed from Learner)
Route::prefix('parent')->middleware(['auth', 'role:Parent'])->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
    Route::get('/subscriptions', [\App\Http\Controllers\ParentSubscriptionController::class, 'index'])->name('parent.subscriptions.index');

    // Parent Course Purchase & Library
    Route::get('/courses', [ParentCourseController::class, 'index'])->name('parent.courses.index');
    Route::get('/courses/summary', [ParentCourseController::class, 'summary'])->name('parent.courses.summary');
    Route::get('/courses/my', [ParentCourseController::class, 'my'])->name('parent.courses.my');
    Route::get('/courses/{course}', [ParentCourseController::class, 'show'])->name('parent.courses.show');
    Route::post('/courses/{course}/checkout', [ParentCourseController::class, 'checkout'])->name('parent.courses.checkout');
    Route::post('/courses/{course}/intent', [ParentCourseController::class, 'intent'])->name('parent.courses.intent');
    Route::post('/courses/{course}/complete', [ParentCourseController::class, 'complete'])->name('parent.courses.complete');
    Route::get('/courses/{course}/success', [ParentCourseController::class, 'success'])->name('parent.courses.success');
    Route::get('/courses/{course}/cancel', [ParentCourseController::class, 'cancel'])->name('parent.courses.cancel');

    // Parent Child Profiles
    Route::get('/children', [ParentChildController::class, 'index'])->name('parent.children.index');
    Route::post('/children', [ParentChildController::class, 'store'])->name('parent.children.store');
    Route::patch('/children/{child}', [ParentChildController::class, 'update'])->name('parent.children.update');
    Route::delete('/children/{child}', [ParentChildController::class, 'destroy'])->name('parent.children.destroy');
    Route::get('/children/exit', [ParentChildController::class, 'exit'])->name('parent.children.exit');

    // Child View (Dashboard + Course)
    Route::get('/children/{child}', [ChildDashboardController::class, 'dashboard'])->name('parent.children.dashboard');
    Route::get('/children/{child}/courses/{course}', [ChildDashboardController::class, 'course'])->name('parent.children.course');
    Route::get('/children/{child}/courses/{course}/modules/{module}', [ChildDashboardController::class, 'module'])->name('parent.children.course.module');

    // Child Quiz Routes
    Route::post('/children/{child}/courses/{course}/modules/{module}/quiz/start', [ChildQuizController::class, 'start'])->name('parent.children.quiz.start');
    Route::post('/children/{child}/courses/{course}/modules/{module}/quiz/answer', [ChildQuizController::class, 'answer'])->name('parent.children.quiz.answer');
    Route::post('/children/{child}/courses/{course}/modules/{module}/quiz/complete', [ChildQuizController::class, 'complete'])->name('parent.children.quiz.complete');

    // Analytics
    Route::get('/analytics/children', [\App\Http\Controllers\ParentAnalyticsController::class, 'overall'])->name('parent.children.analytics.overall');
    Route::get('/children/analytics/export/csv', [\App\Http\Controllers\ParentAnalyticsController::class, 'exportCsvAll'])->name('parent.children.analytics.overall.csv');
    Route::get('/children/analytics/export/pdf', [\App\Http\Controllers\ParentAnalyticsController::class, 'exportPdfAll'])->name('parent.children.analytics.overall.pdf');
    Route::get('/children/{child}/analytics', [\App\Http\Controllers\ParentAnalyticsController::class, 'show'])->name('parent.children.analytics');
    Route::get('/children/{child}/analytics/export/csv', [\App\Http\Controllers\ParentAnalyticsController::class, 'exportCsv'])->name('parent.children.analytics.csv');
    Route::get('/children/{child}/analytics/export/pdf', [\App\Http\Controllers\ParentAnalyticsController::class, 'exportPdf'])->name('parent.children.analytics.pdf');
});

Route::get('login/{provider}', [SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('login/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');

require __DIR__ . '/auth.php';

// Stripe Webhook (Laravel Cashier)
use App\Http\Controllers\Stripe\CashierWebhookController;
Route::post('/stripe/webhook', [CashierWebhookController::class, '__invoke'])->name('cashier.webhook');
