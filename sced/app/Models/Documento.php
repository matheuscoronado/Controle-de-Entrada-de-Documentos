<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'numero_protocolo', 'tipo_documento_id', 'usuario_registro_id',
        'remetente', 'assunto', 'descricao', 'setor_destino',
        'status', 'data_recebimento'
    ];

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'usuario_registro_id');
    }

    public function historicos()
    {
        return $this->hasMany(HistoricoMovimentacao::class);
    }

    public function anexos()
    {
        return $this->hasMany(ArquivoAnexo::class);
    }

    // Gera o número de protocolo: 2026-000001
    public static function gerarProtocolo(): string
    {
        $ano = date('Y');
        $ultimo = self::whereYear('created_at', $ano)->count();
        return $ano . '-' . str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);
    }
}