<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PropostaController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MrrController;
use App\Http\Controllers\FinanceiroController;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index');

Route::prefix('leads')->name('leads.')->group(function () {
    Route::get('/', [LeadController::class, 'index'])->name('index');
    Route::get('/create', [LeadController::class, 'create'])->name('create');
    Route::post('/', [LeadController::class, 'store'])->name('store');
});

Route::resource('propostas', PropostaController::class);
Route::resource('contratos', ContratoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('mrr', MrrController::class);
Route::resource('financeiro', FinanceiroController::class);
