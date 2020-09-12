<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('contact-us','ContactUsController@handleForm');

Route::post('checkemail', 'UserController@checkEmail');
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::get('/certificates', 'CertificateController@index');
Route::post('/upload-file', 'CertificateController@uploadFile');
Route::get('/certificates/{certificate}', 'CertificateController@show');
// Route::post('/forgot_password', 'UserController@forgot_password');

Route::post('/user/verify', 'UserController@verifyUser');


// Send reset password mail
Route::post('reset-password', 'Auth\AuthController@sendPasswordResetLink');
// handle reset password form process
Route::post('reset/password', 'Auth\AuthController@callResetPassword');
Route::resource('/cart', 'CartController');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/coupons', 'CouponController@store');
    Route::delete('/coupons', 'CouponController@destroy');
    Route::post('change_password', 'UserController@change_password');
    Route::get('/users','UserController@index');
    Route::get('users/{user}','UserController@show');
    Route::patch('users/{user}','UserController@update');
    Route::get('users/{user}/orders','UserController@showOrders');
    Route::post('users/paginate','UserController@paginate');

    Route::put('orders/{order}/finish','OrderController@finishOrder');
    Route::post('orders/canorder','OrderController@canOrder');
    Route::resource('/certificates', 'CertificateController')->except(['index','show']);
    Route::post('certificates/paginate','CertificateController@paginate');
    Route::post('certificates/bookedno','CertificateController@updatebookedNO');
    Route::resource('/orders', 'OrderController');
    Route::post('/orders/adminstore', 'OrderController@adminStore');
    Route::post('orders/paginate','OrderController@paginate');
    Route::resource('/exams', 'ExamController');
    Route::post('exams/paginate','ExamController@paginate');
    Route::resource('/questions', 'QuestionController');
    Route::post('questions/paginate','QuestionController@paginate');
    Route::post('/questions/qoptions', 'QuestionController@qOptions');
    Route::post('/questions/qexams', 'QuestionController@qExams');
    Route::post('/questions/nqexams', 'QuestionController@nqExams');
    Route::post('/questions/unjoinexam', 'QuestionController@unjoinExam');
    Route::post('/questions/joinexam', 'QuestionController@joinExam');
    Route::post('/questions/updatepivot', 'QuestionController@updatePivot');
    Route::post('/exams/equestions', 'ExamController@eQuestions');
    Route::post('/exams/nequestions', 'ExamController@neQuestions');
    Route::post('/exams/unjoinquestion', 'ExamController@unjoinQuestion');
    Route::post('/exams/joinquestion', 'ExamController@joinQuestion');
    Route::post('/certificates/cexams', 'CertificateController@cExams');
    Route::post('/certificates/ncexams', 'CertificateController@ncExams');
    Route::post('/certificates/unjoinexam', 'CertificateController@unjoinExam');
    Route::post('/certificates/joinexam', 'CertificateController@joinExam');
    Route::resource('/options', 'OptionController');
    Route::post('/options/updatesequences', 'OptionController@updateSequences');
    Route::resource('/answers', 'AnswerController');
    Route::resource('/sessions/', 'SessionController');
    Route::post('/sessions/csessions', 'SessionController@cSessions');
    Route::post('sessions/paginate','SessionController@paginate');

});