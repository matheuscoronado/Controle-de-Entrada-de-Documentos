<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogAuditoria extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'usuario_id','acao','modulo','tabela_afetada','registro_id',
        'status_anterior','status_novo','campos_alterados','uploads_realizados',
        'descricao_legivel','ip_origem','user_agent','data_hora',
    ];

    protected $casts = [
        'campos_alterados'   => 'array',
        'uploads_realizados' => 'array',
        'data_hora'          => 'datetime',
    ];

    public function usuario() { return $this->belongsTo(User::class); }

    public static function registrar(string $acao, string $tabela, ?int $registroId = null, array $extras = []): self
    {
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

    public function scopeDoModulo($q, string $m)  { return $q->where('modulo', $m); }
    public function scopeDoUsuario($q, int $id)   { return $q->where('usuario_id', $id); }
    public function scopeNoPeriodo($q, $i, $f)    { return $q->whereBetween('data_hora', [$i, $f]); }
}
