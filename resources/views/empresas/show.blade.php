@extends('adminlte::page')

@section('title', 'Informações da Empresa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">Empresas</a></li>
    <li class="breadcrumb-item active">Informações da Empresa</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalhes da Empresa</h3>
            <div class="card-tools">
                <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Dados da Empresa</h5>
                    <dl class="row">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $empresa->id }}</dd>
                        
                        <dt class="col-sm-4">Nome:</dt>
                        <dd class="col-sm-8">{{ $empresa->nome }}</dd>
                        
                        <dt class="col-sm-4">CNPJ:</dt>
                        <dd class="col-sm-8">{{ $empresa->cnpj ?? 'Não informado' }}</dd>
                        
                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8">{{ $empresa->email ?? 'Não informado' }}</dd>
                        
                        <dt class="col-sm-4">Telefone:</dt>
                        <dd class="col-sm-8">{{ $empresa->telefone ?? 'Não informado' }}</dd>
                        
                        <dt class="col-sm-4">Responsável:</dt>
                        <dd class="col-sm-8">{{ $empresa->responsavel }}</dd>
                        
                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            @if($empresa->ativo && $empresa->planoAtivo())
                                <span class="badge badge-success">Ativo</span>
                            @else
                                <span class="badge badge-danger">Inativo</span>
                            @endif
                        </dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <h5>Dados do Plano</h5>
                    @if($empresa->plano)
                        <dl class="row">
                            <dt class="col-sm-4">Plano:</dt>
                            <dd class="col-sm-8">{{ $empresa->plano->nome }}</dd>
                            
                            <dt class="col-sm-4">Valor:</dt>
                            <dd class="col-sm-8">R$ {{ number_format($empresa->plano->valor, 2, ',', '.') }}</dd>
                            
                            <dt class="col-sm-4">Período:</dt>
                            <dd class="col-sm-8">{{ $empresa->plano->periodoFormatado }}</dd>
                            
                            <dt class="col-sm-4">Início:</dt>
                            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($empresa->data_inicio_plano)->format('d/m/Y') }}</dd>
                            
                            <dt class="col-sm-4">Validade:</dt>
                            <dd class="col-sm-8">
                                {{ \Carbon\Carbon::parse($empresa->data_fim_plano)->format('d/m/Y') }}
                                @if(\Carbon\Carbon::parse($empresa->data_fim_plano)->isPast())
                                    <span class="badge badge-danger">Vencido</span>
                                @elseif(\Carbon\Carbon::parse($empresa->data_fim_plano)->diffInDays(now()) < 15)
                                    <span class="badge badge-warning">Próximo ao vencimento</span>
                                @endif
                            </dd>
                        </dl>
                        
                        <div class="progress-group">
                            <span class="progress-text">Utilização de Membros</span>
                            <span class="float-right"><b>{{ $totalMembros }}</b>/{{ $limitePlano }}</span>
                            <div class="progress">
                                @php
                                    $colorClass = $percentualUtilizado < 70 ? 'bg-success' : ($percentualUtilizado < 90 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentualUtilizado }}%"></div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-warning btn-sm mt-3" data-toggle="modal" data-target="#modal-renovar-{{ $empresa->id }}">
                            <i class="fas fa-sync-alt"></i> Renovar Plano
                        </button>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Esta empresa não possui um plano associado.
                        </div>
                    @endif
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-md-12">
                    <h5>Usuários</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Papel</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->name }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>
                                            @foreach($usuario->roles as $role)
                                                <span class="badge badge-info">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $usuario->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum usuário encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add-user-{{ $empresa->id }}">
                        <i class="fas fa-user-plus"></i> Adicionar Usuário
                    </button>
                </div>
            </div>
            
            @if($empresa->observacoes)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Observações</h5>
                        <p>{{ $empresa->observacoes }}</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('empresas.index') }}" class="btn btn-default">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-delete">
                    <i class="fas fa-trash"></i> Excluir
                </button>
            </form>
        </div>
    </div>

    <!-- Modal para Renovar Plano -->
    <div class="modal fade" id="modal-renovar-{{ $empresa->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Renovar Plano</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('empresas.renovar-plano', $empresa->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> A renovação irá estender o plano atual por mais um período.
                        </div>
                        
                        <div class="form-group">
                            <label for="data_inicio_plano">Data de Início da Renovação</label>
                            <input type="date" class="form-control" id="data_inicio_plano" name="data_inicio_plano" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Plano Atual</label>
                            <p class="form-control-static">
                                {{ $empresa->plano->nome ?? 'Sem plano' }} - 
                                R$ {{ number_format($empresa->plano->valor ?? 0, 2, ',', '.') }} 
                                ({{ $empresa->plano->periodoFormatado ?? 'N/A' }})
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label>Validade Atual</label>
                            <p class="form-control-static">
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
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Renovar Plano</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Adicionar Usuário -->
    <div class="modal fade" id="modal-add-user-{{ $empresa->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Adicionar Usuário</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('empresas.add-user', $empresa->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">
                                Por favor, informe o nome do usuário.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Por favor, informe um email válido.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Por favor, informe uma senha.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role">Papel</label>
                            <select class="form-control select2" id="role" name="role" required>
                                <option value="admin">Administrador</option>
                                <option value="user">Usuário</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor, selecione um papel.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/monetizacao.js') }}"></script>
@endsection
