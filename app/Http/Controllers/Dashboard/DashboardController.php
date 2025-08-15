<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

final class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        $text_subtitle = 'Get an overview of the latest data and information';

        return view('dashboard', compact('title', 'text_subtitle'));
    }
}
