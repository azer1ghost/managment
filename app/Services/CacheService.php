<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Inquiry;
use App\Models\Task;
use App\Models\User;
use App\Models\Work;
use Cache;

class CacheService
{
    /**
     * @var OpenWeatherApi $openWeatherApi
     */
    private OpenWeatherApi $openWeatherApi;

    /**
     * @param OpenWeatherApi $openWeatherApi
     */
    public function __construct(OpenWeatherApi $openWeatherApi)
    {
        $this->openWeatherApi = $openWeatherApi;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function doesntHave(string $key): bool
    {
        return !$this->has($key);
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getData(string $key)
    {
        return Cache::get($key)['data'] ?? null;
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getLastTime(string $key)
    {
        return Cache::get($key)['updated_at'] ?? null;
    }

    /**
     * @param string $key
     * @param array|null $payload
     * @return void
     */
    public function update(string $key, ?array $payload = []): void
    {
        $data['data'] = $payload;
        $data['updated_at'] = now();

        Cache::put($key, $data);
    }

    /**
     * @param string $key
     * @return void
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * @return void
     */
    public function resolveWidgets(): void
    {
        // TODO finish widgets cache resolver
    }

    /**
     * @return void
     */
    public function resolveStatistics(): void
    {
        $users = User::query();
        $works = Work::query();
        $inquiries = Inquiry::query()->isReal();
        $tasks = Task::query();

        $getData = fn(int $total, int $count, string $text) => [
            'total' => $total,
            'percentage' => round($count/max($total, 1) * 100, 2),
            'text' => $text
        ];

        $statistics = [
            'users' => [
                'title' => trans('translates.widgets.number_of_users', [], 'az'),
                'color' => 'tale',
                'data' => $getData($users->count(), $users->isActive()->count(), trans('translates.users.statuses.active', [], 'az'), 'users'),
                'class' => 'mb-4'
            ],
            'works' => [
                'title' => trans('translates.widgets.number_of_works',  [], 'az'),
                'color' => 'dark-blue',
                'data' => $getData($works->count(), $works->isVerified()->count(), trans('translates.columns.verified', [], 'az'), 'works'),
                'class' => 'mb-4'
            ],
            'inquiries' => [
                'title' => trans('translates.widgets.number_of_inquiries',  [], 'az'),
                'color' => 'light-blue',
                'data' => $getData(
                    $inquiries->count(),
                    $inquiries
                        ->whereHas('options', fn($q) => $q->whereId(Inquiry::ACTIVE))
                        ->count(),
                    trans('translates.users.statuses.active',  [], 'az'),
                    'inquiries'
                ),
                'class' => 'mb-4'
            ],
            'tasks' => [
                'title' => trans('translates.widgets.number_of_tasks',  [], 'az'),
                'color' => 'light-danger',
                'data' => $getData($tasks->count(), $tasks->newTasks()->count(), trans('translates.tasks.list.to_do',  [], 'az'), 'tasks'),
                'class' => ''
            ],
        ];

        $this->update('statistics', $statistics);
    }

    /**
     * @return void
     */
    public function resolveOpenWeather(): void
    {
        $weather = $this->openWeatherApi->location(40.4093, 49.8671)->send(); // Baku lat/lon

        $this->update('open_weather', $weather);
    }
}