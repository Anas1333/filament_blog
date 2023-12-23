<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Post;
use App\Models\User;
use App\Models\comments;

class UserPostOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::all()->count()),
            Stat::make('Total Posts', Post::all()->count()),
            Stat::make('Total Comments', comments::all()->count()),
        ];
    }
}
