<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Student\PortalController as StudentPortalController;
use App\Http\Controllers\Teacher\PortalController as TeacherPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuestController::class, 'home'])->name('home');
Route::get('/khoa-hoc/{course}', [GuestController::class, 'course'])->name('course.show');
Route::post('/contact', [GuestController::class, 'contact'])->name('contact.submit');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admins', [AdminAccountController::class, 'index'])->name('admins.index');
    Route::post('/admins', [AdminAccountController::class, 'store'])->name('admins.store');
    Route::put('/admins/{admin}', [AdminAccountController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminAccountController::class, 'destroy'])->name('admins.destroy');

    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

    Route::get('/classes', [AdminClassController::class, 'index'])->name('classes.index');
    Route::post('/classes', [AdminClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{class}', [AdminClassController::class, 'show'])->name('classes.show');
    Route::put('/classes/{class}', [AdminClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{class}', [AdminClassController::class, 'destroy'])->name('classes.destroy');
    Route::post('/classes/{class}/students', [AdminClassController::class, 'addStudent'])->name('classes.students.add');
    Route::delete('/classes/{class}/students/{student}', [AdminClassController::class, 'removeStudent'])->name('classes.students.remove');
    Route::post('/classes/{class}/enrollments/{student}/approve', [AdminClassController::class, 'approveEnrollment'])->name('classes.enrollments.approve');
    Route::post('/classes/{class}/enrollments/{student}/reject', [AdminClassController::class, 'rejectEnrollment'])->name('classes.enrollments.reject');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    Route::post('/teachers/{teacher}/salary', [TeacherController::class, 'paySalary'])->name('teachers.salary');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::put('/gallery/{artwork}', [GalleryController::class, 'review'])->name('gallery.review');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/invoices', [FinanceController::class, 'storeInvoice'])->name('finance.invoice.store');
    Route::post('/finance/invoices/{invoice}/confirm', [FinanceController::class, 'confirmInvoice'])->name('finance.invoice.confirm');
    Route::post('/finance/invoices/{invoice}/reject', [FinanceController::class, 'rejectPayment'])->name('finance.invoice.reject');
    Route::post('/finance/salaries/{salary}/confirm', [FinanceController::class, 'confirmSalary'])->name('finance.salary.confirm');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{message}/toggle', [MessageController::class, 'markRead'])->name('messages.toggle');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth', 'role:TEACHER'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/account', [TeacherPortalController::class, 'account'])->name('account');
    Route::put('/account', [TeacherPortalController::class, 'updateAccount'])->name('account.update');
    Route::post('/gallery', [TeacherPortalController::class, 'storeArtwork'])->name('gallery.store');
    Route::delete('/gallery/{artwork}', [TeacherPortalController::class, 'deleteArtwork'])->name('gallery.destroy');
    Route::get('/classes', [TeacherPortalController::class, 'classes'])->name('classes');
    Route::get('/classes/{class}', [TeacherPortalController::class, 'classShow'])->name('classes.show');
    Route::post('/classes/{class}/attendance', [TeacherPortalController::class, 'attendance'])->name('classes.attendance');
    Route::post('/classes/{class}/enrollments/{student}/approve', [TeacherPortalController::class, 'approveEnrollment'])->name('classes.enrollments.approve');
    Route::post('/classes/{class}/enrollments/{student}/reject', [TeacherPortalController::class, 'rejectEnrollment'])->name('classes.enrollments.reject');
    Route::get('/gallery', [TeacherPortalController::class, 'galleryIndex'])->name('gallery.index');
    Route::post('/artworks/{artwork}/approve', [TeacherPortalController::class, 'approveArtwork'])->name('artworks.approve');
    Route::post('/artworks/{artwork}/comment', [TeacherPortalController::class, 'commentArtwork'])->name('artworks.comment');
    Route::get('/salary', [TeacherPortalController::class, 'salary'])->name('salary');
});

Route::middleware(['auth', 'role:STUDENT'])->prefix('student')->name('student.')->group(function () {
    Route::get('/account', [StudentPortalController::class, 'account'])->name('account');
    Route::put('/account', [StudentPortalController::class, 'updateAccount'])->name('account.update');
    Route::post('/gallery', [StudentPortalController::class, 'storeArtwork'])->name('gallery.store');
    Route::get('/classes', [StudentPortalController::class, 'classes'])->name('classes');
    Route::get('/enroll', [StudentPortalController::class, 'catalog'])->name('enroll.index');
    Route::post('/classes/{class}/enroll', [StudentPortalController::class, 'requestEnrollment'])->name('classes.enroll');
    Route::delete('/classes/{class}/enroll', [StudentPortalController::class, 'cancelEnrollment'])->name('classes.cancel');
    Route::post('/invoices/{invoice}/pay', [StudentPortalController::class, 'requestPayment'])->name('invoices.pay');
    Route::post('/classes/{class}/leave', [StudentPortalController::class, 'requestLeave'])->name('classes.leave');
});
