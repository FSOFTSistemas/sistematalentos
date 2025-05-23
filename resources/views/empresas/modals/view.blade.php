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