<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArquivoAnexo extends Model
{
    use HasFactory;

    protected $table = 'arquivo_anexos';

    public $timestamps = false;

    protected $fillable = [
        'documento_id', 
        'usuario_id', 
        'nome_arquivo', 
        'caminho_arquivo', 
        'tipo_mime', 
        'tamanho_bytes'
    ];

    /**
     * Relacionamento com o Documento
     */
    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    /**
     * Relacionamento com o Usuário que fez o upload
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}