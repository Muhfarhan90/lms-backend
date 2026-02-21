<?php

namespace App\Providers;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Repositories\EnrollmentRepositoryInterface;
use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\CertificateServiceInterface;
use App\Contracts\Services\CourseServiceInterface;
use App\Contracts\Services\EnrollmentServiceInterface;
use App\Contracts\Services\NotificationServiceInterface;
use App\Contracts\Services\QuizServiceInterface;
use App\Contracts\Services\TransactionServiceInterface;
use App\Repositories\CourseRepository;
use App\Repositories\EnrollmentRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\CertificateService;
use App\Services\CourseService;
use App\Services\EnrollmentService;
use App\Services\NotificationService;
use App\Services\QuizService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(CourseRepositoryInterface::class,      CourseRepository::class);
        $this->app->bind(UserRepositoryInterface::class,        UserRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class,  EnrollmentRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);

        // Services
        $this->app->bind(AuthServiceInterface::class,         AuthService::class);
        $this->app->bind(CourseServiceInterface::class,       CourseService::class);
        $this->app->bind(EnrollmentServiceInterface::class,   EnrollmentService::class);
        $this->app->bind(QuizServiceInterface::class,         QuizService::class);
        $this->app->bind(TransactionServiceInterface::class,  TransactionService::class);
        $this->app->bind(CertificateServiceInterface::class,  CertificateService::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
    }

    public function boot(): void
    {
        //
    }
}
