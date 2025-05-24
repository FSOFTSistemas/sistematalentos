<div class="modal fade" id="deleteDizimoModal{{ $dizimo->id }}" tabindex="-1" aria-labelledby="deleteDizimoModalLabel{{ $dizimo->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDizimoModalLabel{{ $dizimo->id }}">
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir o dízimo de <strong>{{ $dizimo->membro->nome ?? 'Desconhecido' }}</strong>
                no valor de <strong>R$ {{ number_format($dizimo->valor, 2, ',', '.') }}</strong>
                registrado em <strong>{{ $dizimo->data->format('d/m/Y') }}</strong>?
            </div>
            <div class="modal-footer">
                <form action="{{ route('dizimos.destroy', $dizimo->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">🗑️ Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
