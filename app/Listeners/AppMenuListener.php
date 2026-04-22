<?php

namespace App\Listeners;

use App\Enums\UserType;
use App\Events\AppMenuEvent;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Menu\Laravel\Html;

class AppMenuListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppMenuEvent $event): void
    {
        $menu = $event->menu;

        /* ================= MAIN ================= */
        $menu->html('<span>Main</span>', ['class' => 'menu-title fw-bold']);

        $menu->add(
            Link::toRoute('dashboard', '<i class="la la-dashboard"></i> <span>' . __('Dashboard') . '</span>')
                ->setActive(route_is('dashboard'))
        );

        /* ================= APPS ================= */
        if (planFeature('modules.chat')) {
            $activeClass = route_is(['app.chat']) ? 'active' : '';

            $unreadMessages = \App\Models\ChatMessage::where('receiver_id', auth()->user()->id)
            ->where('is_read', false)->count();

            $badge = '';
            if (!empty($unreadMessages) && $unreadMessages > 0) {
                $badge = '<span class="badge top-0 text-white rounded-pill bg-danger position-absolute">'
                       . $unreadMessages .
                       '</span>';
            }

            $menu->add(
                Link::toRoute('app.chat', '<div>' . $badge . '<i class="lab la-rocketchat"></i> <span>'. __('Chat') . '</span></div>')
                    ->setActive(route_is('app.chat'))
            );
        }

        /* ================= SUBSCRIPTION ================= */
        $menu->canForActiveRole(
            'view-subscriptions',
            Link::toRoute('subscription.index', '<i class="la la-credit-card"></i> <span>' . __('Subscription') . '</span>')
                ->setActive(route_is('subscription.*'))
        );

        /* ================= Demo Request ================= */
        // $menu->canForActiveRole(
        //     'view-demo-request',
        //     Link::toRoute('demo-request', '<i class="la la-credit-card"></i> <span>' . __('Subscription') . '</span>')
        //         ->setActive(route_is('subscription.*'))
        // );

        /* ================= ORGANIZATIONS ================= */
        $menu->canAnyForActiveRole(['view-organizations'], function ($menu) {
            $menu->html('<span>Organizations</span>', ['class' => 'menu-title fw-bold']);

            $menu->canForActiveRole(
                'view-organizations',
                Link::toRoute('tenant.index', '<i class="la la-building"></i> <span>' . __('Organizations') . '</span>')
                    ->setActive(route_is('tenant.*'))
            );
        });

        /* ================= EMPLOYEES ================= */
        if (planFeature('modules.employees')) {
            $menu->canAnyForActiveRole([
                'view-employees',
                'view-attendances',
                'view-shifts',
                'view-departments',
                'view-designations',
                'view-holidays',
                'view-work-tasks',
            ], function ($menu) {

                $menu->html('<span>Employees</span>', ['class' => 'menu-title fw-bold']);

                $activeClass = route_is([
                    'employees.*',
                    'attendances.*',
                    'shift.*',
                    'departments.*',
                    'designations.*',
                    'holidays.*',
                    'work-report.*',
                    'onboard'
                ]) ? 'active' : '';

                $menu->submenu(
                    Html::raw(
                        '<a href="#" class="' . $activeClass . '">
                            <i class="la la-user"></i>
                            <span>' . __('Employees') . '</span>
                            <span class="menu-arrow"></span>
                        </a>'
                    ),
                    Menu::new()
                        ->addParentClass('submenu')

                        ->canForActiveRole(
                            'view-employees',
                            Link::toRoute('employees.index', __('Employees'))
                                ->addClass(route_is(['employees.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-attendances',
                            Link::toRoute('attendances.index', __('Attendance'))
                                ->addClass(route_is(['attendances.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-shifts',
                            Link::toRoute('shift.index', __('Shifts'))
                                ->addClass(route_is(['shift.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-departments',
                            Link::toRoute('departments.index', __('Departments'))
                                ->addClass(route_is(['departments.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-designations',
                            Link::toRoute('designations.index', __('Designations'))
                                ->addClass(route_is(['designations.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-holidays',
                            Link::toRoute('holidays.calendar', __('Holidays'))
                                ->addClass(route_is(['holidays.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-work-tasks',
                            Link::toRoute('work-report.index', __('Work Reports'))
                                ->addClass(route_is(['work-report.*']) ? 'active' : '')
                        )

                        ->addIf(
                            auth()->check() && auth()->user()->is_onboarding_complete == 0,
                            Link::toRoute('onboard', __('Onboarding'))
                                ->addClass(route_is('onboard') ? 'active' : '')
                        )
                );
            });
        }

        /* ================= REPORTING MANAGERS ================= */
        if (planFeature('modules.reporting_managers')) {
            $menu->canForActiveRole(
                'view-reporting-manager',
                Link::toRoute('reporting-managers', '<i class="la la-user-tie"></i> <span>' . __('Reporting Managers') . '</span>')
                    ->setActive(route_is('reporting-managers.*'))
            );
        }

        /* ================= CLIENTS ================= */
        if (planFeature('modules.clients')) {
            $menu->canForActiveRole(
                'view-clients',
                Link::toRoute('clients.index', '<i class="la la-group"></i> <span>' . __('Clients') . '</span>')
                    ->setActive(route_is('clients.*'))
            );
        }

        /* ================= TICKETS ================= */
        // if (planFeature('modules.tickets')) {
        //     $menu->canForActiveRole(
        //         'view-tickets',
        //         Link::toRoute('tickets.index', '<i class="la la-ticket"></i> <span>' . __('Tickets') . '</span>')
        //             ->setActive(route_is('tickets.*'))
        //     );
        // }

        /* ================= TICKETS ================= */
        if (planFeature('modules.tickets')) {
            $menu->canAnyForActiveRole([
                'view-tickets',
                // 'view-my-tickets',
            ], function ($menu) {

                $menu->html('<span>Support</span>', ['class' => 'menu-title fw-bold']);

                $activeClass = route_is([
                    'tickets.*',
                    'my-tickets.*',
                ]) ? 'active' : '';

                $menu->submenu(
                    Html::raw(
                        '<a href="#" class="' . $activeClass . '">
                            <i class="la la-ticket"></i>
                            <span>' . __('Tickets') . '</span>
                            <span class="menu-arrow"></span>
                        </a>'
                    ),
                    Menu::new()
                        ->addParentClass('submenu')

                        ->canForActiveRole(
                            'view-tickets',
                            Link::toRoute('tickets.index', __('Tickets'))
                                ->addClass(route_is(['tickets.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-tickets',
                            Link::toRoute('my-tickets', __('My Tickets'))
                                ->addClass(route_is(['my-tickets']) ? 'active' : '')
                        )
                );
            });
        }

        /* ================= PAYROLL ================= */
        if (planFeature('modules.payroll')) {
            $menu->canAnyForActiveRole(
                ['view-PayrollAllowances', 'view-PayrollDeductions', 'view-payslips'],
                function ($menu) {

                    $activeClass = route_is([
                        'payroll.*',
                        'payslips.*',
                        'allowances.*',
                        'deductions.*'
                    ]) ? 'active' : '';

                    $menu->submenu(
                        Html::raw(
                            '<a href="#" class="' . $activeClass . '">
                                <i class="la la-money"></i>
                                <span>' . __('Payroll') . '</span>
                                <span class="menu-arrow"></span>
                            </a>'
                        ),
                        Menu::new()
                            ->addParentClass('submenu')

                            ->canAnyForActiveRole(
                                ['view-PayrollAllowances', 'view-PayrollDeductions'],
                                function ($menu) {
                                    $menu->add(
                                        Link::toRoute(
                                            'payroll.items',
                                            __('Payroll Items')
                                        )->addClass(
                                            route_is(['payroll.items']) ? 'active' : ''
                                        )
                                    );
                                }
                            )
                            ->canForActiveRole(
                                'view-payslips',
                                Link::toRoute('payslips.index', __('Payslips'))
                                    ->addClass(route_is(['payslips.*']) ? 'active' : '')
                            )
                    );
                }
            );
        }

        /* ================= LEAVES ================= */

        if (planFeature('modules.leave')) {
            $menu->canAnyForActiveRole([
                'view-leave', 
                'view-leave-balance', 
                'view-leave-type'
            ], function ($menu) {

                $activeClass = route_is(['leaves.*', 'leave-balances.*', 'leave-type.*']) ? 'active' : '';

                $menu->submenu(
                    Html::raw(
                        '<a href="#" class="' . $activeClass . '">
                            <i class="la la-calendar"></i>
                            <span>' . __('Leaves') . '</span>
                            <span class="menu-arrow"></span>
                        </a>'
                    ),
                    Menu::new()
                        ->addParentClass('submenu')

                        // Leave Applications
                        ->canForActiveRole(
                            'view-leave',
                            Link::toRoute('leaves.index', __('Leave Requests'))
                                ->addClass(route_is(['leaves.*']) ? 'active' : '')
                        )

                        // Leave Types
                        ->canForActiveRole(
                            'view-leave-type',
                            Link::toRoute('leave-type.index', __('Leave Types'))
                                ->addClass(route_is(['leave-type.*']) ? 'active' : '')
                        )

                        // Leave Balances
                        ->canForActiveRole(
                            'view-leave-balance',
                            Link::toRoute('leave-balances.index', __('Leave Balances'))
                                ->addClass(route_is(['leave-balances.*']) ? 'active' : '')
                        )
                );
            });
        }



        /* ================= USERS ================= */
        if (planFeature('modules.administrative_users')) {
            $menu->canForActiveRole(
                'view-users',
                Link::toRoute('users.index', '<i class="la la-user-plus"></i> <span>' . __('Users') . '</span>')
                    ->setActive(route_is('users.*'))
            );
        }

        /* ================= BACKUPS ================= */
        $menu->canForActiveRole(
            'view-backups',
            Link::toRoute('backups.index', '<i class="la la-cloud-upload"></i> <span>' . __('Backups') . '</span>')
                ->setActive(route_is('backups.*'))
        );

        /* ================= SETTINGS ================= */
        if (planFeature('modules.settings')) {
            $menu->canForActiveRole(
                'view-settings',
                Link::toRoute('settings.index', '<i class="la la-cog"></i> <span>' . __('Settings') . '</span>')
                    ->setActive(route_is('settings.*'))
            );
        }

        /* ================= ASSETS ================= */
        if (planFeature('modules.assets')) {
            $menu->canForActiveRole(
                'view-assets',
                Link::toRoute('assets-list.index', '<i class="la la-object-ungroup"></i> <span>' . __('Assets') . '</span>')
                    ->setActive(route_is('assets-list.*'))
            );
        }
    }

}
