<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArquivoAnexo extends Model
{
    public $timestamps = false;
    protected $fillable = ['documento_id', 'usuario_id', 'nome_arquivo', 'caminho_arquivo', 'tipo_mime', 'tamanho_bytes'];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }
}