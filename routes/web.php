<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImportQuestionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [CategoryController::class , 'categoryPage'])->name('category')->middleware('auth'); // Main Category

Route::get('category/addCategoryView', [CategoryController::class , 'addCategoryView'])->name('addCategoryView')->middleware('auth');
Route::post('category/addCategoryFun', [CategoryController::class , 'addCategoryFun'])->name('addCategoryFun')->middleware('auth');

Route::post('category/updateCategoryView', [CategoryController::class , 'updateCategoryView'])->name('updateCategoryView')->middleware('auth');
Route::post('category/updateCategoryFun', [CategoryController::class , 'updateCategoryFun'])->name('updateCategoryFun')->middleware('auth');

Route::post('category/delete', [CategoryController::class , 'deleteCategory']);
Route::post('category/deleteView', [CategoryController::class , 'deleteCategoryView'])->name('deleteCategory')->middleware('auth');




Route::get('question', [QuestionController::class , 'questionPage'])->name('question')->middleware('auth');

Route::post('question/addQuestionView', [QuestionController::class , 'addQuestionView'])->name('addQuestionView')->middleware('auth');
Route::post('question/addQuestionFun', [QuestionController::class , 'addQuestionFun'])->name('addQuestionFun')->middleware('auth');

Route::post('question/updateQuestionView', [QuestionController::class , 'updateQuestionView'])->name('updateQuestionView')->middleware('auth');
Route::post('question/updateQuestionFun', [QuestionController::class , 'updateQuestionFun'])->name('updateQuestionFun')->middleware('auth');

Route::post('question/delete', [QuestionController::class , 'deleteQuestionView'])->name('deleteQuestion')->middleware('auth');;




Route::post('answer', [AnswerController::class , 'answerPage'])->name('answer')->middleware('auth');

Route::post('answer/addAnswerView', [AnswerController::class , 'addAnswerView'])->name('addAnswerView')->middleware('auth');
Route::post('answer/addAnswerFun', [AnswerController::class , 'addAnswerFun'])->name('addAnswerFun')->middleware('auth');

Route::get('answer/updateAnswerView', [AnswerController::class , 'updateAnswerView'])->name('updateAnswerView')->middleware('auth');
Route::post('answer/updateAnswerFun', [AnswerController::class , 'updateAnswerFun'])->name('updateAnswerFun')->middleware('auth');

Route::post('answer/delete', [AnswerController::class , 'deleteAnswerView'])->name('deleteAnswer')->middleware('auth');



Route::get('notification', [NotificationController::class , 'nofication_page'])->name('notification')->middleware('auth');;

Route::post('notification/addNotificationView', [NotificationController::class , 'addNotificationView'])->name('addNotificationView')->middleware('auth');;
Route::post('notification/addNotificationFun', [NotificationController::class , 'addNotification'])->name('addNotificationFun')->middleware('auth');;

Route::post('notification/updateNotificationView', [NotificationController::class , 'updateNotificationView'])->name('updateNotificationView')->middleware('auth');;
Route::post('notification/updateNotificationFun', [NotificationController::class , 'updateNotification'])->name('updateNotificationFun')->middleware('auth');;

Route::post('notification/delete', [NotificationController::class , 'deleteNotification'])->name('deleteNotification')->middleware('auth');;

Route::post('/import-questions', [ImportQuestionController::class, 'import'])->name('questions.import');

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);
