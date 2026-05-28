<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome',
        'email',
        'password',
        'perfil',
        'status',
        'departamento_id',
        'cargo',
    ];

    protected $hidden = ['password', 'remember_token'];

    // ── Relacionamentos ──────────────────────────────────────

    public function departamentoRelacionado(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function documentosRegistrados()
    {
        return $this->hasMany(Documento::class, 'usuario_registro_id');
    }

    public function historicos()
    {
        return $this->hasMany(HistoricoMovimentacao::class, 'usuario_id');
    }

    public function logs()
    {
        return $this->hasMany(LogAuditoria::class, 'usuario_id');
    }

    // ── Helpers de Perfil ────────────────────────────────────

    /** Acesso total ao sistema */
    public function isAdmin(): bool
    {
        return $this->perfil === 'administrador';
    }

    /**
     * Nível N3: supervisor — pode ver tudo, fechar chamados,
     * acessar relatórios. NÃO gerencia usuários nem configurações.
     */
    public function isN3(): bool
    {
        return $this->perfil === 'n3' || $this->cargo === 'N3';
    }

    /** Operadores N1/N2: registro e acompanhamento básico */
    public function isOperador(): bool
    {
        return $this->perfil === 'operador';
    }

    /**
     * Verifica se o usuário pode acessar a área administrativa.
     * Admin e N3 têm acesso (com diferentes escopos).
     */
    public function podeAcessarAdmin(): bool
    {
        return $this->isAdmin() || $this->isN3();
    }

    public function getLabelPerfilAttribute(): string
    {
        return match($this->perfil) {
            'administrador' => 'Administrador',
            'n3'            => 'Supervisor N3',
            default         => 'Operador',
        };
    }
}
