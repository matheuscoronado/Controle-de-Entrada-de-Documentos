<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAuditoria extends Model
{
    public $timestamps = false;
    protected $fillable = ['usuario_id', 'acao', 'tabela_afetada', 'registro_id', 'ip_origem', 'data_hora'];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}