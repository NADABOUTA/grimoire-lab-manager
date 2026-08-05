<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function chercheur()
    {
        $projects = auth()->user()
            ->projects()
            ->wherePivot('role', 'chercheur')
            ->get();

        return view('dashboard.chercheur', compact('projects'));
    }
    public function etudiant()
{
    $projects = auth()->user()
        ->projects()
        ->wherePivot('role', 'etudiant_assistant')
        ->get();

    return view('dashboard.etudiant', compact('projects'));
}
}