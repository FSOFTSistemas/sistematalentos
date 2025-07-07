<!-- Modal para Nova Entrada -->
    <div class="modal fade" id="modal-entrada">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Nova Entrada</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('caixa.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipo" value="entrada">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="categoriaEntrada">Categoria</label>
                            <select class="form-control" id="categoriaEntrada" name="categoriaEntrada" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Dízimo">Dízimo</option>
                                <option value="Oferta">Oferta</option>
                                <option value="Doação">Doação</option>
                                <option value="Evento">Evento</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="membro-select-group">
                            <label for="membro_id">Membro</label>
                            <select class="form-control" id="membro_id" name="membro_id">
                                <option value="">Selecione um membro</option>
                                @foreach ($membros as $membro)
                                    <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao">
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="data">Data</label>
                            <input type="date" class="form-control" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal-entrada');
    const categoriaSelect = document.getElementById('categoriaEntrada');
    const membroGroup = document.getElementById('membro-select-group');

    function toggleMembroSelect() {
        if (categoriaSelect.value.trim().toLowerCase() === 'dízimo') {
            membroGroup.classList.remove('d-none');
        } else {
            membroGroup.classList.add('d-none');
        }
    }

    categoriaSelect.addEventListener('change', toggleMembroSelect);

    modal.addEventListener('shown.bs.modal', function () {
        toggleMembroSelect(); // atualiza sempre que o modal abrir
    });
});
</script>