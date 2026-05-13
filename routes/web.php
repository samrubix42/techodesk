<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::dashboard')->name('dashboard');
Route::livewire('/client','pages::client')->name('clients');
Route::livewire('settings', 'pages::setting')->name('settings');   
Route::livewire('/service', 'pages::service')->name('services');
