<?php

namespace Modules\Blog\Listeners;

use App\Events\AppMenuEvent;


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
        // $menu->html('<span>blog</span>', ['class' => 'menu-title']);
    }
}
