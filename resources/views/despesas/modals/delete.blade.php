<div class="modal fade" id="modal-delete-{{ $despesa->id }}" tabindex="-1"
    aria-labelledby="modal-delete-{{ $despesa->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletedespesaLabel{{ $despesa->id }}">
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o despesa <strong>{{ $despesa->nome }}</strong>?</p>
                <p class="text-danger mb-0"><small>Esta ação não poderá ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('despesas.destroy', $despesa->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
