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
    public array $results = [];

    public function __construct($widget)
    {
        $this->widget = $widget;
        $this->model = $this->getClassRealName();

        $clients = clone Client::query()->whereNull('client_id');

        $this->results = \Cache::remember("{$this->widget->getAttribute('keey')}_widget", 7200, function () use ($clients){
           return [
               (object) [
                   'label' => __('translates.clients_type')[0],
                   'total' => $clients->clone()->legal()->count()
               ],
               (object) [
                   'label' => __('translates.clients_type')[1],
                   'total' => $clients->clone()->physical()->count()
               ]
           ];
        });
    }

    public function render()
    {
        return view('components.widgets.client-widget');
    }
}
