<?php

$cache_menus = \Thoughtco\Outofstock\Models\Outofstock::where([
    'type' => 'menuitems',
    'location_id' => \AdminLocation::getId(),
])->get()->pluck('type_id');

return [
    'list' => [
        'toolbar' => [
            'buttons' => [
		        'menuitems' => [
		            'label' => 'lang:thoughtco.outofstock::default.button_menuitems',
		            'class' => 'btn btn-secondary',
		            'href' => 'thoughtco/outofstock/menus',
		        ],
		        'categories' => [
		            'label' => 'lang:thoughtco.outofstock::default.button_categories',
		            'class' => 'btn btn-secondary',
		            'href' => 'thoughtco/outofstock/categories',
		        ],
            ],
        ],
		'filter' => [
            'search' => [
                'prompt' => 'lang:thoughtco.outofstock::default.label_filter_search_options',
                'mode' => 'all',
            ],
		],
        'columns' => [
			'option' => [
                'label' => 'lang:thoughtco.outofstock::default.column_option',
                'type' => 'text',
                'sortable' => TRUE,
                'relation' => 'option',
                'select' => 'option_name',
            ],
			'value' => [
                'label' => 'lang:thoughtco.outofstock::default.column_name',
                'type' => 'text',
                'sortable' => TRUE,
            ],
			'option_value_id' => [
				'label' => '',
				'type' => 'text',
				'sortable' => FALSE,
				'formatter' => function ($record, $column, $value) use ($cache_menus) {
					return $cache_menus->contains($value) ? '<a class="btn btn-success" href="'.admin_url('thoughtco/outofstock/menuitems/stock/'.$value).'">'.__('lang:thoughtco.outofstock::default.button_stock').'</a>' : '<a class="btn btn-danger" href="'.admin_url('thoughtco/outofstock/menuitems/nostock/'.$value).'">'.__('lang:thoughtco.outofstock::default.button_nostock').'</a>';
				}
			],

        ],
    ],
];
