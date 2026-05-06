<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome', 
        'email', 
        'password', 
        'perfil', 
        'status', 
        'departamento', // Campo adicionado
        'cargo'         // Campo adicionado
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    /**
     * Relacionamento: Documentos registrados pelo usuário
     */
    public function documentosRegistrados()
    {
        return $this->hasMany(Documento::class, 'usuario_registro_id');
    }

    /**
     * Relacionamento: Histórico de movimentações do usuário
     */
    public function historicos()
    {
        return $this->hasMany(HistoricoMovimentacao::class, 'usuario_id');
    }

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin(): bool
    {
        return $this->perfil === 'administrador';
    }
}