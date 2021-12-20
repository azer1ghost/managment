<?php

namespace App\View\Components\Widgets;

use App\Models\Client;
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

        $clients = Client::with('salesUsers')->whereNull('client_id');
        $legalClients = (clone $clients)->where('type', Client::LEGAL)->get();
        $physicalClients = (clone $clients)->where('type', Client::PHYSICAL)->get();

        $clientTypes = [$legalClients, $physicalClients];

        foreach ($clientTypes as $index => $type) {
            $typeCount = $type->count();

            $this->types[$index] = [
                'type' => trans('translates.clients_type.' . $index),
                'value' => $typeCount,
            ];

            $subs = [];
            foreach ($type as $client) {
                foreach ($client->salesUsers as $salesUser) {
                    if(count($client->salesUsers) >= 2) continue;
                    $subs[$salesUser->id]['type'] = $salesUser->getAttribute('fullname');
                    @$subs[$salesUser->id]['value'] += 1;
                }
            }

            $totalClientsWithUsers = array_reduce(array_column($subs, 'value'), fn($c, $i) => $c + $i);

            if($totalClientsWithUsers == 0){
                $subs[] = ['type' => trans('translates.general.no_users'), 'value' => $typeCount - $totalClientsWithUsers];
            }else if($totalClientsWithUsers != $typeCount){
                $subs[] = ['type' => trans('translates.general.common'), 'value' => $typeCount - $totalClientsWithUsers];
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
