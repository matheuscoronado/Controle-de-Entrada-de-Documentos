{{-- resources/views/documentos/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Novo Documento')
@section('subtitle', 'Registre a entrada de um novo documento')

@section('topbar-actions')
    <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">
        ← Voltar
    </a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card-sced">
            <div class="card-header-sced">
                <div>
                    <strong style="font-size:16px; color:var(--azul-escuro);">📄 Dados do Documento</strong>
                    <div style="font-size:13px; color:var(--cinza-400); margin-top:3px;">
                        O protocolo será gerado automaticamente ao salvar.
                    </div>
                </div>
            </div>
            <div class="card-body-sced">
                <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label-sced">Tipo de Documento *</label>
                                <select name="tipo_documento_id"
                                        class="form-input-sced {{ $errors->has('tipo_documento_id') ? 'is-invalid' : '' }}"
                                        required>
                                    <option value="">Selecione o tipo...</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}"
                                            {{ old('tipo_documento_id') == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento_id')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label-sced">Data de Recebimento *</label>
                                <input type="date" name="data_recebimento"
                                       class="form-input-sced {{ $errors->has('data_recebimento') ? 'is-invalid' : '' }}"
                                       value="{{ old('data_recebimento', date('Y-m-d')) }}"
                                       required>
                                @error('data_recebimento')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label-sced">Remetente *</label>
                                <input type="text" name="remetente"
                                       class="form-input-sced {{ $errors->has('remetente') ? 'is-invalid' : '' }}"
                                       value="{{ old('remetente') }}"
                                       placeholder="Nome ou órgão de origem"
                                       required>
                                @error('remetente')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label-sced">Setor de Destino *</label>
                                <input type="text" name="setor_destino"
                                       class="form-input-sced {{ $errors->has('setor_destino') ? 'is-invalid' : '' }}"
                                       value="{{ old('setor_destino') }}"
                                       placeholder="Ex: RH, Financeiro, Diretoria"
                                       required>
                                @error('setor_destino')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label-sced">Assunto *</label>
                                <input type="text" name="assunto"
                                       class="form-input-sced {{ $errors->has('assunto') ? 'is-invalid' : '' }}"
                                       value="{{ old('assunto') }}"
                                       placeholder="Descreva brevemente o assunto do documento"
                                       required>
                                @error('assunto')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label-sced">Descrição complementar</label>
                                <textarea name="descricao"
                                          class="form-input-sced"
                                          rows="4"
                                          placeholder="Informações adicionais sobre o documento (opcional)...">{{ old('descricao') }}</textarea>
                            </div>
                        </div>

                        {{-- FIX 3: Múltiplos anexos --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label-sced">Arquivos Anexos (opcional)</label>

                                <div class="upload-area" onclick="document.getElementById('inputAnexos').click()">
                                    <span class="upload-icon">📎</span>
                                    <div class="upload-text" id="uploadLabel">
                                        Clique para selecionar ou arraste os arquivos aqui
                                    </div>
                                    <div style="font-size:11px; color:var(--cinza-400); margin-top:4px;">
                                        PDF, DOC, DOCX, JPG, PNG — máximo 10MB por arquivo — pode selecionar vários
                                    </div>
                                </div>

                                {{-- multiple e name="anexos[]" para suportar múltiplos arquivos --}}
                                <input type="file"
                                       id="inputAnexos"
                                       name="anexos[]"
                                       multiple
                                       style="display:none"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       onchange="mostrarArquivos(this)">

                                {{-- Lista de arquivos selecionados --}}
                                <div id="listaArquivos" style="margin-top:10px; display:none;">
                                    <div style="font-size:12px; color:var(--cinza-600); font-weight:600; margin-bottom:6px;">
                                        Arquivos selecionados:
                                    </div>
                                    <div id="arquivosItens"></div>
                                </div>

                                @error('anexos')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                                @error('anexos.*')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                        <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary-sced">
                            💾 Registrar Documento
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function mostrarArquivos(input) {
    const lista = document.getElementById('listaArquivos');
    const itens = document.getElementById('arquivosItens');
    const label = document.getElementById('uploadLabel');

    if (input.files.length === 0) {
        lista.style.display = 'none';
        label.textContent = 'Clique para selecionar ou arraste os arquivos aqui';
        return;
    }

    const total = input.files.length;
    label.textContent = total === 1
        ? '1 arquivo selecionado'
        : total + ' arquivos selecionados';

    itens.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const tamanho = (file.size / 1024).toFixed(1) + ' KB';
        const div = document.createElement('div');
        div.style.cssText = 'display:flex;align-items:center;gap:8px;padding:7px 12px;background:var(--cinza-100);border-radius:6px;margin-bottom:5px;font-size:13px;';
        div.innerHTML = '<span>📄</span><span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + file.name + '</span><span style="font-size:11px;color:var(--cinza-400);">' + tamanho + '</span>';
        itens.appendChild(div);
    });

    lista.style.display = 'block';
}
</script>
@endpush
