<?php

namespace Modules\Accounting\Listeners;

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
        if (! planFeature('modules.accounting')) {
            return;
        }

        $menu = $event->menu;

        $activeClass = route_is([
            "budget.categories.*",
            "budgets.*",
            "budget.expense.*",
            "budget.revenue.*"
        ]) ? "active" : "";

        $menu->canAnyForActiveRole(
            [
                'view-budgetCategories',
                'view-budgets',
                'view-budgetExpenses',
                'view-budgetRevenues'
            ],
            function ($menu) use ($activeClass) {

                $menu->submenu(
                    Html::raw(
                        '<a href="#" class="' . $activeClass . '">
                            <i class="la la-files-o"></i>
                            <span>' . __("Budget") . '</span>
                            <span class="menu-arrow"></span>
                        </a>'
                    ),
                    Menu::new()
                        ->canForActiveRole(
                            'view-budgetCategories',
                            Link::toRoute('budget.categories.index', __('Categories'))
                                ->addClass(route_is(['budget.categories.*']) ? 'active' : '')
                        )
                        ->canForActiveRole(
                            'view-budgets',
                            Link::toRoute('budgets.index', __('Budgets'))
                                ->addClass(route_is(['budgets.*']) ? 'active' : '')
                        )
                        ->canForActiveRole(
                            'view-budgetExpenses',
                            Link::toRoute('budget.expense.index', __('Budget Expenses'))
                                ->addClass(route_is(['budget.expense.*']) ? 'active' : '')
                        )
                        ->canForActiveRole(
                            'view-budgetRevenues',
                            Link::toRoute('budget.revenue.index', __('Budget Revenue'))
                                ->addClass(route_is(['budget.revenue.*']) ? 'active' : '')
                        )
                        ->addParentClass('submenu')
                );
            }
        );
    }

}
