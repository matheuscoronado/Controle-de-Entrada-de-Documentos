{{-- ============================================================
     resources/views/admin/tipos/index.blade.php
     Listagem de Tipos de Documento com os novos campos da Parte 1
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Tipos de Documento')
@section('subtitle', 'Parametrização de documentos e serviços')

@section('topbar-actions')
    <a href="{{ route('tipos.create') }}" class="btn-primary-sced">
        ➕ Novo Tipo
    </a>
@endsection

@section('content')

{{-- Cards de resumo --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Total de Tipos</div>
            <div style="font-size:28px;font-weight:700;color:var(--azul-claro);">{{ $tipos->count() }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Obrigatórios</div>
            <div style="font-size:28px;font-weight:700;color:var(--vermelho);">{{ $tipos->where('obrigatoriedade','obrigatorio')->count() }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Ativos</div>
            <div style="font-size:28px;font-weight:700;color:var(--verde);">{{ $tipos->where('status','ativo')->count() }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Com SLA</div>
            <div style="font-size:28px;font-weight:700;color:var(--ciano);">{{ $tipos->whereNotNull('sla_horas')->count() }}</div>
        </div>
    </div>
</div>

<div class="card-sced">
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo de Documento</th>
                    <th>Exigência</th>
                    <th>Destino</th>
                    <th>Responsável</th>
                    <th>SLA</th>
                    <th>Docs.</th>
                    <th>Status</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $tipo)
                <tr>
                    <td style="color:var(--cinza-400);font-size:12px;">{{ $tipo->id }}</td>

                    <td>
                        <div style="font-weight:600;">🏷️ {{ $tipo->nome }}</div>
                        @if($tipo->descricao)
                            <div style="font-size:12px;color:var(--cinza-400);margin-top:2px;">{{ Str::limit($tipo->descricao, 60) }}</div>
                        @endif
                    </td>

                    <td>
                        @if($tipo->obrigatoriedade === 'obrigatorio')
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Obrigatório</span>
                        @else
                            <span style="background:#f0f9ff;color:#0369a1;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">○ Opcional</span>
                        @endif
                    </td>

                    <td style="font-size:13px;">
                        @if($tipo->departamentoDestino)
                            <span style="display:flex;align-items:center;gap:4px;">
                                <span>🏢</span> {{ $tipo->departamentoDestino->nome }}
                            </span>
                        @else
                            <span style="color:var(--cinza-400);">—</span>
                        @endif
                    </td>

                    <td>
                        @if($tipo->cargo_responsavel)
                            <span style="background:var(--cinza-200);color:var(--cinza-800);padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;font-family:'JetBrains Mono',monospace;">
                                {{ $tipo->cargo_responsavel }}
                            </span>
                        @else
                            <span style="color:var(--cinza-400);">—</span>
                        @endif
                    </td>

                    <td style="font-size:13px;font-weight:600;color:var(--ciano);">
                        {{ $tipo->label_sla }}
                    </td>

                    <td>
                        <span style="font-weight:700;color:var(--azul-claro);">{{ $tipo->documentos_count }}</span>
                        <span style="font-size:12px;color:var(--cinza-400);"> doc(s)</span>
                    </td>

                    <td>
                        @if($tipo->status === 'ativo')
                            <span style="background:#f0fdf4;color:#059669;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Ativo</span>
                        @else
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Inativo</span>
                        @endif
                    </td>

                    <td style="text-align:center;">
                        <a href="{{ route('tipos.edit', $tipo) }}" class="btn-outline-sced">✏️ Editar</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        <div style="font-size:32px;margin-bottom:8px;">🏷️</div>
                        Nenhum tipo cadastrado ainda. Crie o primeiro!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
