{{-- ============================================================
     resources/views/admin/tipos/create.blade.php
     CRIAR SERVIÇO - PADRÃO MODERNO
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Serviço')
@section('subtitle', 'Cadastre um novo serviço no sistema')

@section('topbar-actions')
    <a href="{{ route('tipos.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')

<style>
    .form-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        padding: 28px;
        margin-bottom: 24px;
    }
    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--azul-escuro);
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-error {
        font-size: 12px;
        color: var(--vermelho);
        margin-top: 4px;
    }
    .helper-text {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 4px;
    }
    
    /* Grid de documentos */
    .docs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }
    .doc-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border: 2px solid var(--cinza-200);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--branco);
    }
    .doc-card:hover {
        border-color: var(--azul-claro);
        background: #f8faff;
    }
    .doc-card.selected {
        border-color: var(--azul-claro);
        background: #eef4ff;
    }
    .doc-card input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--azul-claro);
        cursor: pointer;
    }
    .doc-card-info {
        flex: 1;
    }
    .doc-card-nome {
        font-weight: 600;
        font-size: 13px;
        color: var(--cinza-800);
    }
    .doc-card-desc {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 2px;
    }
    .doc-card-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .doc-card-badge.obrigatorio {
        background: #fef2f2;
        color: #dc2626;
    }
    .doc-card-badge.opcional {
        background: #f0f9ff;
        color: #0369a1;
    }
    
    /* Cargos responsáveis */
    .cargos-grid {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .cargo-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border: 2px solid var(--cinza-200);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--branco);
    }
    .cargo-item:hover {
        border-color: var(--azul-claro);
    }
    .cargo-item.selected {
        border-color: var(--azul-claro);
        background: #eef4ff;
    }
    .cargo-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--azul-claro);
        cursor: pointer;
    }
    .cargo-info {
        flex: 1;
    }
    .cargo-nome {
        font-weight: 700;
        font-size: 14px;
    }
    .cargo-desc {
        font-size: 12px;
        color: var(--cinza-400);
        margin-top: 2px;
    }
    .cargo-n1 { color: #3730a3; }
    .cargo-n2 { color: #92400e; }
    .cargo-n3 { color: #065f46; }
</style>

<div class="row justify-content-center">
    <div class="col-lg-10">

        <form action="{{ route('tipos.store') }}" method="POST" id="formServico">
            @csrf

            {{-- IDENTIFICAÇÃO --}}
            <div class="form-card">
                <div class="form-section-title">
                    📋 Identificação do Serviço
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Nome do Serviço <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-input-sced @error('nome') is-invalid @enderror"
                           value="{{ old('nome') }}" placeholder="Ex: Solicitação de Benefício" required>
                    @error('nome')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="helper-text">Digite um nome único e descritivo para o serviço.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Descrição</label>
                    <textarea name="descricao" class="form-input-sced" rows="3" 
                              placeholder="Descreva a finalidade deste serviço...">{{ old('descricao') }}</textarea>
                    <div class="helper-text">Descreva brevemente o propósito deste serviço.</div>
                </div>
            </div>

            {{-- SETOR DESTINO --}}
            <div class="form-card">
                <div class="form-section-title">
                    🏢 Setor Destino
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Setor de Destino <span class="text-danger">*</span></label>
                    <select name="departamento_destino_id" class="form-input-sced @error('departamento_destino_id') is-invalid @enderror" required>
                        <option value="">Selecione o setor responsável</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->id }}" {{ old('departamento_destino_id') == $dep->id ? 'selected' : '' }}>
                                🏢 {{ $dep->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento_destino_id')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="helper-text">Processos deste serviço serão direcionados para este setor.</div>
                </div>
            </div>

            {{-- DOCUMENTOS VINCULADOS --}}
            <div class="form-card">
                <div class="form-section-title">
                    📄 Documentos Vinculados
                </div>
                <div class="helper-text mb-3">
                    Selecione os documentos que podem ser anexados aos processos deste serviço.
                </div>

                @if($documentosDisponiveis->isEmpty())
                    <div class="alert alert-warning">
                        ⚠️ Nenhum documento cadastrado ainda. 
                        <a href="{{ route('documentos-tipo.create') }}">Cadastrar documentos</a>
                    </div>
                @else
                    <div class="docs-grid">
                        @foreach($documentosDisponiveis as $doc)
                            <label class="doc-card" id="doc-{{ $doc->id }}">
                                <input type="checkbox" name="documentos_necessarios[]" value="{{ $doc->id }}"
                                       {{ in_array($doc->id, old('documentos_necessarios', [])) ? 'checked' : '' }}
                                       onchange="toggleDocCard(this)">
                                <div class="doc-card-info">
                                    <div class="doc-card-nome">{{ $doc->nome }}</div>
                                    @if($doc->descricao)
                                        <div class="doc-card-desc">{{ Str::limit($doc->descricao, 50) }}</div>
                                    @endif
                                </div>
                                <span class="doc-card-badge {{ $doc->tipo }}">
                                    {{ $doc->tipo == 'obrigatorio' ? 'Obrigatório' : 'Opcional' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
                @error('documentos_necessarios')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- CARGOS RESPONSÁVEIS --}}
            <div class="form-card">
                <div class="form-section-title">
                    👥 Cargos Responsáveis
                </div>
                <div class="helper-text mb-3">
                    Selecione os cargos que podem visualizar e atuar sobre processos deste serviço.
                </div>

                <div class="cargos-grid">
                    <label class="cargo-item" id="cargo-n1-label">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N1" id="cargo-n1"
                               {{ in_array('N1', old('cargos_responsaveis', [])) ? 'checked' : '' }}
                               onchange="mudarCargo('N1', this.checked)">
                        <div class="cargo-info">
                            <div class="cargo-nome cargo-n1">🎯 N1 - Atendimento</div>
                            <div class="cargo-desc">Primeiro nível de atendimento</div>
                        </div>
                    </label>

                    <label class="cargo-item" id="cargo-n2-label">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N2" id="cargo-n2"
                               {{ in_array('N2', old('cargos_responsaveis', [])) ? 'checked' : '' }}
                               onchange="mudarCargo('N2', this.checked)">
                        <div class="cargo-info">
                            <div class="cargo-nome cargo-n2">📊 N2 - Analista</div>
                            <div class="cargo-desc">Análise e validação de processos</div>
                        </div>
                    </label>

                    <label class="cargo-item" id="cargo-n3-label">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N3" id="cargo-n3"
                               {{ in_array('N3', old('cargos_responsaveis', [])) ? 'checked' : '' }}
                               onchange="mudarCargo('N3', this.checked)">
                        <div class="cargo-info">
                            <div class="cargo-nome cargo-n3">⭐ N3 - Supervisor</div>
                            <div class="cargo-desc">Supervisão e gestão de processos</div>
                        </div>
                    </label>
                </div>
                @error('cargos_responsaveis')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- AÇÕES --}}
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('tipos.index') }}" class="btn-secondary-sced">Cancelar</a>
                <button type="submit" class="btn-primary-sced">💾 Salvar Serviço</button>
            </div>

        </form>

    </div>
