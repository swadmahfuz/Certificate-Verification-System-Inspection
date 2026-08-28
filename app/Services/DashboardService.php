<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardService
{
    public function data(): array
    {
        $total = Certificate::count();
        $pendingReview = Certificate::pendingReview()->count();
        $pendingApproval = Certificate::pendingApproval()->count();
        $expired = Certificate::approved()
            ->whereNotNull('validity_date')
            ->where('validity_date', '<', now()->format('Y-m-d'))
            ->count();
        $approved = Certificate::approved()
            ->where(function ($query) {
                $query->whereNull('validity_date')
                    ->orWhere('validity_date', '>=', now()->format('Y-m-d'));
            })
            ->count();

        $statusCounts = [
            'Approved' => $approved,
            'Pending Review' => $pendingReview,
            'Pending Approval' => $pendingApproval,
            'Expired' => $expired,
        ];

        $myAssignments = $this->myAssignments();

        return [
            'stats' => [
                'total' => $total,
                'approved' => $approved,
                'pending_review' => $pendingReview,
                'pending_approval' => $pendingApproval,
                'expired' => $expired,
            ],
            'myAssignments' => $myAssignments,
            'percentages' => collect($statusCounts)->map(function ($count) use ($total) {
                return $total > 0 ? round(($count / $total) * 100, 1) : 0;
            })->all(),
            'statusChart' => [
                'labels' => array_keys($statusCounts),
                'values' => array_values($statusCounts),
            ],
            'monthlyChart' => $this->monthlyInspections(),
            'recentCertificates' => Certificate::latest('created_at')->limit(5)->get([
                'id',
                'certificate_number',
                'client_name',
                'equipment_name',
                'status',
                'inspection_date',
            ]),
            'recentActivities' => $this->recentActivities(),
        ];
    }

    public function myAssignments(?int $userId = null): array
    {
        $userId = $userId ?? Auth::id();

        if (!$userId) {
            return [
                'review' => 0,
                'approval' => 0,
                'total' => 0,
            ];
        }

        $review = Certificate::assignedForReview($userId)->count();
        $approval = Certificate::assignedForApproval($userId)->count();

        return [
            'review' => $review,
            'approval' => $approval,
            'total' => $review + $approval,
        ];
    }

    private function monthlyInspections(): array
    {
        $months = collect(range(11, 1))->map(function ($monthsAgo) {
            return now()->startOfMonth()->subMonths($monthsAgo);
        })->push(now()->startOfMonth());

        $start = $months->first()->format('Y-m-d');
        $counts = Certificate::whereNotNull('inspection_date')
            ->where('inspection_date', '>=', $start)
            ->get(['inspection_date'])
            ->groupBy(function ($certificate) {
                try {
                    return Carbon::parse($certificate->inspection_date)->format('Y-m');
                } catch (\Throwable $exception) {
                    return 'invalid';
                }
            })
            ->map->count();

        return [
            'labels' => $months->map->format('M')->all(),
            'values' => $months->map(function ($month) use ($counts) {
                return $counts->get($month->format('Y-m'), 0);
            })->all(),
        ];
    }

    private function recentActivities()
    {
        if (!Schema::hasTable('inspection_activity_logs')) {
            return collect();
        }

        return ActivityLog::latest('created_at')->limit(6)->get();
    }
}
