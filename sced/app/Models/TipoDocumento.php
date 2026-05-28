<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'obrigatoriedade',
        'departamento_destino_id',
        'cargo_responsavel',
        'sla_horas',
        'status',
    ];

    // ── Relacionamentos ──────────────────────────────────────

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function departamentoDestino()
    {
        return $this->belongsTo(Departamento::class, 'departamento_destino_id');
    }

    // ── Helpers ──────────────────────────────────────────────

    public function isObrigatorio(): bool
    {
        return $this->obrigatoriedade === 'obrigatorio';
    }

    public function getLabelObrigatoriedadeAttribute(): string
    {
        return $this->obrigatoriedade === 'obrigatorio' ? 'Obrigatório' : 'Opcional';
    }

    public function getLabelSlaAttribute(): string
    {
        if (! $this->sla_horas) return '—';
        if ($this->sla_horas < 24) return "{$this->sla_horas}h";
        $dias = intdiv($this->sla_horas, 24);
        $horas = $this->sla_horas % 24;
        return $horas > 0 ? "{$dias}d {$horas}h" : "{$dias} dia(s)";
    }
}
