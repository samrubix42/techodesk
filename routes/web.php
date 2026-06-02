<?php

use App\Http\Controllers\InvoicePdfController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::livewire('/', 'pages::dashboard')->name('dashboard');
    Route::livewire('/client','pages::client')->name('clients');
    Route::livewire('settings', 'pages::setting')->name('settings');   
    Route::livewire('/service', 'pages::service')->name('services');
    Route::livewire('/invoiceing', 'pages::invoiceing')->name('invoiceing');
    Route::livewire('/invoice-list', 'pages::invoice-list')->name('invoice-list');
    Route::livewire('/invoice-list/proforma', 'pages::invoice-list', ['type' => 'proforma'])->name('invoice-list.proforma');
    Route::livewire('/invoice-list/tax', 'pages::invoice-list', ['type' => 'general'])->name('invoice-list.general');
    Route::livewire('/edit-proforma-invoice/{invoice}', 'pages::edit-proforma-invoice')->name('invoice.proforma.edit');
    Route::livewire('/edit-tax-invoice/{invoice}', 'pages::edit-tax-invoice')->name('invoice.tax.edit');

    Route::get('/invoice-pdf/{type}/{invoice}', [InvoicePdfController::class, 'download'])->name('invoice.pdf');
    Route::get('/invoice-preview/{type}/{invoice}', [InvoicePdfController::class, 'preview'])->name('invoice.preview');

    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->middleware('guest');
