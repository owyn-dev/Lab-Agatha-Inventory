<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageProduction;

use App\Enums\StatusProduction;
use App\Http\Controllers\Controller;
use App\Models\Production;
use Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class ProductionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_production', only: ['index']),
            new Middleware('permission:show_production', only: ['show']),
            new Middleware('permission:edit_production', only: ['edit']),
            new Middleware('permission:view_report_production', only: ['report']),
        ];
    }

    public function index()
    {
        $title = 'Production List';

        $text_subtitle = 'Production List is used to display, manage, and monitor production data in the system';

        return view('manage-production.production-index', compact('title', 'text_subtitle'));
    }

    public function edit(Production $production)
    {
        $title = 'Edit Production';
        $text_subtitle = 'This page displays form edit production data.';

        $user = Auth::user();
        $isAdmin = $user->hasRole('administrator');

        if ( ! in_array($production->status, [StatusProduction::IN_PROGRESS, StatusProduction::REJECTED])) {
            abort(403, 'Only productions with status "In Progress" or "Rejected" can be edited.');
        }

        if (StatusProduction::REJECTED === $production->status) {
            $loginRoles = $user->roles->pluck('name');
            $rejectedRoles = $production->rejectedBy?->roles->pluck('name') ?? collect();
            $hasSameRole = $loginRoles->intersect($rejectedRoles)->isNotEmpty();

            if ( ! $hasSameRole && ! $isAdmin) {
                abort(403, 'You do not have permission to edit this rejected production.');
            }
        }

        return view('manage-production.production-edit', compact('title', 'text_subtitle', 'production'));
    }

    public function show(Production $production)
    {
        $title = 'Show Production';

        $text_subtitle = 'This page displays detail of production data.';

        return view('manage-production.production-show', compact('title', 'text_subtitle', 'production'));
    }

    public function report()
    {
        $title = 'Production Report';

        $text_subtitle = 'Generate Production Reports';

        return view('manage-production.production-report', compact('title', 'text_subtitle'));
    }
}
