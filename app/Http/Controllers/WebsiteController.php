<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function jobs()
    {
        return view('jobs');
    }

    public function vacancies()
    {
        return view('vacancies');
    }

    public function scheduledVacancies()
    {
        return view('scheduled-vacancies');
    }

    public function weeklyCalendar()
    {
        return view('weekly-calendar');
    }

    public function users()
    {
        return view('users');
    }
}
