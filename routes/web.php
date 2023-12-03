<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MenuController; 
use App\Http\Controllers\PaymentSuccessController; 

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
    return view('login');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::get('/transaction', [TransactionController::class, 'showTransactionPage'])->name('transaction')->middleware('auth');
Route::post('/transaction/pay', [TransactionController::class, 'payButton'])->name('payButton')->middleware('auth');
Route::post('/transaction/add', [TransactionController::class, 'addToOrderList'])->name('addToOrderList')->middleware('auth');
Route::post('/submitButton', [TransactionController::class, 'submitButton'])->name('submitButton')->middleware('auth');
Route::post('/addToSession', [TransactionController::class, 'addToSession'])->name('addToSession')->middleware('auth');
Route::post('/directToPayment', [TransactionController::class, 'directToPayment'])->name('directToPayment')->middleware('auth');
// Route::get('/transaction', [TransactionController::class, 'index']);


// Route::get('/menu', [MenuController::class, 'index']);

Route::get('/orderList', [OrderListController::class, 'showOrderListPage'])->name('orderList')->middleware('auth');
Route::post('/orderList/addNewOrder', [OrderListController::class, 'addNewOrder'])->name('addNewOrder')->middleware('auth');
Route::post('/orderList/dltProduct', [OrderListController::class, 'dltProduct'])->name('dltProduct')->middleware('auth');
Route::post('/orderList/dltOrder', [OrderListController::class, 'dltOrder'])->name('dltOrder')->middleware('auth');
Route::get('/orderList/toPayment', [OrderListController::class, 'toPayment'])->name('toPayment')->middleware('auth');

Route::get('/report',[ReportController::class, 'showReportPage'])->name('report')->middleware('auth');
Route::get('/generateReport',[ReportController::class, 'generateReport'])->name('generateReport')->middleware('auth');
Route::post('/fetchReport',[ReportController::class, 'fetchReport'])->name('fetchReport')->middleware('auth');

Route::get('/payment',[PaymentController::class, 'showPaymentPage'])->name('payment')->middleware('auth');
Route::post('/payment', [PaymentController::class, 'paymentMethod'])->name('paymentMethod')->middleware('auth');
Route::post('/confirmPayment', [PaymentController::class, 'confirmPayment'])->name('confirmPayment')->middleware('auth');

Route::get('/paymentSuccess', [PaymentSuccessController::class, 'showPaymentSuccessPage'])->name('paymentSuccess')->middleware('auth');
Route::get('/printBill', [PaymentSuccessController::class, 'printBill'])->name('printBill')->middleware('auth');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/generateBill', [PaymentSuccessController::class, 'generateBill'])->name('generateBill')->middleware('auth');
Route::get('/clearOrder', [PaymentSuccessController::class, 'clearOrder'])->name('clearOrder')->middleware('auth');









