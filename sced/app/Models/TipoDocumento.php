<?php
// app/Models/TipoDocumento.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'status',
        'departamento_destino_id',
        'cargos_responsaveis',
    ];

    protected $casts = [
        'cargos_responsaveis' => 'array',
    ];

    // ── Relacionamentos ───────────────────────────────────────

    /** Processos que usam este serviço */
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    /** Setor de destino */
    public function departamentoDestino()
    {
        return $this->belongsTo(Departamento::class, 'departamento_destino_id');
    }

    /**
     * Documentos vinculados a este serviço (ex: RG, CPF, Certidão).
     * Tabela pivot: tipo_documento_documento_tipo
     */
    public function documentosTipo()
    {
        return $this->belongsToMany(
            DocumentoTipo::class,
            'tipo_documento_documento_tipo',
            'tipo_documento_id',
            'documento_tipo_id'
        );
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Retorna os cargos como array garantido (nunca null).
     */
    public function getCargosArrayAttribute(): array
    {
        return $this->cargos_responsaveis ?? [];
    }

    /**
     * Rótulo legível dos cargos responsáveis.
     * Ex: "N1 — Atendimento, N2 — Analista"
     */
    public function getLabelCargosAttribute(): string
    {
        $mapa = [
            'N1' => 'N1 — Atendimento',
            'N2' => 'N2 — Analista',
            'N3' => 'N3 — Supervisor',
        ];

        $cargos = $this->cargos_responsaveis ?? [];

        if (empty($cargos)) {
            return '—';
        }

        return implode(', ', array_map(fn($c) => $mapa[$c] ?? $c, $cargos));
    }

    /**
     * Verifica se o serviço está ativo
     */
    public function isAtivo(): bool
    {
        return $this->status === 'ativo';
    }
}