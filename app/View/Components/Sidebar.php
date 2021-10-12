<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $items;

    public function __construct()
    {
        $this->items = (array) [

//            (object) [
//                'title' => __('translates.navbar.general'),
//                'type' => 'title',
//            ],

            (object) [
                'title' => __('translates.navbar.dashboard'),
                'icon'  => 'fa fa-home',
                'url'  => route('dashboard'),
                'permission' => 'generally',
            ],

//            (object) [
//                'title' => __('translates.navbar.cabinet'),
//                'icon'  => 'fal fa-tasks-alt',
//                'url'  => route('cabinet'),
//                'permission' => 'generally',
//            ],

            (object) [
                'title' => __('translates.navbar.company'),
                'icon'  => 'fa fa-building',
                'url'  => route('companies.index'),
                'permission' => 'viewAny-company',
            ],

            (object) [
                'title' => __('translates.navbar.account'),
                'icon'  => 'fa fa-user',
                'url'  => route('account'),
                'permission' => 'viewAny-account',
                'badge' => null,
//                (object) [
//                    'title' => 'New',
//                    'class' => 'info'
//                ],
            ],

            (object) [
                'title' => __('translates.navbar.bonus'),
                'icon'  => 'fas fa-gift',
                'url'  => route('bonuses'),
                'permission' => 'generally',
            ],

            (object) [
                'title' => __('translates.navbar.signature'),
                'icon'  => 'fa fa-envelope',
                'url'  => route('signature-select-company'),
                'permission' => 'signature',
            ],

            (object) [
                'title' => __('translates.navbar.inquiry'),
                'icon'  => 'fa fa-phone',
                'url'  => route('inquiry.index'),
                'permission' => 'viewAny-inquiry',
            ],

            (object) [
                'title' => __('translates.navbar.task'),
                'icon'  => 'fal fa-thumbtack',
                'url'  => route('tasks.index'),
                'permission' => 'viewAny-task',
            ],

            (object) [
                'title' => __('translates.navbar.parameter'),
                'icon'  => 'fa fa-list',
                'url'  => route('parameters.index'),
                'permission' => 'viewAny-parameter',
            ],

            (object) [
                'title' => __('translates.navbar.option'),
                'icon'  => 'fa fa-list-alt',
                'url'  => route('options.index'),
                'permission' => 'viewAny-option',
            ],

            (object) [
                'title' => __('translates.navbar.role'),
                'icon'  => 'fas fa-key',
                'url'  => route('roles.index'),
                'permission' => 'viewAny-role',
            ],

            (object) [
                'title' => __('translates.navbar.user'),
                'icon'  => 'fa fa-users',
                'url'  => route('users.index'),
                'permission' => 'viewAny-user',
            ],

            (object) [
                'title' => __('translates.navbar.department'),
                'icon'  => 'fa fa-users-cog',
                'url'  => route('departments.index'),
                'permission' => 'viewAny-department',
            ],

            (object) [
                'title' => __('translates.navbar.position'),
                'icon'  => 'fas fa-briefcase',
                'url'  => route('positions.index'),
                'permission' => 'viewAny-position',
            ],

            (object) [
                'title' => __('translates.navbar.notification'),
                'icon'  => 'far fa-bell',
                'url'  => route('notifications.index'),
                'permission' => 'viewAny-notification',
            ],

            (object) [
                'title' => __('translates.navbar.client'),
                'icon'  => 'fas fa-portrait',
                'url'  => route('clients.index'),
                'permission' => 'viewAny-client',
            ],

            (object) [
                'title' => "Referrals",
                'icon'  => 'fas fa-ticket-alt',
                'url'  => route('referrals.index'),
                'permission' => 'viewAny-referral',
            ],

            (object) [
                'title' => "Widgets",
                'icon'  => 'fas fa-tools',
                'url'  => route('widgets.index'),
                'permission' => 'viewAny-widget',
            ],

            (object) [
                'title' => "Debug Log",
                'icon'  => 'fal fa-bug',
                'url'  => url('module/log-reader'),
                'permission' => 'viewAny-log',
            ]
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return string
     */
    public function render(): string
    {
        return /* @lang Blade */
            <<<'blade'
            <ul>
                @foreach($items as $item)
                    @can($item->permission ?? 'generally') 
                        @if($item->type ?? '' == "title")
                            <li>
                                <h2 class="text-muted">{{$item->title}}</h2>
                            </li>
                        @else
                            <li @class(['active' => request()->url() == $item->url ]) >
                                <a href="{{$item->url}}">
                                    <span class="icon"><i class="{{$item->icon}} mr-2"></i></span>
                                    <span class="item">{{$item->title}}</span>
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
