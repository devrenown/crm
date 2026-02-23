<?php

namespace Modules\Sales\Listeners;

use App\Events\AppMenuEvent;
use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;


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
        if (! planFeature('modules.sales')) {
            return;
        }

        $menu = $event->menu;

        $activeClass = route_is(["taxes.*","expenses.*","estimates.*","invoices.*"]) ? "active" : "";

        $menu->canAnyForActiveRole(
            ['view-taxs','view-expenses','view-estimates','view-invoices'],
            function ($menu) use ($activeClass) {

                $menu->submenu(
                    Html::raw(
                        '<a href="#" class="' . $activeClass . '">
                            <i class="la la-files-o"></i>
                            <span>' . __("Finance") . '</span>
                            <span class="menu-arrow"></span>
                        </a>'
                    ),
                    Menu::new()
                        ->canForActiveRole(
                            'view-taxs',
                            Link::toRoute('taxes.index', __('Taxes'))
                                ->addClass(route_is(['taxes.*']) ? 'active' : '')
                        )
                        ->canForActiveRole(
                            'view-expenses',
                            Link::toRoute('expenses.index', __('Expenses'))
                                ->addClass(route_is(['expenses.*']) ? 'active' : '')
                        )
                        ->canForActiveRole(
                            'view-estimates',
                            Link::toRoute('estimates.index', __('Estimates'))
                                ->addClass(route_is(['estimates.*']) ? 'active' : '')
                        )
                        ->canForActiveRole(
                            'view-invoices',
                            Link::toRoute('invoices.index', __('Invoices'))
                                ->addClass(route_is(['invoices.*']) ? 'active' : '')
                        )
                        ->addParentClass('submenu')
                );
            }
        );
    }


}
