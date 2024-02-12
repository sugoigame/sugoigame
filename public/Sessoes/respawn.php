<?php if ($userDetails->tripulacao_alive): ?>
    <div class="panel-heading">
        Sua tripulação está bem
    </div>

    <div class="panel-body">
        <a class="btn btn-info link_content" href="./?ses=oceano">
            Ir para o Oceano
        </a>
    </div>
<?php else: ?>
    <div class="panel-heading">
        Você foi derrotado
    </div>

    <div class="panel-body">
        
            
            <p>
                <button class="btn btn-success link_confirm"
                        data-question="Todos os seus tripulantes receberão 100% da vida."
                        href="Mapa/derrotado_ficar_coordenada_tripulacao.php">
                    Recuperar minha tripulação e ficar na coordenada atual
                    
                </button>
            </p>
            
       
    </div>
<?php endif; ?>