</div>

<script>
    // Toggle do card de documento
    function toggleDocCard(checkbox) {
        const label = checkbox.closest('.doc-card');
        if (checkbox.checked) {
            label.classList.add('selected');
        } else {
            label.classList.remove('selected');
        }
    }

    // Inicializar documentos selecionados
    document.querySelectorAll('.doc-card input[type="checkbox"]').forEach(checkbox => {
        if (checkbox.checked) {
            checkbox.closest('.doc-card').classList.add('selected');
        }
    });

    // ============================================================
    // HIERARQUIA INTELIGENTE DE CARGOS
    // ============================================================
    function mudarCargo(cargo, isChecked) {
        if (cargo === 'N1' && isChecked) {
            marcarCargo('N2', true);
            marcarCargo('N3', true);
        } 
        else if (cargo === 'N2' && isChecked) {
            marcarCargo('N3', true);
        }
    }

    function marcarCargo(cargo, marcado) {
        const checkbox = document.getElementById(`cargo-${cargo.toLowerCase()}`);
        const label = document.getElementById(`cargo-${cargo.toLowerCase()}-label`);
        
        if (checkbox && checkbox.checked !== marcado) {
            checkbox.checked = marcado;
            if (marcado) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
        }
    }

    // Inicializar estados dos cards de cargo
    function atualizarCardCargo(cargo) {
        const checkbox = document.getElementById(`cargo-${cargo.toLowerCase()}`);
        const label = document.getElementById(`cargo-${cargo.toLowerCase()}-label`);
        if (checkbox && label) {
            if (checkbox.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
        }
    }

    atualizarCardCargo('N1');
    atualizarCardCargo('N2');
    atualizarCardCargo('N3');

    document.getElementById('cargo-n1')?.addEventListener('change', (e) => {
        document.getElementById('cargo-n1-label').classList.toggle('selected', e.target.checked);
    });
    document.getElementById('cargo-n2')?.addEventListener('change', (e) => {
        document.getElementById('cargo-n2-label').classList.toggle('selected', e.target.checked);
    });
    document.getElementById('cargo-n3')?.addEventListener('change', (e) => {
        document.getElementById('cargo-n3-label').classList.toggle('selected', e.target.checked);
    });
</script>

@endsection