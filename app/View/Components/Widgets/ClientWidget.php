<?php

namespace App\View\Components\Widgets;

use App\Models\Client;
use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class ClientWidget extends Component
{
    use GetClassInfo;

    public ?Model $widget;
    public ?string $model = null;
    public array $types = [];

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $clients = Client::with('salesUsers')->withCount('salesUsers')->whereNull('client_id');
        $legalClients = (clone $clients)->where('type', Client::LEGAL)->get();
        $physicalClients = (clone $clients)->where('type', Client::PHYSICAL)->get();

        $clientTypes = [$legalClients, $physicalClients];

        foreach ($clientTypes as $index => $type) {
            $this->types[$index] = [
                'type' => $index === 0 ? 'Legal' : 'Physical',
                'percent' => round($type->count() / $clients->count() * 100, 2),
            ];

            $subs = [];

            foreach ($type as $client) {

                foreach ($client->salesUsers as $salesUser) {
                    if(count($client->salesUsers) >= 2) continue;
                    $subs[$salesUser->id]['type'] = $salesUser->getAttribute('fullname');
                    @$subs[$salesUser->id]['percent'] += 1;
                }
            }

            $total = (int) array_reduce(array_column($subs, 'percent'), fn($c, $i) => $c + $i);

            foreach ($subs as $idx => $sub) {
                $subs[$idx]['percent'] = round($sub['percent'] / $clients->count() * 100, 2);
            }

            if($total == 0){
                $subs[] = ['type' => 'No users', 'percent' => round(($type->count() - $total) / $clients->count() * 100 , 2)];
            }else if($total != $type->count()){
                $subs[] = ['type' => 'Common', 'percent' => round(($type->count() - $total) / $clients->count() * 100 , 2)];
            }

            $subs = array_values($subs);

            $this->types[$index]['subs'] = $subs;
        }
    }

    public function render()
    {
        return view('components.widgets.client-widget');
    }
}
