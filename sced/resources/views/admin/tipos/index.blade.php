{{-- resources/views/admin/tipos/index.blade.php — Cadastro de Serviços --}}
@extends('layouts.app')
@section('title', 'Cadastro de Serviços')
@section('subtitle', 'Gerencie os serviços, seus documentos e responsáveis')

@section('topbar-actions')
    <a href="{{ route('tipos.create') }}" class="btn-primary-sced">
        ➕ Novo Serviço
    </a>
@endsection

@section('content')

{{-- Cards de resumo --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Total de Serviços</div>
            <div style="font-size:28px;font-weight:700;color:var(--azul-claro);">{{ $tipos->count() }}</div>
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
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Com Setor Destino</div>
            <div style="font-size:28px;font-weight:700;color:var(--ciano);">{{ $tipos->whereNotNull('departamento_destino_id')->count() }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Com Documentos</div>
            <div style="font-size:28px;font-weight:700;color:var(--azul-claro);">{{ $tipos->filter(fn($t) => $t->documentosTipo->count() > 0)->count() }}</div>
        </div>
    </div>
</div>

<div class="card-sced">
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Serviço</th>
                    <th>Documentos Necessários</th>
                    <th>Setor Destino</th>
                    <th>Cargos Responsáveis</th>
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
                        @if($tipo->documentosTipo->count() > 0)
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach($tipo->documentosTipo as $doc)
                                    <span style="background:{{ $doc->tipo === 'obrigatorio' ? '#fef2f2' : '#f0f9ff' }};
                                                 color:{{ $doc->tipo === 'obrigatorio' ? '#dc2626' : '#0369a1' }};
                                                 padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">
                                        {{ $doc->nome }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span style="color:var(--cinza-400);font-size:13px;">—</span>
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
                        @php $cargos = $tipo->cargos_responsaveis ?? []; @endphp
                        @if(count($cargos) > 0)
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach($cargos as $cargo)
                                    <span style="background:var(--cinza-200);color:var(--cinza-800);padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;font-family:'JetBrains Mono',monospace;">
                                        {{ $cargo }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span style="color:var(--cinza-400);">—</span>
                        @endif
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
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        <div style="font-size:32px;margin-bottom:8px;">🏷️</div>
                        Nenhum serviço cadastrado ainda.
                        <a href="{{ route('tipos.create') }}" style="color:var(--azul-claro);font-weight:600;">Criar o primeiro</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
