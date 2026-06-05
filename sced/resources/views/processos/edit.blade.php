{{-- ============================================================
     resources/views/processos/edit.blade.php — PARTE 3
     Edição de dados do processo (disponível em 'novo' e 'pendente')
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Processo')
@section('subtitle', $documento->numero_protocolo . ' — ' . $documento->tipoDocumento->nome)

@section('topbar-actions')
    <a href="{{ route('documentos.show', $documento) }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

    @if($documento->status === 'pendente')
    <div style="background:#fef3c7;border:1.5px solid #fde68a;border-radius:var(--radius-sm);padding:14px 18px;margin-bottom:20px;font-size:13px;color:#92400e;">
        ⚠️ <strong>Processo pendente.</strong> Faça as correções necessárias e reenvie pelo botão no detalhe do processo.
        @if($documento->motivo_pendencia)
            <div style="margin-top:6px;font-style:italic;">"{{ $documento->motivo_pendencia }}"</div>
        @endif
    </div>
    @endif

    <div class="card-sced card-body-sced">
        <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">✏️ Editar Dados do Processo</strong>

        <form method="POST" action="{{ route('documentos.update', $documento) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label-sced">Solicitante / Remetente *</label>
                    <input type="text" name="remetente" class="form-input-sced @error('remetente') is-invalid @enderror"
                           value="{{ old('remetente', $documento->remetente) }}" required>
                    @error('remetente')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label-sced">Setor de Destino *</label>
                    <input type="text" name="setor_destino" class="form-input-sced @error('setor_destino') is-invalid @enderror"
                           value="{{ old('setor_destino', $documento->setor_destino) }}" required>
                    @error('setor_destino')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label-sced">Descrição / Observações</label>
                    <textarea name="descricao" class="form-input-sced" rows="4">{{ old('descricao', $documento->descricao) }}</textarea>
                </div>
            </div>

            {{-- Campos bloqueados (só para exibição) --}}
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--cinza-200);">
                <div style="font-size:12px;font-weight:600;color:var(--cinza-400);text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">
                    🔒 Campos não editáveis
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label-sced">Protocolo</label>
                        <input type="text" class="form-input-sced" value="{{ $documento->numero_protocolo }}" readonly style="background:var(--cinza-100);color:var(--cinza-400);">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sced">Serviço</label>
                        <input type="text" class="form-input-sced" value="{{ $documento->tipoDocumento->nome }}" readonly style="background:var(--cinza-100);color:var(--cinza-400);">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sced">Data de Abertura</label>
                        <input type="text" class="form-input-sced" value="{{ \Carbon\Carbon::parse($documento->data_recebimento)->format('d/m/Y') }}" readonly style="background:var(--cinza-100);color:var(--cinza-400);">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sced">Status Atual</label>
                        <input type="text" class="form-input-sced" value="{{ $documento->label_status }}" readonly style="background:var(--cinza-100);color:var(--cinza-400);">
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--cinza-200);">
                <a href="{{ route('documentos.show', $documento) }}" class="btn-secondary-sced">Cancelar</a>
                <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
            </div>
        </form>
    </div>

</div>
</div>
@endsection

@push('styles')
<style>
.form-label-sced { font-size:12px;font-weight:600;color:var(--cinza-600);text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px;display:block; }
.form-error { font-size:12px;color:var(--vermelho);margin-top:4px; }
</style>
@endpush
