<?php

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
		            'href' => 'thoughtco/outofstock/categories',
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
					return $cache_menus->contains($value) ? '<a class="btn btn-success" href="'.admin_url('thoughtco/outofstock/categories/stock/'.$value).'">'.__('lang:thoughtco.outofstock::default.button_stock').'</a>' : '<a class="btn btn-danger" href="'.admin_url('thoughtco/outofstock/categories/nostock/'.$value).'">'.__('lang:thoughtco.outofstock::default.button_nostock').'</a>';
				}
			],

        ],
    ],
];
