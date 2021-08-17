<?php

namespace Thoughtco\Outofstock;

use Event;
use Igniter\Local\Facades\Location;
use System\Classes\BaseExtension;
use Thoughtco\Outofstock\Models\Outofstock;

class Extension extends BaseExtension
{
    public function boot()
    {
        $cache_menus = NULL;
        $cache_menu_items = NULL;
        Event::listen('admin.menu.isAvailable', function (&$model, $dateTime, $isAvailable) use (&$cache_menus, &$cache_menu_items) {

            if (is_null($cache_menus))
                $cache_menus = Outofstock::where(['location_id' => Location::getId(), 'type' => 'menus'])->get()->pluck('type_id');

            if (is_null($cache_menu_items))
                $cache_menu_items = Outofstock::where(['location_id' => Location::getId(), 'type' => 'menuitems'])->get()->pluck('type_id');

            // if entire menu is out of stock
            if ($cache_menus->contains($model->getKey()))
               return TRUE;

            // if menu item is out of stock
            $model->menu_options = $model->menu_options->filter(function (&$menu_option) use ($cache_menu_items) {
                $menu_option->menu_option_values = $menu_option->menu_option_values->filter(function ($option_value) use ($cache_menu_items) {
                    return !$cache_menu_items->contains($option_value->option_value->id);
                });

                return $menu_option;
            });

        });
    }

    public function registerNavigation()
    {
        return [
            'restaurant' => [
                'child' => [
                    'outofstock' => [
                        'priority' => 25,
                        'class' => 'pages',
                        'href' => admin_url('thoughtco/outofstock/menus'),
                        'title' => lang('lang:thoughtco.outofstock::default.text_title'),
                        'permission' => 'Thoughtco.Outofstock.Manage',
                    ],
                ],
            ],
        ];
    }
}

?>
