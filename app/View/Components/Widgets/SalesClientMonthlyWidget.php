<?php

namespace App\View\Components\Widgets;

use App\Models\SalesClient;
use App\Models\User;
use App\Traits\GetClassInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class SalesClientMonthlyWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public $result;

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $users = User::has('salesInquiryUsers')->get();

        $salesClients = SalesClient::select(['id', 'created_at', 'user_id'])
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->orderBy('created_at')
            ->get();

        $this->result = $salesClients->groupBy(function($client) {
                return $client->created_at->format('d-m-Y');
            })->map(function ($clients, $date) use ($users){

                $day = Carbon::parse($date);

                $result = [
                    'day' => $day->format('d'),
                ];

                foreach ($users as $user)
                {
                    $result[$user->fullname] = $user->salesInquiryUsers()->whereDate('created_at', $day)->count();
                }

                return $result;

            })->all();

        $this->result = array_values($this->result);
    }

    public function render()
    {
        return view('components.widgets.salesClientMonthly-widget');
    }
}
