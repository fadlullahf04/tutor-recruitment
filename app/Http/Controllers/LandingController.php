<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentPeriod;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page of the recruitment system.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch the currently active recruitment period
        $activePeriod = RecruitmentPeriod::where('is_active', true)->first();

        return view('landing', compact('activePeriod'));
    }
}
