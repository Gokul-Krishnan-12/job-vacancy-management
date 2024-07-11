<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'vacancies'])->name('home'); // Redirect / to vacancies page

Route::get('/jobs', [WebsiteController::class, 'jobs'])->name('jobs.index');
Route::get('/vacancies', [WebsiteController::class, 'vacancies'])->name('vacancies.index');
Route::get('/weekly-calendar', [WebsiteController::class, 'weeklyCalendar'])->name('weekly.calendar');
Route::get('/users', [WebsiteController::class, 'users'])->name('users.index');
