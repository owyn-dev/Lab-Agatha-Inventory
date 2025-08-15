<?php

declare(strict_types=1);

namespace App\Http\Controllers\ClassificationABC;

use App\Http\Controllers\Controller;

final class PriorityAnalysisController extends Controller
{
    public function index()
    {
        $title = 'Priority Analysis';

        $text_subtitle = 'Product Priority Analysis Makes it Easier for You to Make Decisions';

        return view('classification.priority-analysis', compact('title', 'text_subtitle'));
    }
}
