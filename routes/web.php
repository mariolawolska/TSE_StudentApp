<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    if (env('APP_ENV') == 'local') {
    return view('welcome');
    } else {
        return redirect(env('WORDPRESS_URL'),);
    }
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('test', ['uses' => 'App\Http\Controllers\TestController@index']);

Route::middleware(['auth:sanctum', 'verified'])->get('hesa/aggregate/{collection}', ['uses' => 'App\Http\Controllers\HesaController@aggregate']); // ->name('hesa.xml');
Route::middleware(['auth:sanctum', 'verified'])->get('hesa/staff/{collection}', ['uses' => 'App\Http\Controllers\HesaController@staff']); // ->name('hesa.xml');
//Route::middleware(['auth:sanctum', 'verified'])->get('hesa/student/{collection}', ['uses' => 'App\Http\Controllers\HesaController@student']); // ->name('hesa.xml');
Route::middleware(['auth:sanctum', 'verified'])->get('hesa/studentalternative/{collection}', ['uses' => 'App\Http\Controllers\HesaController@studentalternative']); // ->name('hesa.xml');

Route::get('/student/application/{token}', 'App\Http\Controllers\StudentApplicationsController@studentApplicationStart')->name('studentApplicationStart');
Route::get('/student/application/', 'App\Http\Controllers\StudentApplicationsController@studentApplication')->name('studentApplication');

Route::post('/saveStudentApplication', 'App\Http\Controllers\StudentApplicationsController@saveStudentApplication')->name('saveStudentApplication');

Route::match(['get', 'post'],'/checkCurrentCountryIsUK', 'App\Http\Controllers\StudentApplicationsController@checkCurrentCountryIsUK')->name('checkCurrentCountryIsUK');
Route::match(['get', 'post'],'/checkStudentHasDisabilities', 'App\Http\Controllers\StudentApplicationsController@checkStudentHasDisabilities')->name('checkStudentHasDisabilities');

Route::match(['get', 'post'], '/formPart/{flow?}', 'App\Http\Controllers\FormPartController@formPart')->name('formPart');
Route::match(['get', 'post'], '/takeStep/{no?}', 'App\Http\Controllers\FormPartController@takeStep')->name('takeStep');
Route::match(['get', 'post'], '/confirmSaveForm/{flow?}', 'App\Http\Controllers\StudentApplicationsController@confirmSaveForm')->name('confirmSaveForm');

Route::match(['get', 'post'], '/student_applications_qualifications/saveQualifications', 'App\Http\Controllers\StudentApplicationsQualificationsController@saveQualifications')->name('student_applications_qualifications.saveQualifications');
Route::match(['get', 'post'], '/student_applications_qualifications/deleteQualification', 'App\Http\Controllers\StudentApplicationsQualificationsController@deleteQualification')->name('student_applications_qualifications.deleteQualification');
Route::match(['get', 'post'], '/student_applications_qualifications/showMoreQualification', 'App\Http\Controllers\StudentApplicationsQualificationsController@showMoreQualification')->name('student_applications_qualifications.showMoreQualification');
Route::match(['get', 'post'], '/student_applications_qualifications/checkStudentHasQualifications', 'App\Http\Controllers\StudentApplicationsQualificationsController@checkStudentHasQualifications')->name('student_applications_qualifications.checkStudentHasQualifications');

Route::match(['get', 'post'], '/student_applications_employment/add_data', 'App\Http\Controllers\StudentApplicationsEmploymentController@add_data')->name('student_applications_employment.add_data');
Route::match(['get', 'post'], '/student_applications_employment/delete_data', 'App\Http\Controllers\StudentApplicationsEmploymentController@delete_data')->name('student_applications_employment.delete_data');
