<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Licence_count;
use App\Http\Controllers\Tags;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::any('/manage-company', [HomeController::class, 'manage_company'])->name('managecompany');
Route::get('/company-access', [HomeController::class, 'company_access'])->name('companyaccess');
Route::get('/company-view', [HomeController::class, 'company_view'])->name('companyview');
Route::get('/connections', [HomeController::class, 'connections'])->name('connections');
Route::any('/user-activity-report', [HomeController::class, 'user_activity_report'])->name('useractivityreport');
Route::any('/device-report', [HomeController::class, 'device_report'])->name('devicereport');
Route::get('/prepatch-report', [HomeController::class, 'prepatch_report'])->name('prepatchreport');
Route::any('/data-extract', [HomeController::class, 'data_extract'])->name('dataextract');
Route::get('/run-cron-job', [HomeController::class, 'cron_job'])->name('runcronjob');
Route::get('/run-company-cron', [HomeController::class, 'cron_job_company'])->name('cronjobcompany');
Route::get('/run-org-cron', [HomeController::class, 'organisation_in_company'])->name('organisationincompany');
Route::get('/run-device-cron', [HomeController::class, 'devices_in_organisation'])->name('devicesinorganisation');
Route::any('/manage-user', [HomeController::class, 'manage_user'])->name('manageuser');
Route::get('/manage-user/{user_id}', [HomeController::class, 'manage_user_edit'])->name('manageuseredit');
Route::any('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/error', [HomeController::class, 'error_view'])->name('errorview');
Route::get('/group-details', [HomeController::class, 'group_details'])->name('groupdetails');
Route::get('/device-information', [HomeController::class, 'device_information'])->name('deviceinformation');
Route::get('/needs-attention-report', [HomeController::class, 'needs_attention_report'])->name('needsattentionreport');
Route::get('/manual-approvals', [HomeController::class, 'manual_approvals'])->name('manualapprovals');
Route::get('/policies', [HomeController::class, 'policies'])->name('policies');
Route::get('/user-login-details', [HomeController::class, 'user_login_details'])->name('userlogindetails');
Route::get('/user-policy-report', [HomeController::class, 'user_policy_report'])->name('userpolicyreport');
Route::get('/added-devices', [HomeController::class, 'added_devices'])->name('addeddevices');
Route::get('/activity-logs', [HomeController::class, 'activity_logs'])->name('activity-logs');
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/custom-logout', [LoginController::class, 'myLogout'])->name('custom.logout');
Route::get('Licence-count', [Licence_count::class, 'index'])->name('dashboard');
Route::get('/tagsremoval', [HomeController::class, 'tagsremoval'])->name('tagsremoval');
Route::get('/tags', [Tags::class, 'index'])->name('dashboard');
Route::get('/forgot-password', function () {
    // return view('auth.passwords.email');
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');

Route::get('routes', function() {
    \Artisan::call('route:list');
    return \Artisan::output();
});

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Auth::routes();