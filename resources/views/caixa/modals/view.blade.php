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
                        <dl class="row border rounded p-3 bg-light">
                            <dt class="col-sm-4 text-muted"><i class="fas fa-hashtag"></i> ID:</dt>
                            <dd class="col-sm-8 fw-bold">{{ $movimentacao->id }}</dd>

                            <dt class="col-sm-4 text-muted"><i class="fas fa-file-alt"></i> Descrição:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->descricao }}</dd>

                            <dt class="col-sm-4 text-muted"><i class="fas fa-dollar-sign"></i> Valor:</dt>
                            <dd class="col-sm-8 text-success fw-bold">R$ {{ number_format($movimentacao->valor, 2, ',', '.') }}</dd>

                            <dt class="col-sm-4 text-muted"><i class="fas fa-calendar-day"></i> Data:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->data->format('d/m/Y') }}</dd>

                            <dt class="col-sm-4 text-muted"><i class="fas fa-tag"></i> Categoria:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->categoria }}</dd>

                            @if($movimentacao->tipo === 'entrada' && $movimentacao->categoria === 'Dízimo' && $movimentacao->membro)
                                <dt class="col-sm-4 text-muted"><i class="fas fa-user"></i> Membro:</dt>
                                <dd class="col-sm-8">{{ $movimentacao->membro->nome }}</dd>
                            @endif

                            <dt class="col-sm-4 text-muted"><i class="fas fa-exchange-alt"></i> Tipo:</dt>
                            <dd class="col-sm-8">
                                @if($movimentacao->tipo == 'entrada')
                                    <span class="badge bg-success">Entrada</span>
                                @else
                                    <span class="badge bg-danger">Saída</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted"><i class="fas fa-sticky-note"></i> Observação:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->observacao ?? 'Nenhuma observação' }}</dd>

                            <dt class="col-sm-4 text-muted"><i class="fas fa-user-check"></i> Registrado por:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->user->name ?? 'Sistema' }}</dd>

                            <dt class="col-sm-4 text-muted"><i class="fas fa-clock"></i> Data de Registro:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->created_at->format('d/m/Y H:i:s') }}</dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
