<?php

namespace Thoughtco\Outofstock;

use Admin\Controllers\Categories;
use Admin\Controllers\Menus;
use Admin\Facades\AdminLocation;
use Carbon\Carbon;
use Event;
use Igniter\Local\Facades\Location;
use System\Classes\BaseExtension;
use Thoughtco\Outofstock\Models\Outofstock;
use Thoughtco\OutOfStock\Models\Settings;

class Extension extends BaseExtension
{
    public function boot()
    {
        $this->extendListColumns();
        $this->listenForMenuIsAvailableEvent();
    }

    private function extendListColumns()
    {
        Event::listen('admin.list.extendColumns', function ($widget) {

            $type = '';

            if ($widget->getController() instanceof Menus) {
                $type = 'menus';
                $primary_key = 'menu_id';
            }

            if ($widget->getController() instanceof Categories) {
                $type = 'categories';
                $primary_key = 'category_id';
            }

            if ($type == '')
                return;

            $widget->vars['out_of_stock'] = Outofstock::where([
                'type' => $type,
            ])->where(function($subquery) {
                $location_id = AdminLocation::getId();
                $subquery->where(['location_id' => $location_id]);
                if (!is_null($location_id))
                    $subquery->orWhereNull('location_id');
            })
            ->get()
            ->pluck('type_id');

            $widget->vars['out_of_stock_delays'] = collect(Settings::get('delay_times', []));
            $widget->vars['out_of_stock_url'] = admin_url('thoughtco/outofstock/'.$type);
            $widget->vars['out_of_stock_location'] = AdminLocation::getId();

            $widget->addColumns([
    			$primary_key => [
    				'label' => '',
    				'type' => 'partial',
    				'sortable' => FALSE,
                    'path' => 'extensions/thoughtco/outofstock/views/form/stock_column',
                ],
            ]);

        });
    }

    private function listenForMenuIsAvailableEvent()
    {
        $cache_menus = NULL;
        $cache_menu_items = NULL;
        $cache_categories = NULL;
        Event::listen('admin.menu.isAvailable', function (&$model, $dateTime, $isAvailable) use (&$cache_menus, &$cache_menu_items, &$cache_categories) {

            $location_id = Location::getId();

            if (is_null($cache_menus))
                $cache_menus = Outofstock::where(['type' => 'menus'])
                    ->where(function($subquery) use ($location_id) {
                        $subquery->where(['location_id' => $location_id])
                            ->orWhereNull('location_id');
                    })
                    ->where(function ($subquery) {
                        return $subquery->whereNull('timeout')
                            ->orWhere([
                                ['timeout', '>', Carbon::now()->format('Y-m-d H:i:s')]
                            ]);
                    })
                    ->get()
                    ->pluck('type_id');

            if (is_null($cache_menu_items))
                $cache_menu_items = Outofstock::where(['type' => 'menuoptions'])
                    ->where(function($subquery) use ($location_id) {
                        $subquery->where(['location_id' => $location_id])
                            ->orWhereNull('location_id');
                    })
                    ->where(function ($subquery) {
                        return $subquery->whereNull('timeout')
                            ->orWhere([
                                ['timeout', '>', Carbon::now()->format('Y-m-d H:i:s')]
                            ]);
                    })
                    ->get()
                    ->pluck('type_id');

            if (is_null($cache_categories))
                $cache_categories = Outofstock::where(['type' => 'categories'])
                    ->where(function($subquery) use ($location_id) {
                        $subquery->where(['location_id' => $location_id])
                            ->orWhereNull('location_id');
                    })
                    ->where(function ($subquery) {
                        return $subquery->whereNull('timeout')
                            ->orWhere([
                                ['timeout', '>', Carbon::now()->format('Y-m-d H:i:s')]
                            ]);
                    })
                    ->get()
                    ->pluck('type_id');

            // if entire menu is out of stock
            if ($cache_menus->contains($model->getKey())){
               return FALSE;
            }

            // if entire category is out of stock
			if (count(array_intersect($cache_categories->toArray(), $model->categories->pluck('category_id')->toArray())) > 0)
               return FALSE;

            // if menu item is out of stock
            $menu_options = $model->menu_options;
            foreach ($menu_options as $id => $menu_option) {
                $menu_options[$id]->menu_option_values = $menu_option->menu_option_values->filter(function ($option_value) use ($cache_menu_items) {
                    return !$cache_menu_items->contains($option_value->option_value_id);
                });
            };
            $model->menu_options = $menu_options;

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
                        'href' => admin_url('thoughtco/outofstock/menuoptions'),
                        'title' => lang('lang:thoughtco.outofstock::default.button_menuoptions'),
                        'permission' => 'Thoughtco.Outofstock.*',
                    ],
                ],
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'Thoughtco.OutOfStock.Manage' => [
                'description' => lang('lang:thoughtco.outofstock::default.text_permissions'),
                'group' => 'module',
            ],
            'Thoughtco.OutOfStock.Settings' => [
                'description' => lang('lang:thoughtco.outofstock::default.text_permissions_settings'),
                'group' => 'module',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'icon' => 'fa fa-random',
                'label' => lang('lang:thoughtco.outofstock::default.text_settings_label'),
                'description' => lang('lang:thoughtco.outofstock::default.text_settings_description'),
                'model' => 'Thoughtco\OutOfStock\Models\Settings',
                'permissions' => ['Thoughtco.OutOfStock.Settings'],
            ],
        ];
    }
}

?>
