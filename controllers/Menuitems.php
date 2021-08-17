<?php

namespace Thoughtco\Outofstock\Controllers;

use AdminMenu;
use Admin\Facades\AdminLocation;
use Admin\Models\Menu_option_values_model;
use ApplicationException;
use Event;
use Redirect;
use Template;
use Thoughtco\Outofstock\Models\Outofstock;

class Menuitems extends \Admin\Classes\AdminController
{
    public $implement = [
        'Admin\Actions\ListController',
    ];

    public $listConfig = [
        'list' => [
            'model' => 'Admin\Models\Menu_option_values_model',
            'title' => 'lang:thoughtco.outofstock::default.text_title',
            'emptyMessage' => 'lang:thoughtco.outofstock::default.text_empty',
            'defaultSort' => ['value', 'ASC'],
            'configFile' => 'menuitems',
            'showCheckboxes' => FALSE,
        ],
    ];

    protected $requiredPermissions = 'Thoughtco.Outofstock.*';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('restaurant', 'outofstock');
        Template::setTitle(lang('lang:thoughtco.outofstock::default.text_title'));
    }

    public function index()
    {
        $this->vars['noLocation'] = false;

        if (!AdminLocation::getId())
            $this->vars['noLocation'] = true;

        Event::listen('admin.list.extendQuery', function($listWidget, $query) {
            $query->whereHas('option', function($subquery) {
                $subquery->whereHasOrDoesntHaveLocation(AdminLocation::getId());
            });
        });

        $this->asExtension('ListController')->index();
    }

    public function stock($context, $id = null)
    {
        if (!$id)
            abort(404);

        $this->checkMenuItemExists($id);

        $params = [
            'type' => 'menuitems',
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
            'type' => 'menuitems',
            'type_id' => $id,
            'location_id' => AdminLocation::getId(),
        ];

        if (!Outofstock::where($params)->count())
            $model = Outofstock::create($params)->save();

        return Redirect::back();
    }

    private function checkMenuItemExists($id)
    {
        if (!Menu_option_values_model::find($id))
            abort(404);
    }
}
