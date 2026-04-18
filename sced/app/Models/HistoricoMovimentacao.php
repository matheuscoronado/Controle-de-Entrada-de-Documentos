<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoMovimentacao extends Model
{
    public $timestamps = false;
    protected $fillable = ['documento_id', 'usuario_id', 'status_anterior', 'status_novo', 'observacoes', 'data_hora'];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}