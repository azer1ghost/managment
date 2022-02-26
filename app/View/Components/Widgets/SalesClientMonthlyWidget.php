<?php

namespace App\View\Components\Widgets;

use App\Models\SalesClient;
use App\Models\User;
use App\Traits\GetClassInfo;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class SalesClientMonthlyWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public array $results = [];

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        if (Cache::has("{$this->widget->getAttribute('key')}_widget")) {
            $this->results = Cache::get("{$this->widget->getAttribute('key')}_widget");
        } else {
            $users = User::has('salesInquiryUsers')->get();

            $salesClients = SalesClient::select(['id', 'created_at', 'user_id'])
                ->whereDate('created_at', '>=', now()->startOfMonth())
                ->orderBy('created_at')
                ->get()
                ->groupBy(function($client) {
                    return $client->created_at->format('d-m-Y');
                });

            $userData = [];

            $salesClients->each(function ($clients, $date) use ($users, &$userData){
                foreach ($users as $user)
                {
                    $userSalesClientsCount = $user->salesInquiryUsers()
                        ->whereDate('created_at', Carbon::parse($date))
                        ->count();

                    if ($userSalesClientsCount) {
                        $rand_color = rand_color();

                        if (!array_key_exists($user->id, $userData))
                            $userData[$user->id] = [
                                'label' => $user->fullname,
                                'borderColor' => $rand_color,
                                'backgroundColor' => $rand_color,
                                'data' => []
                            ];

                        $userData[$user->id]['data'][] = $user->salesInquiryUsers()
                            ->whereDate('created_at', Carbon::parse($date))
                            ->count();
                    }
                }
            });

            $this->results['data']   = array_values($userData);
            $this->results['labels'] = array_keys($salesClients->toArray());

            Cache::put("{$this->widget->getAttribute('key')}_widget", $this->results, 7200);
        }

    }

    public function render()
    {
        return view('components.widgets.salesClientMonthly-widget');
    }
}
