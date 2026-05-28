<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    /**
     * Relacionamento: Um departamento possui muitos usuários.
     */
    public function usuarios()
    {
        // Certifique-se de que a coluna na tabela 'users' se chama 'departamento_id'
        return $this->hasMany(User::class, 'departamento_id');
    }
}