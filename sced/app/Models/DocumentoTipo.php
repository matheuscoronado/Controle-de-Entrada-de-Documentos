<?php
// app/Models/DocumentoTipo.php
// Representa um tipo de documento cadastrado (ex: RG, CPF, Certidão de Casamento)
// Diferente de TipoDocumento (que é o "Serviço").

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoTipo extends Model
{
    protected $table = 'documento_tipos';

    protected $fillable = ['nome', 'descricao', 'tipo', 'status'];

    // Um DocumentoTipo pode estar vinculado a muitos Serviços (TipoDocumento) via pivot
    public function servicos()
    {
        return $this->belongsToMany(TipoDocumento::class, 'tipo_documento_documento_tipo', 'documento_tipo_id', 'tipo_documento_id');
    }

    public function getLabelTipoAttribute(): string
    {
        return $this->tipo === 'obrigatorio' ? 'Obrigatório' : 'Opcional';
    }
}