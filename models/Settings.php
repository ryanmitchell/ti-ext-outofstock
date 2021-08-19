<?php

namespace Thoughtco\OutOfStock\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System\Actions\SettingsModel'];

    // A unique code
    public $settingsCode = 'thoughtco_outofstock_settings';

    // Reference to field configuration
    public $settingsFieldsConfig = 'settings';
}
