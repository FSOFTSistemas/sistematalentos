<!-- Modal de Edição -->
<div class="modal fade" id="modal-edit-{{ $dizimo->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Editar Dízimo</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dizimos.update', $dizimo->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="membro_id">Membro</label>
                        <select class="form-control select2" id="membro_id" name="membro_id" required>
                            <option value="">Selecione um membro</option>
                            @foreach ($membros ?? [] as $membro)
                                <option value="{{ $membro->id }}"
                                    {{ $dizimo->membro_id == $membro->id ? 'selected' : '' }}>{{ $membro->nome }}
                                </option>
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
                                name="valor" value="{{ $dizimo->valor }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="data">Data do Pagamento</label>
                        <input type="date" class="form-control" id="data" name="data"
                            value="{{ $dizimo->data->format('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mes_referencia">Mês de Referência</label>
                                <select class="form-control" id="mes_referencia" name="mes_referencia" required>
                                    <option value="1" {{ $dizimo->mes_referencia == 1 ? 'selected' : '' }}>Janeiro
                                    </option>
                                    <option value="2" {{ $dizimo->mes_referencia == 2 ? 'selected' : '' }}>
                                        Fevereiro</option>
                                    <option value="3" {{ $dizimo->mes_referencia == 3 ? 'selected' : '' }}>Março
                                    </option>
                                    <option value="4" {{ $dizimo->mes_referencia == 4 ? 'selected' : '' }}>Abril
                                    </option>
                                    <option value="5" {{ $dizimo->mes_referencia == 5 ? 'selected' : '' }}>Maio
                                    </option>
                                    <option value="6" {{ $dizimo->mes_referencia == 6 ? 'selected' : '' }}>Junho
                                    </option>
                                    <option value="7" {{ $dizimo->mes_referencia == 7 ? 'selected' : '' }}>Julho
                                    </option>
                                    <option value="8" {{ $dizimo->mes_referencia == 8 ? 'selected' : '' }}>Agosto
                                    </option>
                                    <option value="9" {{ $dizimo->mes_referencia == 9 ? 'selected' : '' }}>
                                        Setembro</option>
                                    <option value="10" {{ $dizimo->mes_referencia == 10 ? 'selected' : '' }}>
                                        Outubro</option>
                                    <option value="11" {{ $dizimo->mes_referencia == 11 ? 'selected' : '' }}>
                                        Novembro</option>
                                    <option value="12" {{ $dizimo->mes_referencia == 12 ? 'selected' : '' }}>
                                        Dezembro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ano_referencia">Ano de Referência</label>
                                <input type="number" class="form-control" id="ano_referencia" name="ano_referencia"
                                    value="{{ $dizimo->ano_referencia }}" min="2000" max="2100" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="observacao">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ $dizimo->observacao }}</textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
