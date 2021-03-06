<div class="panel-heading">
    Campanha de Enies Lobby.
</div>
<?php render_campanha_css(); ?>

<div class="panel-body">
    <?php $campanha = DataLoader::load("campanha_enies_lobby"); ?>
    <?php $validator = new CampanhaEniesLobby($campanha, $connection, $userDetails, $protector); ?>
    <?php $etapa = $validator->get_current_stage(); ?>
    <?php render_campanha_etapa($etapa, $validator, "enies_lobby"); ?>
</div>