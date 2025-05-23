<!-- Modal Delete -->
<div class="modal fade" id="deletePlanoModal{{ $plano->id }}" tabindex="-1" aria-labelledby="deletePlanoModalLabel{{ $plano->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('planos.destroy', $plano->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePlanoModalLabel{{ $plano->id }}">Confirmação de Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p>Você tem certeza que deseja excluir o plano <strong>{{ $plano->nome }}</strong>?</p>
                    <p class="text-danger"><small>Essa ação não poderá ser desfeita!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill">Excluir</button>
                </div>
            </div>
        </form>
    </div>
</div>
