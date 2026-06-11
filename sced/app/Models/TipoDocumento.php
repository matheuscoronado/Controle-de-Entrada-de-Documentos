<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = ['nome','descricao','status','obrigatoriedade','departamento_destino_id','cargo_responsavel','sla_horas'];

    public function documentos()          { return $this->hasMany(Documento::class); }
    public function departamentoDestino() { return $this->belongsTo(Departamento::class, 'departamento_destino_id'); }

    // Documentos necessários para este serviço (via pivot)
    public function documentosTipo()
    {
        return $this->belongsToMany(DocumentoTipo::class, 'tipo_documento_documento_tipo', 'tipo_documento_id', 'documento_tipo_id');
    }

    public function getLabelSlaAttribute(): string
    {
        if (!$this->sla_horas) return '—';
        if ($this->sla_horas < 24) return "{$this->sla_horas}h";
        $dias = intdiv($this->sla_horas, 24);
        $h    = $this->sla_horas % 24;
        return $h > 0 ? "{$dias}d {$h}h" : "{$dias} dia(s)";
    }

    public function getLabelObrigatoriedadeAttribute(): string
    {
        return $this->obrigatoriedade === 'obrigatorio' ? 'Obrigatório' : 'Opcional';
    }
}