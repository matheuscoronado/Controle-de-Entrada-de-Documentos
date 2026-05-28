<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogAuditoria extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'acao',
        'modulo',
        'tabela_afetada',
        'registro_id',
        'status_anterior',
        'status_novo',
        'campos_alterados',
        'uploads_realizados',
        'descricao_legivel',
        'ip_origem',
        'user_agent',
        'data_hora',
    ];

    protected $casts = [
        'campos_alterados'   => 'array',
        'uploads_realizados' => 'array',
        'data_hora'          => 'datetime',
    ];

    // ── Relacionamentos ──────────────────────────────────────

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // ── Factory method (facilita o registro no sistema) ──────

    /**
     * Registra uma entrada de auditoria de forma centralizada.
     *
     * Exemplo de uso no controller:
     *   LogAuditoria::registrar('ATUALIZAR_STATUS', 'documentos', $doc->id, [
     *       'status_anterior'    => $anterior,
     *       'status_novo'        => $novo,
     *       'descricao_legivel'  => "Status alterado de {$anterior} para {$novo}",
     *       'campos_alterados'   => ['status' => ['de' => $anterior, 'para' => $novo]],
     *   ]);
     */
    public static function registrar(
        string $acao,
        string $tabela,
        ?int $registroId = null,
        array $extras = []
    ): self {
        return self::create(array_merge([
            'usuario_id'     => Auth::id(),
            'acao'           => strtoupper($acao),
            'modulo'         => $extras['modulo'] ?? null,
            'tabela_afetada' => $tabela,
            'registro_id'    => $registroId,
            'ip_origem'      => Request::ip(),
            'user_agent'     => Request::userAgent(),
            'data_hora'      => now(),
        ], $extras));
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeDoModulo($query, string $modulo)
    {
        return $query->where('modulo', $modulo);
    }

    public function scopeDoUsuario($query, int $userId)
    {
        return $query->where('usuario_id', $userId);
    }

    public function scopeNoPeriodo($query, string $inicio, string $fim)
    {
        return $query->whereBetween('data_hora', [$inicio, $fim]);
    }
}
