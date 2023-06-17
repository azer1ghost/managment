<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $items;

    public function __construct()
    {
        $this->items = (array)[

            (object)[
                'title' => __('translates.navbar.dashboard'),
                'icon' => 'fa fa-home',
                'url' => route('dashboard'),
                'permission' => 'generally',
            ],

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
                'title' => __('translates.navbar.structure'),
                'icon' => 'fad fa-chart-network',
                'url' => '#',
                'permission' => 'generally',
                'children' => [
                    (object)[
                        'title' => __('translates.navbar.structure'),
                        'icon' => 'fal fa-users-class',
                        'url' => route('structure'),
                        'permission' => 'viewAny-jobInstruction',
                    ],
                    (object)[
                        'title' => __('translates.navbar.intern_number'),
                        'icon' => 'fal fa-phone-office',
                        'url' => route('internal-numbers.index'),
                        'permission' => 'viewAny-internalNumber',
                    ],
                    (object)[
                        'title' => __('translates.fields.cooperative_numbers'),
                        'icon' => 'fal fa-phone-office',
                        'url' => route('cooperative-numbers'),
                        'permission' => 'viewAny-internalNumber',
                    ],
                    (object)[
                        'title' => __('translates.navbar.intern_relation'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('internal-relations.index'),
                        'permission' => 'generally',
                    ],

                    (object)[
                        'title' => __('translates.navbar.foreign_relation'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('foreign'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.presentation'),
                        'icon' => 'far fa-chalkboard-teacher',
                        'url' => route('presentations'),
                        'permission' => 'viewAny-task',
                    ],
                    (object)[
                        'title' => __('translates.navbar.instruction'),
                        'icon' => 'far fa-chalkboard-teacher',
                        'url' => route('instruction'),
                        'permission' => 'viewAny-task',
                    ],
                    (object)[
                        'title' => __('translates.navbar.asan_imza'),
                        'icon' => 'fas fa-file-signature',
                        'url' => route('asan-imza.index'),
                        'permission' => 'viewAny-asanImza',
                    ],
                    (object)[
                        'title' => __('translates.navbar.necessary'),
                        'icon' => 'fas fa-file-signature',
                        'url' => route('necessary'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.changes'),
                        'icon' => 'fas fa-file-signature',
                        'url' => route('changes.index'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.registration_logs'),
                        'icon' => 'fas fa-file-signature',
                        'url' => route('registration-logs.index'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.supports'),
                        'icon' => 'fas fa-file-signature',
                        'url' => route('supports.index'),
                        'permission' => 'generally',
                    ],
                ]
            ],
            (object)[
                'title' => __('translates.navbar.work'),
                'icon' => 'fas fa-briefcase',
                'url' => route('works.index'),
                'permission' => 'viewAny-work',
                'children' => [
                    (object)[
                        'title' => __('translates.navbar.work'),
                        'icon' => 'fas fa-briefcase',
                        'url' => route('works.index'),
                        'permission' => 'viewAny-work',
                    ],
                    (object)[
                        'title' => __('translates.navbar.pendingWorks'),
                        'icon' => 'fas fa-bullhorn',
                        'url' => route('pending-works'),
                        'permission' => 'viewAny-work',
                    ],
                    (object)[
                        'title' => __('translates.navbar.financeWorks'),
                        'icon' => 'fas fa-scroll',
                        'url' => route('finance-works'),
                        'permission' => 'viewAny-work',
                    ],
                    (object)[
                        'title' => __('translates.navbar.plannedWorks'),
                        'icon' => 'fas fa-scroll',
                        'url' => route('planned-works'),
                        'permission' => 'viewAny-work',
                    ],
                ]
            ],

            (object)[
                'title' => __('translates.navbar.finance'),
                'icon' => 'fas fa-briefcase',
                'url' => route('creditors.index'),
                'permission' => 'viewAny-financeClient',
                'children' => [
                    (object)[
                        'title' => 'Hesab Faktura',
                        'icon' => 'far fa-file-invoice',
                        'url' => route('accountInvoice'),
                        'permission' => 'viewAny-financeClient',
                    ],
                    (object)[
                        'title' => trans('translates.navbar.creditor'),
                        'icon' => 'fas fa-money-check-edit-alt',
                        'url' => route('creditors.index'),
                        'permission' => 'viewAny-creditor',
                    ],
                    (object)[
                        'title' => trans('translates.navbar.accounts'),
                        'icon' => 'fas fa-money-check',
                        'url' => route('banks.index'),
                        'permission' => 'viewAny-creditor',
                    ],
                ]
            ],


            (object)[
                'title' => __('translates.navbar.logistics'),
                'icon' => 'fas fa-shipping-fast',
                'url' => route('logistics.index'),
                'permission' => 'viewAny-logistics',
            ],

            (object)[
                'title' => __('translates.navbar.client'),
                'icon' => 'fas fa-portrait',
                'url' => route('clients.index'),
                'permission' => 'viewAny-client',
            ],

            (object)[
                'title' => __('translates.navbar.report'),
                'icon' => 'fal fa-file',
                'url' => route('reports.index'),
                'permission' => 'viewAny-report',
            ],

            (object)[
                'title' => __('translates.navbar.task'),
                'icon' => 'fal fa-thumbtack',
                'url' => route('tasks.index'),
                'permission' => 'viewAny-task',
            ],

            (object)[
                'title' => __('translates.navbar.inquiry'),
                'icon' => 'fa fa-phone',
                'url' => route('inquiry.index'),
                'permission' => 'viewAny-inquiry',
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
                    (object)[
                        'title' => __('translates.navbar.internal_document'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('internal-documents.index'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.access_rate'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('access-rates.index'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.sent_document'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('sent-documents.index'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.iso_document'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('iso-documents.index'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.protocols'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('protocols.index'),
                        'permission' => 'generally',
                    ],
                    (object)[
                        'title' => __('translates.navbar.commands'),
                        'icon' => 'fad fa-chart-network',
                        'url' => route('commands.index'),
                        'permission' => 'generally',
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
                        'title' => __('translates.navbar.intermediary'),
                        'icon' => 'fas fa-hands-helping',
                        'url' => route('partners.index'),
                        'permission' => 'viewAny-partner',
                    ],
                    (object)[
                        'title' => __('translates.navbar.reference'),
                        'icon' => 'fas fa-house-return',
                        'url' => route('customer-engagement.index'),
                        'permission' => 'viewAny-customerEngagement',
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
                'title' => __('translates.navbar.supplier'),
                'icon' => 'fa fa-hands-helping',
                'url' => route('suppliers.index'),
                'permission' => 'viewAny-supplier',
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
                    (object)[
                        'title' => __('translates.navbar.announcement'),
                        'icon' => 'fas fa-scroll',
                        'url' => route('statements.index'),
                        'permission' => 'viewAny-statements',
                    ],
                    (object)[
                        'title' => __('translates.navbar.satisfaction'),
                        'icon' => 'fas fa-concierge-bell',
                        'url' => route('satisfactions.index'),
                        'permission' => 'viewAny-satisfaction',
                    ],
                ]
            ],

            (object)[
                'title' => __('translates.navbar.calendar'),
                'icon' => 'fal fa-calendar',
                'url' => route('calendars.index'),
                'permission' => 'viewAny-calendar',
            ],
            (object)[
                'title' => __('translates.navbar.meeting'),
                'icon' => 'fas fa-bullhorn',
                'url' => route('meetings.index'),
                'permission' => 'viewAny-meeting',
            ],

            (object)[
                'title' => __('translates.navbar.employee_satisfaction'),
                'icon' => 'fal fa-user-shield',
                'url' => route('employee-satisfaction.index'),
                'permission' => 'generally',
            ],
            (object)[
                'title' => __('translates.navbar.customer-satisfaction'),
                'icon' => 'fas fa-users-crown',
                'url' => route('customer-satisfactions.index'),
                'permission' => 'viewAny-satisfaction',
            ],
            (object)[
                'title' => __('translates.navbar.referral'),
                'icon' => 'fas fa-ticket-alt',
                'url' => route('referrals.index'),
                'permission' => 'viewAny-referral',
            ],

            (object)[
                'title' => __('translates.navbar.services'),
                'icon' => 'fas fa-concierge-bell',
                'url' => route('services.index'),
                'permission' => 'viewAny-service',
            ],

//            (object)[
//                'title' => __('translates.navbar.order'),
//                'icon' => 'fas fa-money-check-edit-alt',
//                'url' => route('orders.index'),
//                'permission' => 'viewAny-work',
//            ],

            (object)[
                'title' => __('translates.navbar.document'),
                'icon' => 'fas fa-file-word',
                'url' => route('documents.index'),
                'permission' => 'viewAny-document',
            ],

            (object)[
                'title' => __('translates.navbar.announcement'),
                'icon' => 'fas fa-bullhorn',
                'url' => route('statement'),
                'permission' => 'viewAny-task',
            ],

            (object)[
                'title' => 'Chat',
                'icon' => 'fab fa-rocketchat',
                'url' => route('chats.index'),
                'permission' => 'viewAny-task',
                'children' => [
                    (object)[
                        'title' => trans('translates.navbar.room'),
                        'icon' => 'fab fa-rocketchat',
                        'url' => route('rooms.index'),
                        'permission' => 'viewAny-task',
                    ],  (object)[
                        'title' => 'Chat',
                        'icon' => 'fab fa-rocketchat',
                        'url' => route('chats.index'),
                        'permission' => 'viewAny-task',
                    ],
                ]
            ],


//            (object)[
//                'title' => 'Transit',
//                'icon' => 'fab fa-truck',
//                'url' => route('transit-login'),
//                'permission' => 'viewAny-task',
//            ],
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
                                        @can($menu->permission ?? 'generally')
                                            <li class="nav-item"> <a class="nav-link" href="{{$menu->url}}">{{$menu->title}}</a></li>
                                        @endcan
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
