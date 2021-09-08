<?php

return [
    'list' => [
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
				'type' => 'partial',
				'sortable' => FALSE,
                'path' => 'form/stock_column',
// 				'formatter' => function ($record, $column, $value) use ($cache_menus) {
// 					if ($cache_menus->contains($value))
//                         return '<a class="btn font-weight-bold p-0 text-danger" href="'.admin_url('thoughtco/outofstock/menuoptions/stock/'.$value).'">'.__('lang:thoughtco.outofstock::default.button_stock').'</a>';
//
//                     return '<button class="btn font-weight-bold p-0 dropdown-toggle text-secondary" type="button" data-toggle="dropdown">'.lang('lang:thoughtco.outofstock::default.button_nostock').'</button>
// 						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
// 							'.(collect(Settings::get('delay_times', []))->map(function ($delay) use ($value) {
// 								return '<a class="dropdown-item" href="'.admin_url('thoughtco/outofstock/menuoptions/nostock/'.$value.'?hours='.$delay['time']).'">'.$delay['label'].'</a>';
// 							})->join(' ')).'
// 						    <a class="dropdown-item" href="'.admin_url('thoughtco/outofstock/menuitems/menuoptions/'.$value).'?hours=closing">'.lang('thoughtco.outofstock::default.button_closing').'</a>
// 						    <a class="dropdown-item" href="'.admin_url('thoughtco/outofstock/menuitems/menuoptions/'.$value).'">'.lang('thoughtco.outofstock::default.button_forever').'</a>
// 						  </div>';
//                 }
			],

        ],
    ],
];
