<?php

use App\Http\Controllers\ScheduledVacancyController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/scheduled-vacancies', [ScheduledVacancyController::class, 'index']);
    Route::post('/scheduled-vacancies', [ScheduledVacancyController::class, 'store']);
    Route::put('/scheduled-vacancies/{id}', [ScheduledVacancyController::class, 'update']);
    Route::delete('/scheduled-vacancies/{id}', [ScheduledVacancyController::class, 'softDeleteScheduledVacancy']);

    Route::get('/jobs', [JobController::class, 'index']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::get('/jobs/active', [JobController::class, 'activeJobs']);
    Route::put('/jobs/update-job/{job_id}', [JobController::class, 'updateJob']);
    Route::delete('/jobs/delete-job/{job_id}', [JobController::class, 'softDeleteJob']);
    Route::put('/jobs/restore/{job_id}', [JobController::class, 'restoreJob']);

    Route::get('/vacancies', [VacancyController::class, 'index']);
    Route::get('/vacancies/active', [VacancyController::class, 'activeVacancies']);
    Route::post('/vacancies/add', [VacancyController::class, 'addVacancy']);
    Route::put('/vacancies/{id}', [VacancyController::class, 'updateVacancy']);
    Route::delete('/vacancies/delete-vacancy/{id}', [VacancyController::class, 'softDeleteVacancy']);
    Route::put('/vacancies/restore/{id}', [VacancyController::class, 'restoreVacancy']);
    Route::post('/vacancies/{id}/send-mail', [VacancyController::class, 'sendMailToUsers']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/active', [UserController::class, 'activeUsers']);
});

