@extends('adminlte::page')

@section('title', 'Gerenciamento de Empresas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Empresas</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Empresas/Igrejas</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-nova-empresa">
                    <i class="fas fa-plus"></i> Nova Empresa
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Plano</th>
                        <th>Membros</th>
                        <th>Validade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas ?? [] as $empresa)
                        <tr>
                            <td>{{ $empresa->id }}</td>
                            <td>{{ $empresa->nome }}</td>
                            <td>{{ $empresa->plano->nome ?? 'Sem plano' }}</td>
                            <td>
                                @if($empresa->plano)
                                    {{ $empresa->membros()->count() }} / {{ $empresa->plano->limite_membros }}
                                    <div class="progress progress-xs">
                                        @php
                                            $percentual = $empresa->plano->limite_membros > 0 ? 
                                                min(100, ($empresa->membros()->count() / $empresa->plano->limite_membros) * 100) : 0;
                                            $colorClass = $percentual < 70 ? 'bg-success' : ($percentual < 90 ? 'bg-warning' : 'bg-danger');
                                        @endphp
                                        <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentual }}%"></div>
                                    </div>
                                @else
                                    N/A
                                @endif
                            </td>
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
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-view-{{ $empresa->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $empresa->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Nova Empresa -->
    <div class="modal fade" id="modal-nova-empresa">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Nova Empresa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('empresas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Dados da Empresa</h5>
                                <div class="form-group">
                                    <label for="nome">Nome da Empresa/Igreja</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required>
                                </div>
                                <div class="form-group">
                                    <label for="cnpj">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone">
                                </div>
                                <div class="form-group">
                                    <label for="responsavel">Responsável</label>
                                    <input type="text" class="form-control" id="responsavel" name="responsavel" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Dados do Plano</h5>
                                <div class="form-group">
                                    <label for="plano_id">Plano</label>
                                    <select class="form-control" id="plano_id" name="plano_id" required>
                                        <option value="">Selecione um plano</option>
                                        @foreach($planos ?? [] as $plano)
                                            <option value="{{ $plano->id }}">{{ $plano->nome }} - R$ {{ number_format($plano->valor, 2, ',', '.') }} ({{ $plano->periodoFormatado }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="data_inicio_plano">Data de Início</label>
                                    <input type="date" class="form-control" id="data_inicio_plano" name="data_inicio_plano" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Dados do Administrador</h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Estas informações serão usadas para criar o usuário administrador da empresa.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="admin_name">Nome</label>
                                    <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="admin_email">Email</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="admin_password">Senha</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modais de Edição e Visualização seriam gerados dinamicamente para cada item -->
    @foreach($empresas ?? [] as $empresa)
        <!-- Modal de Visualização -->
        <div class="modal fade" id="modal-view-{{ $empresa->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Detalhes da Empresa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                                        
                                        <dt class="col-sm-4">Membros:</dt>
                                        <dd class="col-sm-8">
                                            {{ $empresa->membros()->count() }} / {{ $empresa->plano->limite_membros }}
                                            <div class="progress">
                                                @php
                                                    $percentual = $empresa->plano->limite_membros > 0 ? 
                                                        min(100, ($empresa->membros()->count() / $empresa->plano->limite_membros) * 100) : 0;
                                                    $colorClass = $percentual < 70 ? 'bg-success' : ($percentual < 90 ? 'bg-warning' : 'bg-danger');
                                                @endphp
                                                <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentual }}%">{{ number_format($percentual, 0) }}%</div>
                                            </div>
                                        </dd>
                                    </dl>
                                    
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-renovar-{{ $empresa->id }}">
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
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Papel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($usuarios ?? [] as $usuario)
                                            @if($usuario->empresa_id == $empresa->id)
                                                <tr>
                                                    <td>{{ $usuario->name }}</td>
                                                    <td>{{ $usuario->email }}</td>
                                                    <td>
                                                        @foreach($usuario->roles as $role)
                                                            <span class="badge badge-info">{{ $role->name }}</span>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Nenhum usuário encontrado</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal-edit-{{ $empresa->id }}">Editar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $empresa->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Editar Empresa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('empresas.update', $empresa->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Dados da Empresa</h5>
                                    <div class="form-group">
                                        <label for="nome">Nome da Empresa/Igreja</label>
                                        <input type="text" class="form-control" id="nome" name="nome" value="{{ $empresa->nome }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cnpj">CNPJ</label>
                                        <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{ $empresa->cnpj }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $empresa->email }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" class="form-control" id="telefone" name="telefone" value="{{ $empresa->telefone }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="responsavel">Responsável</label>
                                        <input type="text" class="form-control" id="responsavel" name="responsavel" value="{{ $empresa->responsavel }}" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="ativo-{{ $empresa->id }}" name="ativo" {{ $empresa->ativo ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="ativo-{{ $empresa->id }}">Empresa Ativa</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Dados do Plano</h5>
                                    <div class="form-group">
                                        <label for="plano_id">Plano</label>
                                        <select class="form-control" id="plano_id" name="plano_id" required>
                                            <option value="">Selecione um plano</option>
                                            @foreach($planos ?? [] as $plano)
                                                <option value="{{ $plano->id }}" {{ $empresa->plano_id == $plano->id ? 'selected' : '' }}>
                                                    {{ $plano->nome }} - R$ {{ number_format($plano->valor, 2, ',', '.') }} ({{ $plano->periodoFormatado }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="data_inicio_plano">Data de Início</label>
                                        <input type="date" class="form-control" id="data_inicio_plano" name="data_inicio_plano" value="{{ $empresa->data_inicio_plano ? \Carbon\Carbon::parse($empresa->data_inicio_plano)->format('Y-m-d') : date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="renovar_plano-{{ $empresa->id }}" name="renovar_plano">
                                            <label class="custom-control-label" for="renovar_plano-{{ $empresa->id }}">Renovar Plano</label>
                                        </div>
                                        <small class="form-text text-muted">Marque esta opção para recalcular a data de fim do plano.</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ $empresa->observacoes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
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
                    <form action="{{ route('empresas.add-user', $empresa->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Papel</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="admin">Administrador</option>
                                    <option value="user">Usuário</option>
                                </select>
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
    @endforeach
@endsection

@section('scripts')
<script>
    $(function () {
        // Inicialização específica para a tabela de empresas
        $('.datatable').DataTable({
            "order": [[0, 'asc']], // Ordenar por ID (coluna 0) crescente
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            },
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                }
            ],
            "dom": 'Bfrtip'
        });
        
        // Confirmação de exclusão com SweetAlert2
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não poderá ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
