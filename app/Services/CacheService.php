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
        $cached = $this->getData('statistics') ?? [];
        $cachedLastTime =  $this->getLastTime('statistics') ?? null;

        $filterHandler = fn($q) => $q->whereDate('created_at', '>=', $cachedLastTime);

        $users = User::query()->when($cachedLastTime && !$cached['users'], $filterHandler);
        $works = Work::query()->when($cachedLastTime && !$cached['works'], $filterHandler);
        $inquiries = Inquiry::query()->when($cachedLastTime && !$cached['inquiries'], $filterHandler);
        $tasks = Task::query()->when($cachedLastTime && !$cached['tasks'], $filterHandler);

        $getData = function(int $total, int $count, string $text, string $key) use ($cached) {
            $data = @$cached[$key]['data'];
            $newTotal = @$data['total'] + $total;
            $newPercentage = round(((@$data['total'] * @$data['percentage'] / 100) + $count) / max($newTotal, 1), 2) * 100;

            return [
                'total' => $newTotal,
                'percentage' => $newPercentage,
                'text' => $text
            ];
        };

        $statistics = [
            'users' => [
                'title' => __('translates.widgets.number_of_users'),
                'color' => 'tale',
                'data' => $getData($users->count(), $users->isActive()->count(), __('translates.users.statuses.active'), 'users'),
                'class' => 'mb-4'
            ],
            'works' => [
                'title' => __('translates.widgets.number_of_works'),
                'color' => 'dark-blue',
                'data' => $getData($works->count(), $works->isVerified()->count(), __('translates.columns.verified'), 'works'),
                'class' => 'mb-4'
            ],
            'inquiries' => [
                'title' => __('translates.widgets.number_of_inquiries'),
                'color' => 'light-blue',
                'data' => $getData(
                    $inquiries->count(),
                    $inquiries
                        ->whereHas('options', fn($q) => $q->whereId(Inquiry::ACTIVE))
                        ->count(),
                    __('translates.users.statuses.active'),
                    'inquiries'
                ),
                'class' => 'mb-4'
            ],
            'tasks' => [
                'title' => __('translates.widgets.number_of_tasks'),
                'color' => 'light-danger',
                'data' => $getData($tasks->count(), $tasks->newTasks()->count(), __('translates.tasks.list.to_do'), 'tasks'),
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