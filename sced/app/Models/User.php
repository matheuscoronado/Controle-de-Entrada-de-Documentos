<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importante para o relacionamento

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nome', 
        'email', 
        'password', 
        'perfil', 
        'status', 
        'departamento_id', // ALTERADO: agora aponta para o ID da tabela departamentos
        'cargo'
    ];

    /**
     * Atributos ocultos.
     */
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    /**
     * Relacionamento: O Usuário PERTENCE a um Departamento
     */
    public function departamentoRelacionado(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

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