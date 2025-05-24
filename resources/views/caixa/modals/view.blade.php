<!-- Modal de Visualização -->
        <div class="modal fade" id="modal-view-{{ $movimentacao->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header {{ $movimentacao->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                        <h4 class="modal-title">Detalhes da {{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->id }}</dd>
                            
                            <dt class="col-sm-4">Descrição:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->descricao }}</dd>
                            
                            <dt class="col-sm-4">Valor:</dt>
                            <dd class="col-sm-8">R$ {{ number_format($movimentacao->valor, 2, ',', '.') }}</dd>
                            
                            <dt class="col-sm-4">Data:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->data->format('d/m/Y') }}</dd>
                            
                            <dt class="col-sm-4">Categoria:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->categoria }}</dd>
                            
                            <dt class="col-sm-4">Tipo:</dt>
                            <dd class="col-sm-8">
                                @if($movimentacao->tipo == 'entrada')
                                    <span class="badge badge-success">Entrada</span>
                                @else
                                    <span class="badge badge-danger">Saída</span>
                                @endif
                            </dd>
                            
                            <dt class="col-sm-4">Observação:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->observacao ?? 'Nenhuma observação' }}</dd>
                            
                            <dt class="col-sm-4">Registrado por:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->user->name ?? 'Sistema' }}</dd>
                            
                            <dt class="col-sm-4">Data de Registro:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->created_at->format('d/m/Y H:i:s') }}</dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
