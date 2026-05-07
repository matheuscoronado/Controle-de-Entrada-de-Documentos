<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ['nome'];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'departamento_id');
    }
}