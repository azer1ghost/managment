<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $items;

    public function __construct()
    {
        $this->items = (array)[

//            (object) [
//                'title' => __('translates.navbar.general'),
//                'type' => 'title',
//            ],

            (object)[
                'title' => __('translates.navbar.dashboard'),
                'icon' => 'fa fa-home',
                'url' => route('dashboard'),
                'permission' => 'generally',
            ],

//            (object) [
//                'title' => __('translates.navbar.cabinet'),
//                'icon'  => 'fal fa-tasks-alt',
//                'url'  => route('cabinet'),
//                'permission' => 'generally',
//            ],

            (object)[
                'title' => __('translates.navbar.company'),
                'icon' => 'fa fa-building',
                'url' => route('companies.index'),
                'permission' => 'viewAny-company',
            ],

            (object)[
                'title' => __('translates.navbar.account'),
                'icon' => 'fas fa-user',
                'url' => '#',
                'permission' => 'generally',
                'children' => [
                    (object)[
                        'title' => __('translates.navbar.signature'),
                        'icon' => 'fa fa-envelope',
                        'url' => route('signature-select-company'),
                        'permission' => 'signature',
                    ],
                    (object)[
                        'title' => __('translates.navbar.bonus'),
                        'icon' => 'fas fa-gift',
                        'url' => route('bonuses'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => "Security",
                        'icon' => 'fas fa-lock',
                        'url' => route('account.security'),
                        'permission' => 'generally',
                        'badge' => (object) [
                            'title' => 'New',
                            'class' => 'info'
                        ],
                    ],

                ]
            ],

            (object)[
                'title' => __('translates.navbar.intern_number'),
                'icon' => 'fal fa-phone-office',
                'url' => route('internal-numbers.index'),
                'permission' => 'generally',
            ],

            (object)[
                'title' => __('translates.navbar.inquiry'),
                'icon' => 'fa fa-phone',
                'url' => route('inquiry.index'),
                'permission' => 'viewAny-inquiry',
            ],

            (object)[
                'title' => __('translates.navbar.task'),
                'icon' => 'fal fa-thumbtack',
                'url' => route('tasks.index'),
                'permission' => 'viewAny-task',
            ],

            (object)[
                'title' => __('translates.navbar.calendar'),
                'icon' => 'fal fa-calendar',
                'url' => route('calendars.index'),
                'permission' => 'viewAny-calendar',
            ],

            (object)[
                'title' => __('translates.navbar.human_resources'),
                'icon' => 'fas fa-user',
                'url' => '#',
                'permission' => 'viewAny-user',
                'children' => [
                    (object)[
                        'title' => __('translates.users.types.employees'),
                        'icon' => 'fa fa-users',
                        'url' => route('users.index'),
                        'permission' => 'viewAny-user',
                    ],
                    (object)[
                        'title' => __('translates.navbar.position'),
                        'icon' => 'fas fa-briefcase',
                        'url' => route('positions.index'),
                        'permission' => 'viewAny-position',
                    ],
                    (object)[
                        'title' => __('translates.navbar.department'),
                        'icon' => 'fas fa-briefcase',
                        'url' => route('departments.index'),
                        'permission' => 'viewAny-department',
                    ],
                ]
            ],
            (object)[
                'title' => __('translates.navbar.law'),
                'icon' => 'fas fa-gavel',
                'url' => '#',
                'permission' => 'viewAny-partner',
                'children' => [
                    (object)[
                        'title' => __('translates.navbar.partners'),
                        'icon' => 'fas fa-hands-helping',
                        'url' => route('partners.index'),
                        'permission' => 'viewAny-partner',
                    ],
                    (object)[
                        'title' => __('translates.navbar.customer_engagement'),
                        'icon' => 'fas fa-house-return',
                        'url' => route('customer-engagement.index'),
                        'permission' => 'viewAny-customerEngagement',
                    ],
                    (object)[
                        'title' => __('translates.navbar.asan_imza'),
                        'icon' => 'fas fa-file-signature',
                        'url' => route('asan-imza.index'),
                        'permission' => 'viewAny-asanImza',
                    ],
                ]
            ],

            (object)[
                'title' => __('translates.navbar.sales'),
                'icon' => 'fas fa-dollar-sign',
                'url' => '#',
                'permission' => 'viewAny-salesClient',
                'children' => [
                    (object)[
                        'title' => __('translates.navbar.sales_client'),
                        'icon' => 'fas fa-portrait',
                        'url' => route('sales-client.index'),
                        'permission' => 'viewAny-salesClient',
                    ],
                    (object)[
                        'title' => __('translates.navbar.inquiry_sales'),
                        'icon' => 'fa fa-phone',
                        'url' => route('inquiry.sales'),
                        'permission' => 'viewAny-salesInquiry',
                    ],
                    (object)[
                        'title' => __('translates.navbar.barcode'),
                        'icon' => 'fal fa-barcode',
                        'url' => route('barcode.index'),
                        'permission' => 'viewAny-barcode',
                    ],
                    (object)[
                        'title' => __('translates.navbar.sales_activities'),
                        'icon' => 'fas fa-chart-line',
                        'url' => route('sales-activities.index'),
                        'permission' => 'viewAny-salesActivity',
                    ],
                    (object)[
                        'title' => __('translates.navbar.sales_activities_type'),
                        'icon' => 'fas fa-chart-line',
                        'url' => route('sales-activities-types.index'),
                        'permission' => 'viewAny-salesActivityType',
                    ],
                    (object)[
                        'title' => __('translates.navbar.organization'),
                        'icon' => 'fal fa-house',
                        'url' => route('organizations.index'),
                        'permission' => 'viewAny-organization',
                    ],
                    (object)[
                        'title' => __('translates.navbar.certificate'),
                        'icon' => 'far fa-file-certificate',
                        'url' => route('certificates.index'),
                        'permission' => 'viewAny-certificate',
                    ],
                ]
            ],

            (object)[
                'title' => 'Dev Tools',
                'icon' => 'fas fa-laptop-code',
                'url' => '#',
                'permission' => 'viewAny-parameter',
                'children' => [
                    (object)[
                        'title' => __('translates.navbar.parameter'),
                        'icon' => 'fa fa-list',
                        'url' => route('parameters.index'),
                        'permission' => 'viewAny-parameter',
                    ],
                    (object)[
                        'title' => "Widgets",
                        'icon' => 'fas fa-tools',
                        'url' => route('widgets.index'),
                        'permission' => 'viewAny-widget',
                    ],
                    (object)[
                        'title' => "Debug Log",
                        'icon' => 'fal fa-bug',
                        'url' => url('module/log-reader'),
                        'permission' => 'viewAny-log',
                    ],
                    (object)[
                        'title' => __('translates.navbar.notification'),
                        'icon' => 'far fa-bell',
                        'url' => route('notifications.index'),
                        'permission' => 'viewAny-notification',
                    ],
                    (object)[
                        'title' => __('translates.navbar.option'),
                        'icon' => 'fa fa-list-alt',
                        'url' => route('options.index'),
                        'permission' => 'viewAny-option',
                    ],
                    (object)[
                        'title' => __('translates.navbar.role'),
                        'icon' => 'fas fa-key',
                        'url' => route('roles.index'),
                        'permission' => 'viewAny-role',
                    ],
                ]
            ],

            (object)[
                'title' => __('translates.navbar.report'),
                'icon' => 'fal fa-file',
                'url' => route('reports.index'),
                'permission' => 'viewAny-report',
            ],

            (object)[
                'title' => __('translates.navbar.client'),
                'icon' => 'fas fa-portrait',
                'url' => route('clients.index'),
                'permission' => 'viewAny-client',
            ],

            (object)[
                'title' => __('translates.navbar.referral'),
                'icon' => 'fas fa-ticket-alt',
                'url' => route('referrals.index'),
                'permission' => 'viewAny-referral',
            ],

            (object)[
                'title' => __('translates.navbar.meeting'),
                'icon' => 'fas fa-bullhorn',
                'url' => '#',
                'permission' => 'viewAny-meeting',
                'children' => [
                    (object)[
                        'title' => __('translates.navbar.meeting'),
                        'icon' => 'fas fa-bullhorn',
                        'url' => route('meetings.index'),
                        'permission' => 'viewAny-meeting',
                    ],
                    (object)[
                        'title' => __('translates.navbar.announcement'),
                        'icon' => 'fas fa-scroll',
                        'url' => route('announcements.index'),
                        'permission' => 'viewAny-announcement',
                    ],
                    (object)[
                        'title' => __('translates.navbar.conference'),
                        'icon' => 'fas fa-handshake',
                        'url' => route('conferences.index'),
                        'permission' => 'viewAny-conference',
                    ],
                ]
            ],

            (object)[
                'title' => "Advertising",
                'icon' => 'fab fa-adversal',
                'url' => route('advertising.index'),
                'permission' => 'viewAny-advertising',
            ],

            (object)[
                'title' => __('translates.navbar.update'),
                'icon' => 'fas fa-sync',
                'url' => route('updates.index'),
                'permission' => 'viewAny-update',
            ],

            (object)[
                'title' => __('translates.navbar.services'),
                'icon' => 'fas fa-concierge-bell',
                'url' => route('services.index'),
                'permission' => 'viewAny-service',
            ],

            (object)[
                'title' => __('translates.navbar.work'),
                'icon' => 'fas fa-briefcase',
                'url' => route('works.index'),
                'permission' => 'viewAny-work',
            ],

            (object)[
                'title' => __('translates.navbar.document'),
                'icon' => 'fas fa-file-word',
                'url' => route('documents.index'),
                'permission' => 'viewAny-document',
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
        return /* @lang Blade */
            <<<'blade'
            <nav class="sidebar sidebar-offcanvas pb-4" id="sidebar">
                <ul class="nav">
                  @foreach($items as $item)
                    @can($item->permission ?? 'generally')
                        <li @class(['nav-item', 'active' => request()->url() == $item->url])>
                            <a class="nav-link" @if(isset($item->children)) data-toggle="collapse" href="#ui-basic{{$loop->index}}" @else href="{{$item->url}}" @endif>
                                <i class="{{$item->icon}} mr-2"></i>
                                <span class="menu-title">{{$item->title}}</span>
                                @if(isset($item->children)) <i class="menu-arrow"></i> @endif
                            </a>
                            @if(isset($item->children))
                                <div class="collapse" id="ui-basic{{$loop->index}}">
                                    <ul class="nav flex-column sub-menu">
                                    @foreach($item->children as $menu)
                                        <li class="nav-item"> <a class="nav-link" href="{{$menu->url}}">{{$menu->title}}</a></li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endcan
                  @endforeach
                </ul>
            </nav>
        blade;
    }
}
