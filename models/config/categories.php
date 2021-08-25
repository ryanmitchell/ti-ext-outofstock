<?php

use Thoughtco\OutOfStock\Models\Settings;

$cache_menus = \Thoughtco\Outofstock\Models\Outofstock::where([
    'type' => 'categories',
    'location_id' => \AdminLocation::getId(),
])->get()->pluck('type_id');

return [
    'list' => [
        'toolbar' => [
            'buttons' => [
		        'menus' => [
		            'label' => 'lang:thoughtco.outofstock::default.button_menuitems',
		            'class' => 'btn btn-secondary',
		            'href' => 'thoughtco/outofstock/menus',
		        ],
		        'menuitems' => [
		            'label' => 'lang:thoughtco.outofstock::default.button_menuoptions',
		            'class' => 'btn btn-secondary',
		            'href' => 'thoughtco/outofstock/menuitems',
		        ],
            ],
        ],
		'filter' => [
            'search' => [
                'prompt' => 'lang:thoughtco.outofstock::default.label_filter_search',
                'mode' => 'all',
            ],
		],
        'columns' => [
			'name' => [
                'label' => 'lang:thoughtco.outofstock::default.column_name',
                'type' => 'text',
                'sortable' => TRUE,
            ],
			'category_id' => [
				'label' => '',
				'type' => 'text',
				'sortable' => FALSE,
				'formatter' => function ($record, $column, $value) use ($cache_menus) {
					if ($cache_menus->contains($value))
                        return '<a class="btn btn-success" href="'.admin_url('thoughtco/outofstock/categories/stock/'.$value).'">'.__('lang:thoughtco.outofstock::default.button_stock').'</a>';

                    return '<button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">'.lang('lang:thoughtco.outofstock::default.button_nostock').'</button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							'.(collect(Settings::get('delay_times', []))->map(function ($delay) use ($value) {
								return '<a class="dropdown-item" href="'.admin_url('thoughtco/outofstock/categories/nostock/'.$value.'?hours='.$delay['time']).'">'.$delay['label'].'</a>';
							})->join(' ')).'
						    <a class="dropdown-item" href="'.admin_url('thoughtco/outofstock/categories/nostock/'.$value).'?hours=closing">'.lang('thoughtco.outofstock::default.button_closing').'</a>
						    <a class="dropdown-item" href="'.admin_url('thoughtco/outofstock/categories/nostock/'.$value).'">'.lang('thoughtco.outofstock::default.button_forever').'</a>
						  </div>';
                }
			],

        ],
    ],
];
