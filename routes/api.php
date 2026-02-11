<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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


Route::get('category/get', [CategoryController::class , 'getAllCategories']);
Route::get('category/specific' , [CategoryController::class , 'getSpecifecCategory']);
Route::post('category/add', [CategoryController::class , 'addCategory']);
Route::get('category/get/{id}', [CategoryController::class , 'getCategory']);
Route::post('category/update', [CategoryController::class , 'updateCategory']);
Route::delete('category/delete', [CategoryController::class , 'deleteCategory']);

Route::post('/question/add', [QuestionController::class , 'addQuestion']);
Route::get('/question/get/{id}', [QuestionController::class , 'getQuestionsOfCategory']);
Route::post('/question/update', [QuestionController::class , 'updateQuestion']);
Route::delete('/question/delete', [QuestionController::class , 'deleteQuestion']);


Route::post('/answer/add', [AnswerController::class , 'addAnswer']);
Route::get('/answer/get/{id}', [AnswerController::class , 'getAnswers']);
Route::post('/answer/update', [AnswerController::class , 'updateAnswer']);
Route::delete('/answer/delete', [AnswerController::class , 'deleteAnswer']);



Route::post('/battle/start', [BattleController::class, 'startBattle']);


