<?php

namespace Modules\Project\Listeners;

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
        if (! planFeature('modules.projects')) {
            return;
        }

        $menu = $event->menu;

        $activeClass = route_is(['projects.*', 'task-boards.*']) ? 'active' : '';

        $menu->canAnyForActiveRole(
            ['view-projects', 'view-taskboards'],
            function ($menu) use ($activeClass) {

                $menu->submenu(
                    Html::raw(
                        '<a href="#" class="' . $activeClass . '">
                            <i class="la la-rocket"></i>
                            <span>' . __('Projects') . '</span>
                            <span class="menu-arrow"></span>
                        </a>'
                    ),
                    Menu::new()
                        ->addParentClass('submenu')

                        ->canForActiveRole(
                            'view-projects',
                            Link::toRoute('projects.index', __('Projects'))
                                ->addClass(route_is(['projects.*']) ? 'active' : '')
                        )

                        ->canForActiveRole(
                            'view-taskboards',
                            Link::toRoute('task-boards.index', __('Default TaskBoards'))
                                ->addClass(route_is(['task-boards.*']) ? 'active' : '')
                        )
                );
            }
        );
    }

}
