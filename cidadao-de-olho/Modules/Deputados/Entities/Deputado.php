<?php

namespace Modules\Deputados\Entities;

use Illuminate\Database\Eloquent\Model;

class Deputado extends Model
{
    protected $fillable = ['Nome','Partido','Identificador','anoMandato'];
}
