<?php

use Illuminate\Http\Request;

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@authenticate');
Route::post('/register/facebook', 'SocialAuthController@redirect');

Route::group(['middleware' => 'jwt-auth'], function() {
    Route::get('/quiz/{id}/questions', 'QuizController@getQuestions');
    Route::post('/quiz/grade', 'QuizController@gradeQuiz');

    Route::group(['middleware' => 'check-role','roles' => ['ADMIN']], function() {
       Route::post('/quiz/create', 'QuizController@createQuiz');
    });
});