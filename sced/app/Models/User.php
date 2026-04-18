<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nome', 'email', 'password', 'perfil', 'status'];

    protected $hidden = ['password', 'remember_token'];

    public function documentosRegistrados()
    {
        return $this->hasMany(Documento::class, 'usuario_registro_id');
    }

    public function historicos()
    {
        return $this->hasMany(HistoricoMovimentacao::class, 'usuario_id');
    }

    public function isAdmin(): bool
    {
        return $this->perfil === 'administrador';
    }
}