<?php

use App\Http\Controllers\Api\Admin\ActivityLogController;
use App\Http\Controllers\Api\Admin\PageController as AdminPageController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\ForumPostController;
use App\Http\Controllers\Api\ForumReplyController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QuizAttemptController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login',    [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

// Public read-only routes
Route::get('categories',          [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('courses',             [CourseController::class, 'index'])->name('courses.index');
Route::get('courses/{course}',    [CourseController::class, 'show'])->name('courses.show');
Route::get('pages/{page}',        [AdminPageController::class, 'show'])->name('pages.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',               [ProfileController::class, 'show'])->name('show');
        Route::put('/',               [ProfileController::class, 'update'])->name('update');
        Route::put('change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    });

    // Enrollments
    Route::apiResource('enrollments', EnrollmentController::class)->only(['index', 'store', 'show']);

    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/',                          [TransactionController::class, 'index'])->name('index');
        Route::post('/',                         [TransactionController::class, 'store'])->name('store');
        Route::get('{transaction}',              [TransactionController::class, 'show'])->name('show');
        Route::post('{transaction}/upload-proof', [TransactionController::class, 'uploadProof'])->name('upload-proof');
    });

    // Voucher Check
    Route::post('vouchers/check', [VoucherController::class, 'check'])->name('vouchers.check');

    // Reviews
    Route::get('courses/{course}/reviews',        [ReviewController::class, 'index'])->name('courses.reviews.index');
    Route::post('reviews',                        [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('reviews/{review}',                [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}',             [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Certificates
    Route::get('certificates',        [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',            [NotificationController::class, 'index'])->name('index');
        Route::put('{id}/read',    [NotificationController::class, 'markRead'])->name('mark-read');
        Route::put('read-all',     [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });

    // Quiz Attempts
    Route::prefix('quiz-attempts')->name('quiz-attempts.')->group(function () {
        Route::post('/',                      [QuizAttemptController::class, 'store'])->name('store');
        Route::get('{attempt}',               [QuizAttemptController::class, 'show'])->name('show');
        Route::post('{attempt}/submit',       [QuizAttemptController::class, 'submit'])->name('submit');
    });

    /*
    |--------------------------------------------------------------------------
    | Course Content Routes (Enrolled Students + Instructors + Admins)
    |--------------------------------------------------------------------------
    */
    Route::prefix('courses/{course}')->name('courses.')->group(function () {

        // Sections
        Route::get('sections',                          [SectionController::class, 'index'])->name('sections.index');
        Route::post('sections',                         [SectionController::class, 'store'])
            ->middleware('role:admin,instructor')->name('sections.store');
        Route::put('sections/{section}',                [SectionController::class, 'update'])
            ->middleware('role:admin,instructor')->name('sections.update');
        Route::delete('sections/{section}',             [SectionController::class, 'destroy'])
            ->middleware('role:admin,instructor')->name('sections.destroy');

        // Lessons
        Route::get('sections/{section}/lessons',                       [LessonController::class, 'index'])->name('lessons.index');
        Route::get('sections/{section}/lessons/{lesson}',              [LessonController::class, 'show'])
            ->middleware('enrolled')->name('lessons.show');
        Route::post('sections/{section}/lessons',                      [LessonController::class, 'store'])
            ->middleware('role:admin,instructor')->name('lessons.store');
        Route::put('sections/{section}/lessons/{lesson}',              [LessonController::class, 'update'])
            ->middleware('role:admin,instructor')->name('lessons.update');
        Route::delete('sections/{section}/lessons/{lesson}',           [LessonController::class, 'destroy'])
            ->middleware('role:admin,instructor')->name('lessons.destroy');
        Route::post('sections/{section}/lessons/{lesson}/complete',    [LessonController::class, 'markComplete'])
            ->middleware('enrolled')->name('lessons.complete');

        // Forum Posts
        Route::get('forum',                         [ForumPostController::class, 'index'])
            ->middleware('enrolled')->name('forum.index');
        Route::post('forum',                        [ForumPostController::class, 'store'])
            ->middleware('enrolled')->name('forum.store');
        Route::get('forum/{post}',                  [ForumPostController::class, 'show'])
            ->middleware('enrolled')->name('forum.show');
        Route::put('forum/{post}',                  [ForumPostController::class, 'update'])
            ->middleware('enrolled')->name('forum.update');
        Route::delete('forum/{post}',               [ForumPostController::class, 'destroy'])
            ->middleware('enrolled')->name('forum.destroy');

        // Forum Replies
        Route::post('forum/{post}/replies',                     [ForumReplyController::class, 'store'])
            ->middleware('enrolled')->name('forum.replies.store');
        Route::put('forum/{post}/replies/{reply}',              [ForumReplyController::class, 'update'])
            ->middleware('enrolled')->name('forum.replies.update');
        Route::delete('forum/{post}/replies/{reply}',           [ForumReplyController::class, 'destroy'])
            ->middleware('enrolled')->name('forum.replies.destroy');
    });

    // Instructor routes
    Route::middleware('role:instructor,admin')->prefix('instructor')->name('instructor.')->group(function () {
        Route::apiResource('courses', CourseController::class)->only(['store', 'update', 'destroy']);
        Route::post('quiz-attempts/{attempt}/grade', [QuizAttemptController::class, 'grade'])->name('quiz-attempts.grade');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Users
        Route::apiResource('users', AdminUserController::class);
        Route::post('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');

        // Categories (write)
        Route::post('categories',                [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}',      [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}',   [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Vouchers
        Route::apiResource('vouchers', AdminVoucherController::class);

        // Transactions
        Route::get('transactions',                        [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}',          [AdminTransactionController::class, 'show'])->name('transactions.show');
        Route::post('transactions/{transaction}/verify',  [AdminTransactionController::class, 'verify'])->name('transactions.verify');

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Settings
        Route::get('settings',  [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings',  [SettingController::class, 'update'])->name('settings.update');

        // Pages
        Route::apiResource('pages', AdminPageController::class)->except(['show']);
    });
});
