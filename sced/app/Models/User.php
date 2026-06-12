<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nome','email','password','perfil','status','departamento_id','cargo','pode_assumir'];

    protected $casts = [
        'pode_assumir' => 'boolean',
    ];
    protected $hidden   = ['password','remember_token'];

    // Relacionamentos
    public function departamentoRelacionado(): BelongsTo { return $this->belongsTo(Departamento::class, 'departamento_id'); }
    public function documentosRegistrados()  { return $this->hasMany(Documento::class, 'usuario_registro_id'); }
    public function historicos()             { return $this->hasMany(HistoricoMovimentacao::class, 'usuario_id'); }
    public function logs()                   { return $this->hasMany(LogAuditoria::class, 'usuario_id'); }

    // Helpers de perfil
    public function isAdmin(): bool           { return $this->perfil === 'administrador'; }
    public function isN3(): bool              { return $this->perfil === 'n3' || $this->cargo === 'N3'; }
    public function isOperador(): bool        { return $this->perfil === 'operador'; }
    public function podeAcessarAdmin(): bool  { return $this->isAdmin() || $this->isN3(); }

    /**
     * Verifica se o usuário tem permissão habilitada para assumir processos.
     * Admin e N3 sempre podem. Operadores dependem da flag pode_assumir.
     */
    public function podeAssumirProcesso(): bool
    {
        if ($this->isAdmin() || $this->isN3()) {
            return true;
        }

        return (bool) $this->pode_assumir;
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
