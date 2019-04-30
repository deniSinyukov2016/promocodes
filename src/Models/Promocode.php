<?php

namespace Itlead\Promocodes\Models;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $guarded = ['id'];

    protected $dates   = ['expires_at'];
  
    protected $table   = config('promocodes.table', 'promocodes');
}
