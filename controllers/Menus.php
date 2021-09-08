<?php

namespace Thoughtco\Outofstock\Controllers;

use AdminMenu;
use Admin\Facades\AdminLocation;
use Admin\Models\Menus_model;
use ApplicationException;
use Carbon\Carbon;
use Redirect;
use Template;
use Thoughtco\Outofstock\Models\Outofstock;

class Menus extends \Admin\Classes\AdminController
{
    public $implement = [
        'Admin\Actions\ListController',
        'Admin\Actions\LocationAwareController',
    ];

    public $listConfig = [
        'list' => [
            'model' => 'Admin\Models\Menus_model',
            'title' => 'lang:thoughtco.outofstock::default.text_title',
            'emptyMessage' => 'lang:thoughtco.outofstock::default.text_empty',
            'defaultSort' => ['menu_name', 'ASC'],
            'configFile' => 'menus',
            'showCheckboxes' => FALSE,
        ],
    ];

    protected $requiredPermissions = 'Thoughtco.Outofstock.*';

    public function stock($context, $id = null)
    {
        if (!$id)
            abort(404);

        $this->checkMenuItemExists($id);

        $params = [
            'type' => 'menus',
            'type_id' => $id,
            'location_id' => AdminLocation::getId(),
        ];

        Outofstock::where($params)->each(function($model) {
            $model->delete();
        });

        return Redirect::back();
    }

    public function nostock($context, $id = null)
    {
        if (!$id)
            abort(404);

        $this->checkMenuItemExists($id);

        $params = [
            'type' => 'menus',
            'type_id' => $id,
            'location_id' => AdminLocation::getId(),
        ];

        if (!Outofstock::where($params)->count()) {

            $hours = request('hours', '');
            if ($hours != '' AND $hours != 'forever') {
                if ($hours != 'closing') {
                    $params['timeout'] = Carbon::now()->addHours((int)$hours)->format('Y-m-d H:i:s');
                } else {
                    if ($closing = AdminLocation::current()->newWorkingSchedule('opening')->getCloseTime())
                        $params['timeout'] = $closing->format('Y-m-d H:i:s');
                }
            }

            $model = Outofstock::create($params)->save();
        }

        return Redirect::back();
    }

    private function checkMenuItemExists($id)
    {
        if (!Menus_model::find($id))
            abort(404);
    }
}
