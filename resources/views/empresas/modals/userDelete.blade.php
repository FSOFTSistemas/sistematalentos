 {{-- Modal Excluir --}}
 <div class="modal fade" id="modalExcluir{{ $usuario->id }}" tabindex="-1"
     aria-labelledby="modalExcluirLabel{{ $usuario->id }}" aria-hidden="true">
     <div class="modal-dialog">
         <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}">
             @csrf
             @method('DELETE')
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="modalExcluirLabel{{ $usuario->id }}">Confirmar Exclusão
                     </h5>
                     <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
                 </div>
                 <div class="modal-body">
                     Tem certeza que deseja excluir o usuário
                     <strong>{{ $usuario->name }}</strong>?
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                     <button type="submit" class="btn btn-danger">Excluir</button>
                 </div>
             </div>
         </form>
     </div>
 </div>
