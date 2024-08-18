<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evolucao extends Model
{
    protected $table = 'evolucao';
    protected $fillable = ['id', 'paciente', 'peso', 'data'];
    protected $hidden = [
        'id',
    ];
}