<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TutorWizardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Home Landing Page Route
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/template/download/{type}', [LandingController::class, 'downloadTemplate'])->name('template.download');

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/register/success', [AuthController::class, 'registerSuccess'])->name('register.success');
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');
});

// Logged In Users
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    
    // Candidate Tutor Routes
    Route::middleware('role:tutor')->prefix('tutor')->name('tutor.')->group(function () {
        Route::get('/dashboard', [TutorWizardController::class, 'dashboard'])->name('dashboard');
        Route::get('/wizard', [TutorWizardController::class, 'showWizard'])->name('wizard');
        Route::post('/wizard/step1', [TutorWizardController::class, 'saveStep1'])->name('wizard.step1');
        Route::post('/wizard/step2', [TutorWizardController::class, 'saveStep2'])->name('wizard.step2');
        Route::post('/wizard/step3', [TutorWizardController::class, 'saveStep3'])->name('wizard.step3');
        Route::post('/wizard/step4', [TutorWizardController::class, 'saveStep4'])->name('wizard.step4');
        Route::post('/wizard/step5', [TutorWizardController::class, 'saveStep5'])->name('wizard.step5');
        Route::get('/courses', [TutorWizardController::class, 'showCourses'])->name('courses');
        Route::post('/courses', [TutorWizardController::class, 'saveCourses'])->name('save-courses');
        Route::post('/submit', [TutorWizardController::class, 'submit'])->name('submit');
        Route::get('/password', [TutorWizardController::class, 'showPassword'])->name('password');
    });

    // Admin & Super Admin Routes
    Route::middleware('role:admin,super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Candidate Tutor Review
        Route::get('/tutors', [AdminController::class, 'tutorIndex'])->name('tutors.index');
        Route::get('/tutors/create', [AdminController::class, 'tutorCreate'])->middleware('role:super_admin')->name('tutors.create');
        Route::get('/tutors/{id}', [AdminController::class, 'tutorShow'])->name('tutors.show');
        Route::post('/tutors/{id}/approve', [AdminController::class, 'tutorApprove'])->name('tutors.approve');
        Route::post('/tutors/{id}/reject', [AdminController::class, 'tutorReject'])->name('tutors.reject');
        
        // Master Data CRUD (Both Admin and Super Admin can manage faculties, programs, and courses)
        // Note: Delete action will be protected inside the controller methods for super_admin
        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('faculties', FacultyController::class)->except(['create', 'show', 'edit']);
            Route::resource('programs', ProgramController::class)->except(['create', 'show', 'edit']);
            Route::resource('courses', CourseController::class)->except(['create', 'show', 'edit']);
        });

        // Super Admin Only exclusive routes
        Route::middleware('role:super_admin')->group(function () {
            Route::resource('users', AdminController::class)->only(['index', 'store', 'update', 'destroy']);
            Route::post('/tutors', [AdminController::class, 'tutorStore'])->name('tutors.store');
            Route::get('/tutors/{id}/edit', [AdminController::class, 'tutorEdit'])->name('tutors.edit');
            Route::put('/tutors/{id}', [AdminController::class, 'tutorUpdate'])->name('tutors.update');
            Route::delete('/tutors/{id}', [AdminController::class, 'tutorDestroy'])->name('tutors.destroy');
            Route::resource('master/periods', PeriodController::class)->except(['create', 'show', 'edit'])->names([
                'index' => 'master.periods.index',
                'store' => 'master.periods.store',
                'update' => 'master.periods.update',
                'destroy' => 'master.periods.destroy',
            ]);
        });
    });
});
