<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Number;

final class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            $this->getVerifiedUsersStat(),
            $this->getUnverifiedUsersStat(),
        ];
    }

    private function getUnverifiedUsersStat(): Stat
    {
        return Stat::make(__('Not verified users'), User::query()->whereNull('email_verified_at')->count())
            ->description(__('Deleted after one week of being created.'));
    }

    private function getVerifiedUsersStat(): Stat
    {
        $current = Trend::query(User::query()->whereNotNull('email_verified_at'))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->dateColumn('email_verified_at')
            ->perMonth()
            ->count();

        $previous = Trend::query(User::query()->whereNotNull('email_verified_at'))
            ->between(
                start: now()->subYear()->startOfYear(),
                end: now()->subYear()->endOfMonth(),
            )
            ->dateColumn('email_verified_at')
            ->perMonth()
            ->count();

        $color = 'success';
        $description = __('100% increase');
        if ($previous->sum('aggregate') > 0) {
            $growth = round(($current->sum('aggregate') - $previous->sum('aggregate')) / $previous->sum('aggregate') * 100, 2);
            if ($growth < 0) {
                $color = 'danger';
                $description = __(':decrease% decrease', ['decrease' => abs($growth)]);
            } else {
                $description = __(':increase% increase', ['increase' => $growth]);
            }
        }

        return Stat::make(__('Verified users'), Number::abbreviate(User::query()->whereNotNull('email_verified_at')->count()))
            ->chart($current->map(fn (TrendValue $value): mixed => $value->aggregate)->toArray())
            ->color($color)
            ->description($description);
    }
}
