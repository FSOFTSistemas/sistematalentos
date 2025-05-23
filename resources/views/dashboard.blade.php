@extends('adminlte::page')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    @master
    <!-- Dashboard para usuário Master -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $planos_count ?? 0 }}</h3>
                    <p>Planos Ativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="{{ route('planos.index') }}" class="small-box-footer">
                    Ver Planos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $empresas_count ?? 0 }}</h3>
                    <p>Empresas/Igrejas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('empresas.index') }}" class="small-box-footer">
                    Ver Empresas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $empresas_vencendo ?? 0 }}</h3>
                    <p>Planos Vencendo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('empresas.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $empresas_vencidas ?? 0 }}</h3>
                    <p>Planos Vencidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('empresas.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Empresas Recentes</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Plano</th>
                                <th>Validade</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($empresas_recentes ?? [] as $empresa)
                                <tr>
                                    <td>{{ $empresa->nome }}</td>
                                    <td>{{ $empresa->plano->nome ?? 'Sem plano' }}</td>
                                    <td>
                                        @if($empresa->data_fim_plano)
                                            {{ \Carbon\Carbon::parse($empresa->data_fim_plano)->format('d/m/Y') }}
                                            @if(\Carbon\Carbon::parse($empresa->data_fim_plano)->isPast())
                                                <span class="badge badge-danger">Vencido</span>
                                            @elseif(\Carbon\Carbon::parse($empresa->data_fim_plano)->diffInDays(now()) < 15)
                                                <span class="badge badge-warning">Próximo ao vencimento</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($empresa->ativo && $empresa->planoAtivo())
                                            <span class="badge badge-success">Ativo</span>
                                        @else
                                            <span class="badge badge-danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('empresas.show', $empresa->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Nenhuma empresa cadastrada</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('empresas.create') }}" class="btn btn-sm btn-primary float-right">
                        <i class="fas fa-plus"></i> Nova Empresa
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Planos Disponíveis</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($planos_ativos ?? [] as $plano)
                            <li class="item">
                                <div class="product-info">
                                    <a href="{{ route('planos.show', $plano->id) }}" class="product-title">
                                        {{ $plano->nome }}
                                        <span class="badge badge-info float-right">R$ {{ number_format($plano->valor, 2, ',', '.') }}</span>
                                    </a>
                                    <span class="product-description">
                                        {{ $plano->periodoFormatado }} - Limite de {{ $plano->limite_membros }} membros
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="item">
                                <div class="product-info">
                                    <span class="product-description text-center">
                                        Nenhum plano ativo disponível
                                    </span>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('planos.index') }}" class="uppercase">Ver Todos os Planos</a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Dashboard para usuários normais (Admin e User) -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informações do Plano</h3>
                </div>
                <div class="card-body">
                    @if(isset($empresa_atual) && isset($plano_atual))
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Empresa:</dt>
                                    <dd>{{ $empresa_atual->nome }}</dd>
                                    
                                    <dt>Plano:</dt>
                                    <dd>{{ $plano_atual->nome }}</dd>
                                    
                                    <dt>Valor:</dt>
                                    <dd>R$ {{ number_format($plano_atual->valor, 2, ',', '.') }}</dd>
                                    
                                    <dt>Período:</dt>
                                    <dd>{{ $plano_atual->periodoFormatado }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Status:</dt>
                                    <dd>
                                        @if($plano_ativo)
                                            <span class="badge badge-success">Ativo</span>
                                        @else
                                            <span class="badge badge-danger">Inativo</span>
                                        @endif
                                    </dd>
                                    
                                    <dt>Validade:</dt>
                                    <dd>
                                        @if($empresa_atual->data_fim_plano)
                                            {{ \Carbon\Carbon::parse($empresa_atual->data_fim_plano)->format('d/m/Y') }}
                                            @if(\Carbon\Carbon::parse($empresa_atual->data_fim_plano)->isPast())
                                                <span class="badge badge-danger">Vencido</span>
                                            @elseif(\Carbon\Carbon::parse($empresa_atual->data_fim_plano)->diffInDays(now()) < 15)
                                                <span class="badge badge-warning">Próximo ao vencimento</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        
                        <div class="progress-group">
                            <span class="progress-text">Utilização de Membros</span>
                            <span class="float-right"><b>{{ $total_membros }}</b>/{{ $limite_plano }}</span>
                            <div class="progress">
                                @php
                                    $colorClass = $percentual_utilizado < 70 ? 'bg-success' : ($percentual_utilizado < 90 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentual_utilizado }}%"></div>
                            </div>
                            <small class="text-muted">
                                @if($membros_restantes > 0)
                                    Você ainda pode cadastrar {{ $membros_restantes }} membros.
                                @else
                                    Você atingiu o limite de membros do seu plano.
                                    Entre em contato com o administrador para fazer upgrade.
                                @endif
                            </small>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Sua conta não está associada a nenhuma empresa ou plano.
                            Entre em contato com o administrador.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Resumo do Sistema</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Membros</span>
                                    <span class="info-box-number">{{ $total_membros ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Dízimos</span>
                                    <span class="info-box-number">{{ $total_dizimos ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Despesas</span>
                                    <span class="info-box-number">{{ $total_despesas ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-chart-pie"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Saldo</span>
                                    <span class="info-box-number">R$ {{ number_format($saldo_atual ?? 0, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endmaster
@endsection
