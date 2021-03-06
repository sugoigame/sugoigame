<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        Nova Mensagem
    </h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <label>Para:</label>
        <input type="text" id="nmsg_destinatario" class="form-control">
    </div>
    <div class="form-group">
        <label>Assunto:</label>
        <input type="text" id="nmsg_assunto" class="form-control">
    </div>
    <div class="form-group">
        <label>Mensagem:</label>
        <textarea id="nmsg_texto" class="form-control"></textarea>
    </div>
</div>
<div class="modal-footer">
    <button id="bt_msg_listar" class="btn btn-danger">Cancelar</button>
    <button id="bt_enviar_nmsg" class="btn btn-success">Enviar</button>
</div>