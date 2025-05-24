 <div class="modal fade" id="modal-view-{{ $membro->id }}">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header bg-info">
                 <h4 class="modal-title">Detalhes do Membro</h4>
                 <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-12 text-center mb-4">
                         <h3>{{ $membro->nome }}</h3>
                         <p>
                             @if ($membro->status == 'ativo')
                                 <span class="badge badge-success">Ativo</span>
                             @else
                                 <span class="badge badge-secondary">Inativo</span>
                             @endif
                         </p>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-6">
                         <dl class="row">
                             <dt class="col-sm-4">ID:</dt>
                             <dd class="col-sm-8">{{ $membro->id }}</dd>

                             <dt class="col-sm-4">CPF:</dt>
                             <dd class="col-sm-8">{{ $membro->cpf ?? 'Não informado' }}</dd>

                             <dt class="col-sm-4">Email:</dt>
                             <dd class="col-sm-8">{{ $membro->email ?? 'Não informado' }}</dd>

                             <dt class="col-sm-4">Telefone:</dt>
                             <dd class="col-sm-8">{{ $membro->telefone ?? 'Não informado' }}</dd>

                             <dt class="col-sm-4">Nascimento:</dt>
                             <dd class="col-sm-8">
                                 {{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado' }}
                             </dd>

                             <dt class="col-sm-4">Batismo:</dt>
                             <dd class="col-sm-8">
                                 {{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não informado' }}
                             </dd>
                         </dl>
                     </div>
                     <div class="col-md-6">
                         <dl class="row">
                             <dt class="col-sm-4">Admissão:</dt>
                             <dd class="col-sm-8">
                                 {{ $membro->data_admissao ? $membro->data_admissao->format('d/m/Y') : 'Não informado' }}
                             </dd>

                             <dt class="col-sm-4">Endereço:</dt>
                             <dd class="col-sm-8">{{ $membro->endereco ?? 'Não informado' }}</dd>

                             <dt class="col-sm-4">Bairro:</dt>
                             <dd class="col-sm-8">{{ $membro->bairro ?? 'Não informado' }}</dd>

                             <dt class="col-sm-4">Cidade/UF:</dt>
                             <dd class="col-sm-8">
                                 {{ ($membro->cidade ?? 'Não informado') . '/' . ($membro->estado ?? '') }}</dd>

                             <dt class="col-sm-4">CEP:</dt>
                             <dd class="col-sm-8">{{ $membro->cep ?? 'Não informado' }}</dd>
                         </dl>
                     </div>
                 </div>
                 <div class="row mt-3">
                     <div class="col-md-12">
                         <h5>Observações:</h5>
                         <p>{{ $membro->observacoes ?? 'Nenhuma observação registrada.' }}</p>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                 <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal"
                     data-bs-target="#modal-edit-{{ $membro->id }}">Editar</button>
             </div>
         </div>
     </div>
 </div>
