<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $items;

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
                'permission' => 'generally',
            ],

//            (object) [
//                'title' => 'Cabinet',
//                'icon'  => 'fal fa-tasks-alt',
//                'url'  => route('cabinet'),
//                'permission' => 'generally',
//            ],

            (object) [
                'title' => 'Companies',
                'icon'  => 'fa fa-building',
                'url'  => route('companies.index'),
                'permission' => 'viewAny-company',
            ],

//            (object) [
//                'title' => 'Gadgets',
//                'icon'  => 'fad fa-browser',
//                'url'  => route('gadgets.index'),
//                'permission' => 'viewAny-gadget',
//            ],

            (object) [
                'title' => 'Account',
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
                'title' => 'Signature',
                'icon'  => 'fa fa-envelope',
                'url'  => route('signature-select-company'),
                'permission' => 'signature',
            ],

            (object) [
                'title' => 'Inquiries',
                'icon'  => 'fa fa-phone',
                'url'  => route('inquiry.index'),
                'permission' => 'viewAny-inquiry',
            ],

            (object) [
                'title' => 'Parameters',
                'icon'  => 'fa fa-list',
                'url'  => route('parameters.index'),
                'permission' => 'viewAny-parameter',
            ],

            (object) [
                'title' => 'Options',
                'icon'  => 'fa fa-list-alt',
                'url'  => route('options.index'),
                'permission' => 'viewAny-option',
            ],

            (object) [
                'title' => 'Roles',
                'icon'  => 'fas fa-key',
                'url'  => route('roles.index'),
                'permission' => 'viewAny-role',
            ],

            (object) [
                'title' => 'Users',
                'icon'  => 'fa fa-users',
                'url'  => route('users.index'),
                'permission' => 'viewAny-user',
            ],

            (object) [
                'title' => 'Departments',
                'icon'  => 'fa fa-users-cog',
                'url'  => route('departments.index'),
                'permission' => 'viewAny-department',
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
            <ul class="nav flex-row flex-md-column">
                @foreach($items as $item)
                    @can($item->permission ?? 'generally') 
                        @if($item->type ?? '' == "title")
                            <li>
                                <h2 class="text-muted">{{$item->title}}</h2>
                            </li>
                        @else
                            <li @class([ 'p-1', 'active' => request()->url() == $item->url ]) >
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
