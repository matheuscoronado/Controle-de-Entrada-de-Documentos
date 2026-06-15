<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogAuditoria extends Model
{
    // ⭐ HABILITAR TIMESTAMPS (created_at e updated_at)
    public $timestamps = true;
    
    // ⭐ NOME DA TABELA
    protected $table = 'log_auditorias';

    // ⭐ CAMPOS QUE PODEM SER PREENCHIDOS EM MASSA
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

    // ⭐ CASTS PARA CONVERSÃO AUTOMÁTICA DE TIPOS
    protected $casts = [
        'campos_alterados'   => 'array',
        'uploads_realizados' => 'array',
        'data_hora'          => 'datetime',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    /**
     * Relacionamento com o usuário
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Registrar uma ação de auditoria
     * 
     * @param string $acao Ação realizada (ex: CRIAR, EDITAR, EXCLUIR)
     * @param string $modulo Módulo afetado (ex: documentos, usuarios)
     * @param int|null $registroId ID do registro afetado
     * @param array $dados Dados adicionais
     * @return self
     */
    public static function registrar(string $acao, string $modulo, ?int $registroId, array $dados = [])
    {
        return self::create([
            'usuario_id' => auth()->id(),
            'acao' => $acao,
            'modulo' => $modulo,
            'tabela_afetada' => $dados['tabela_afetada'] ?? $modulo,
            'registro_id' => $registroId,
            'status_anterior' => $dados['status_anterior'] ?? null,
            'status_novo' => $dados['status_novo'] ?? null,
            'campos_alterados' => $dados['campos_alterados'] ?? null,
            'uploads_realizados' => $dados['uploads_realizados'] ?? null,
            'descricao_legivel' => $dados['descricao_legivel'] ?? null,
            'ip_origem' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data_hora' => now(),
        ]);
    }

    /**
     * Escopo para filtrar por módulo
     */
    public function scopeDoModulo($query, string $modulo)
    {
        return $query->where('modulo', $modulo);
    }

    /**
     * Escopo para filtrar por usuário
     */
    public function scopeDoUsuario($query, int $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    /**
     * Escopo para filtrar por período
     */
    public function scopeNoPeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_hora', [$dataInicio, $dataFim]);
    }

    /**
     * Escopo para filtrar por ação
     */
    public function scopePorAcao($query, string $acao)
    {
        return $query->where('acao', 'like', '%' . $acao . '%');
    }

    /**
     * Accessor para descrição legível formatada
     */
    public function getDescricaoFormatadaAttribute(): string
    {
        if ($this->descricao_legivel) {
            return $this->descricao_legivel;
        }
        
        $usuarioNome = $this->usuario ? $this->usuario->nome : 'Sistema';
        return "{$usuarioNome} realizou {$this->acao} em {$this->modulo}";
    }

    /**
     * Accessor para data formatada
     */
    public function getDataFormatadaAttribute(): string
    {
        return $this->data_hora ? $this->data_hora->format('d/m/Y H:i:s') : '';
    }

    /**
     * Mutator para garantir que data_hora seja sempre definida
     */
    public function setDataHoraAttribute($value)
    {
        $this->attributes['data_hora'] = $value ?? now();
    }
}