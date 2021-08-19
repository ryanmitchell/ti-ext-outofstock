<?php

return [
    'form' => [
        'toolbar' => [
            'buttons' => [
                'save' => [
                    'label' => 'lang:admin::lang.button_save',
                    'class' => 'btn btn-primary',
                    'data-request' => 'onSave'
                ],
                'saveClose' => [
                    'label' => 'lang:admin::lang.button_save_close',
                    'class' => 'btn btn-default',
                    'data-request' => 'onSave',
                    'data-request-data' => 'close:1',
                ],
            ],
        ],
        'fields' => [
            'delay_times' => [
                'label' => 'lang:thoughtco.outofstock::default.label_delay_times',
                'type' => 'repeater',
                'form' => [
                    'fields' => [
                        'label' => [
                            'label' => 'lang:thoughtco.outofstock::default.label_delay_label',
                            'type' => 'text',
                        ],
                        'time' => [
                            'label' => 'lang:thoughtco.outofstock::default.label_delay_amount',
                            'type' => 'number',
                            'default' => 5,
                        ],
                    ]
                ],
            ],
        ],
        'tabs' => [],
        'rules' => [
        ]
    ],
];
