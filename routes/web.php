<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionnaireController;

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
    return inertia('Index');
});

Route::get('verify-otp' , function(){
    return Inertia::render('User/VerifyOTP');
})->name('verify-otp');
Route::post('verify-otp' , [UserController::class , 'verifyOTP']);


Route::get('/questionnaire/' , [QuestionnaireController::class, 'index'])->name('questinnaire.index');
Route::put('/questionnaire/update/{id}' , [QuestionnaireController::class, 'update'])->name('questinnaire.update');
Route::delete('/questionnaire/delete/{id}' , [QuestionnaireController::class, 'destroy'])->name('questinnaire.destroy');
Route::post('/questionnaire/submit-answers', [QuestionnaireController::class, 'store'])->name('questinnaire.submitAnswers');

Route::prefix('user')->middleware(['session-verification'])->name('user.')->group(function(){
    Route::get('/' , [UserController::class, 'index'])->name('index');
    
    Route::get('answers' , [UserController::class, 'answers'])->name('answers');
    
    Route::get('charts' , [UserController::class, 'showCharts'])->name('showCharts');
    Route::get('get-chart-data' , [UserController::class, 'getChartData'])->name('getChartData');
    Route::get('zipcodes' , [UserController::class, 'showZipCodes'])->name('showZipCodes');
    Route::get('get-zipcodes' , [UserController::class, 'getZipCodes'])->name('getZipCodes');
});
