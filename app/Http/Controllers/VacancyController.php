<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use App\Models\Vacancy;
use App\Notifications\NewVacancyNotification;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of all vacancies (including soft deleted ones).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $vacancies = Vacancy::withTrashed()->with('job')->get();
            return response()->json($vacancies);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch vacancies', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of active vacancies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeVacancies()
    {
        try {
            $vacancies = Vacancy::where('status', 'active')->with('job')->get();
            return response()->json($vacancies);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch active vacancies', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new vacancy.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $jobs = Job::where('status', 'active')->get();
            return view('vacancies.create', compact('jobs'));
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['message' => 'Failed to fetch jobs for vacancy creation', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created vacancy resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addVacancy(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'job_id' => 'required|exists:jobs_table,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'description' => 'required|string',
            ]);

            $vacancy = Vacancy::create([
                'job_id' => $validatedData['job_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'description' => $validatedData['description'],
                'status' => 'active'
            ]);

            // Optionally, send notifications or perform other actions here

            return response()->json(['message' => 'Vacancy added successfully', 'vacancy' => $vacancy], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add vacancy', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified vacancy resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVacancy(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'job_id' => 'required|exists:jobs_table,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'description' => 'required|string',
                'status' => 'required|string'
            ]);

            $vacancy = Vacancy::findOrFail($id);

            $vacancy->job_id = $validatedData['job_id'];
            $vacancy->start_date = $validatedData['start_date'];
            $vacancy->end_date = $validatedData['end_date'];
            $vacancy->description = $validatedData['description'];
            $vacancy->status = $validatedData['status'];

            $vacancy->save();

            // Optionally, send notifications or perform other actions here

            return response()->json(['message' => 'Vacancy updated successfully', 'vacancy' => $vacancy], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update vacancy', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Soft delete the specified vacancy resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function softDeleteVacancy($id)
    {
        try {
            $vacancy = Vacancy::findOrFail($id);
            $vacancy->delete();

            return response()->json([
                'message' => 'Vacancy temporarily deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to temporarily delete Vacancy',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore the specified soft-deleted vacancy resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreVacancy($id)
    {
        try {
            $vacancy = Vacancy::withTrashed()->findOrFail($id);
            $vacancy->restore();

            return response()->json([
                'message' => 'Vacancy restored successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to restore Vacancy',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send notification emails to active users for the specified vacancy.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $vacancy_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMailToUsers(Request $request, $vacancy_id)
    {
        try {
            $activeUsers = User::where('status', 'active')->get();
            $vacancy = Vacancy::find($vacancy_id);

            foreach ($activeUsers as $user) {
                $user->notify(new NewVacancyNotification($vacancy));
            }

            return response()->json(['message' => 'Emails sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send notification emails', 'error' => $e->getMessage()], 500);
        }
    }
}

