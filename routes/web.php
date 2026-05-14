<?php

use App\Http\Controllers\InvoicePdfController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::dashboard')->name('dashboard');
Route::livewire('/client','pages::client')->name('clients');
Route::livewire('settings', 'pages::setting')->name('settings');   
Route::livewire('/service', 'pages::service')->name('services');
Route::livewire('/invoiceing', 'pages::invoiceing')->name('invoiceing');
Route::livewire('/invoiceing/proforma', 'pages::invoiceing', ['type' => 'proforma'])->name('invoiceing.proforma');
Route::livewire('/invoiceing/general', 'pages::invoiceing', ['type' => 'general'])->name('invoiceing.general');
Route::livewire('/invoice-list', 'pages::invoice-list')->name('invoice-list');
Route::livewire('/invoice-list/proforma', 'pages::invoice-list', ['type' => 'proforma'])->name('invoice-list.proforma');
Route::livewire('/invoice-list/general', 'pages::invoice-list', ['type' => 'general'])->name('invoice-list.general');

Route::get('/invoice-pdf/{type}/{invoice}', [InvoicePdfController::class, 'download'])->name('invoice.pdf');
