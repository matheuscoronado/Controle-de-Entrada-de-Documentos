<?php
// app/Models/ArquivoAnexo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArquivoAnexo extends Model
{
    protected $table    = 'arquivo_anexos';
    public    $timestamps = false;

    protected $fillable = [
        'documento_id', 'usuario_id',
        'tipo_anexo', 'status_validacao', 'observacao_validacao',
        'validado_por', 'validado_em',
        'nome_arquivo', 'caminho_arquivo', 'tipo_mime', 'tamanho_bytes',
    ];

    protected $casts = ['validado_em' => 'datetime'];

    // ── Labels legíveis ──────────────────────────────────────

    /** Mapa completo dos tipos de anexo disponíveis para o Select do formulário */
    public static array $tiposAnexo = [
        'rg'                     => 'RG — Documento de Identidade',
        'cpf'                    => 'CPF',
        'contrato'               => 'Contrato',
        'comprovante_residencia' => 'Comprovante de Residência',
        'comprovante_renda'      => 'Comprovante de Renda',
        'certidao'               => 'Certidão',
        'laudo'                  => 'Laudo / Parecer Técnico',
        'outros'                 => 'Outros',
    ];

    public function getLabelTipoAnexoAttribute(): string
    {
        return self::$tiposAnexo[$this->tipo_anexo] ?? 'Outros';
    }

    public function getLabelStatusValidacaoAttribute(): string
    {
        return match($this->status_validacao) {
            'aprovado'  => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            default     => 'Pendente',
        };
    }

    // ── Relacionamentos ──────────────────────────────────────

    public function documento()    { return $this->belongsTo(Documento::class); }
    public function usuario()      { return $this->belongsTo(User::class, 'usuario_id'); }
    public function validadoPor()  { return $this->belongsTo(User::class, 'validado_por'); }
}
