<?php

namespace Thoughtco\Outofstock\Models;

use Model;

class Outofstock extends Model
{
    protected $table = 'thoughtco_outofstock';

    protected $fillable = ['type', 'type_id', 'location_id', 'timeout', ];
}
