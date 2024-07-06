<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = ['nutri_id', 'user_id', 'nome', 'idade', 'fa', 'sexo', 'peso', 'altura', 'anaminesia'];

    protected $hidden = ['nutri_id', 'user_id'];
}

