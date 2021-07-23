<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{

    public $items = array();

    public function __construct()
    {
        $this->items = (array) [

            (object) [
                'title' => 'General',
                'type' => 'title',
            ],

            (object) [
                'title' => 'Dashboard',
                'icon'  => 'fa fa-home',
                'url'  => route('dashboard'),
                'permission' => 'general',
                'badge' => null,
            ],

            (object) [
                'title' => 'Companies',
                'icon'  => 'fa fa-building',
                'url'  => route('companies.index'),
                'permission' => 'view-company',
                'badge' => null,
            ],

            (object) [
                'title' => 'Account',
                'icon'  => 'fa fa-user',
                'url'  => route('account'),
                'permission' => 'browse-account',
                'badge' => null,
//                (object) [
//                    'title' => 'New',
//                    'class' => 'info'
//                ],
            ],

            (object) [
                'title' => 'Signature',
                'icon'  => 'fa fa-envelope',
                'url'  => route('signature-select-company'),
                'badge' => null,
                'permission' => 'signature',
            ],

            (object) [
                'title' => 'Customer Services',
                'icon'  => 'fa fa-phone',
                'url'  => route('customer-services'),
                'permission' => 'general',
                'badge' => null,
            ],

        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return string
     */
    public function render(): string
    {
        return /** @lang Blade */
            <<<'blade'
            <ul class="nav">
                @foreach($items as $item)
                @can($item->permission ?? 'general') 
                    @if($item->type ?? '' == "title")
                        <li>
                            <h2 class="text-muted">{{$item->title}}</h2>
                        </li>
                    @else
                        <li class="p-1" {{ (url()->current() == $item->url) ? 'class="active"' : '' }} >
                            <a href="{{$item->url}}">
                                <i class="{{$item->icon}} mr-2"></i>
                                <span>{{$item->title}}</span>
                                @if($item->badge ?? false)
                                    <span class="badge badge-pill badge-{{$item->badge->class}}">{{$item->badge->title}}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endcan
                @endforeach
            </ul>
        blade;
    }
}
