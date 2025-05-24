<!-- Modal para Novo Dízimo -->
<div class="modal fade" id="modal-novo-dizimo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Novo Dízimo</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dizimos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="membro_id">Membro</label>
                        <select class="form-control select2" id="membro_id" name="membro_id" required>
                            <option value="">Selecione um membro</option>
                            @foreach ($membros ?? [] as $membro)
                                <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="valor"
                                name="valor" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="data">Data do Pagamento</label>
                        <input type="date" class="form-control" id="data" name="data"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mes_referencia">Mês de Referência</label>
                                <select class="form-control" id="mes_referencia" name="mes_referencia" required>
                                    <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>Janeiro</option>
                                    <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>Fevereiro</option>
                                    <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>Março</option>
                                    <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>Abril</option>
                                    <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>Maio</option>
                                    <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>Junho</option>
                                    <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>Julho</option>
                                    <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>Agosto</option>
                                    <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>Setembro</option>
                                    <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>Outubro</option>
                                    <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>Novembro</option>
                                    <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>Dezembro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ano_referencia">Ano de Referência</label>
                                <input type="number" class="form-control" id="ano_referencia" name="ano_referencia"
                                    value="{{ date('Y') }}" min="2000" max="2100" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="observacao">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
