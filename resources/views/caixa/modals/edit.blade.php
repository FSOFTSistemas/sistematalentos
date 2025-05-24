<!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $movimentacao->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header {{ $movimentacao->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                        <h4 class="modal-title">Editar {{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('caixa.update', $movimentacao->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tipo" value="{{ $movimentacao->tipo }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" value="{{ $movimentacao->descricao }}" required>
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" value="{{ $movimentacao->valor }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="data">Data</label>
                                <input type="date" class="form-control" id="data" name="data" value="{{ $movimentacao->data->format('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <select class="form-control" id="categoria" name="categoria" required>
                                    @if($movimentacao->tipo == 'entrada')
                                        <option value="Dízimo" {{ $movimentacao->categoria == 'Dízimo' ? 'selected' : '' }}>Dízimo</option>
                                        <option value="Oferta" {{ $movimentacao->categoria == 'Oferta' ? 'selected' : '' }}>Oferta</option>
                                        <option value="Doação" {{ $movimentacao->categoria == 'Doação' ? 'selected' : '' }}>Doação</option>
                                        <option value="Evento" {{ $movimentacao->categoria == 'Evento' ? 'selected' : '' }}>Evento</option>
                                        <option value="Outro" {{ $movimentacao->categoria == 'Outro' ? 'selected' : '' }}>Outro</option>
                                    @else
                                        <option value="Água/Luz" {{ $movimentacao->categoria == 'Água/Luz' ? 'selected' : '' }}>Água/Luz</option>
                                        <option value="Aluguel" {{ $movimentacao->categoria == 'Aluguel' ? 'selected' : '' }}>Aluguel</option>
                                        <option value="Material" {{ $movimentacao->categoria == 'Material' ? 'selected' : '' }}>Material</option>
                                        <option value="Manutenção" {{ $movimentacao->categoria == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                                        <option value="Salário" {{ $movimentacao->categoria == 'Salário' ? 'selected' : '' }}>Salário</option>
                                        <option value="Evento" {{ $movimentacao->categoria == 'Evento' ? 'selected' : '' }}>Evento</option>
                                        <option value="Outro" {{ $movimentacao->categoria == 'Outro' ? 'selected' : '' }}>Outro</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ $movimentacao->observacao }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn {{ $movimentacao->tipo == 'entrada' ? 'btn-success' : 'btn-danger' }}">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>