<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;

if(!function_exists('getOnlineUsersCount')) {
    function getOnlineUsersCount():int
    {
        return Cache::remember('online_users_count', now()->addMinutes(5), function () {
            return User::online()->count();
        });
    }

    if(!function_exists('getOnlineUsers')) {
        function getOnlineUsers():array
        {
            return Cache::remember('online_users_list', 30, function () {
                return User::online()
                        ->select('id', 'name', 'email', 'last_activity_at')
                        ->orderBy('last_activity_at', 'desc')
                        ->get();
            });
        }
    }

    if(!function_exists('getRecentlyActiveUsers')) {
        function getRecentlyActiveUsers($limit = 10)
        {

            return User::recentlyActive()
                    ->select('id', 'name', 'email', 'last_activity_at')
                    ->orderBy('last_activity_at', 'desc')
                    ->get();

        }
    }
}