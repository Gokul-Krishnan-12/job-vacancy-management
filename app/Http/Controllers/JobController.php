<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of all jobs, including soft-deleted ones.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $jobs = Job::withTrashed()->get();
            return response()->json($jobs);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch jobs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of active jobs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeJobs()
    {
        try {
            $jobs = Job::where('status', 'active')->get();
            return response()->json($jobs);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch active jobs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created job resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'job_name' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);

            Job::create($validatedData);

            return response()->json([
                'message' => 'Job created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create job',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified job resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $job_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateJob(Request $request, $job_id)
    {
        try {
            $validatedData = $request->validate([
                'job_name' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);

            $job = Job::findOrFail($job_id);

            $job->update([
                'job_name' => $validatedData['job_name'],
                'status' => $validatedData['status'],
            ]);

            return response()->json([
                'message' => 'Job updated successfully!',
                'job' => $job,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update job',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete the specified job resource from storage.
     *
     * @param  int  $job_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function softDeleteJob($job_id)
    {
        try {
            $job = Job::findOrFail($job_id);

            $job->delete();

            return response()->json([
                'message' => 'Job temporarily deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to temporarily delete job',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore the specified soft-deleted job resource in storage.
     *
     * @param  int  $job_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreJob($job_id)
    {
        try {
            $job = Job::withTrashed()->findOrFail($job_id);

            $job->restore();

            return response()->json([
                'message' => 'Job restored successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to restore job',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
