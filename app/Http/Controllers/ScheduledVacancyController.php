<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ScheduledVacancy;
use Illuminate\Http\Request;

class ScheduledVacancyController extends Controller
{
    /**
     * Display a listing of all scheduled vacancies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $scheduledVacancies = ScheduledVacancy::with('vacancy.job','user')->get();
            return response()->json($scheduledVacancies);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch scheduled vacancies',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created scheduled vacancy resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'vacancy_id' => 'required|exists:vacancies,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $scheduledVacancy = ScheduledVacancy::create([
                'vacancy_id' => $request->vacancy_id,
                'user_id' => $request->user_id,
                'status' => 'scheduled',
            ]);

            return response()->json([
                'message' => 'Scheduled vacancy created successfully',
                'scheduledVacancy' => $scheduledVacancy,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create scheduled vacancy',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified scheduled vacancy resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'vacancy_id' => 'required|exists:vacancies,id',
                'user_id' => 'required|exists:users,id',
                'status' => 'required',
            ]);

            $scheduledVacancy = ScheduledVacancy::findOrFail($id);
            $scheduledVacancy->update([
                'vacancy_id' => $validatedData['vacancy_id'],
                'user_id' => $validatedData['user_id'],
                'status' => $validatedData['status'],
            ]);

            return response()->json([
                'message' => 'Scheduled vacancy updated successfully',
                'scheduledVacancy' => $scheduledVacancy,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update scheduled vacancy',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete the specified scheduled vacancy resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function softDeleteScheduledVacancy($id)
    {
        try {
            $scheduledVacancy = ScheduledVacancy::findOrFail($id);
            $scheduledVacancy->delete();

            return response()->json([
                'message' => 'Scheduled Vacancy temporarily deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to temporarily delete Scheduled Vacancy',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

