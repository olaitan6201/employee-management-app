<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $us = Country::whereCountryCode('USA')->withCount('employees')->first(['name']);
        $uk = Country::whereCountryCode('UK')->withCount('employees')->first(['name']);
        return [
            Stat::make('All Employees', Employee::count()),
            Stat::make("$us->name Employees", $us->employees_count),
            Stat::make("$uk->name Employees", $uk->employees_count),
        ];
    }
}
