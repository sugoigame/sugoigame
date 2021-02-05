<div class="panel-heading">
    Campanha de Impel Down.
</div>
<?php render_campanha_css(); ?>

<div class="panel-body">
    <?php $campanha = DataLoader::load("campanha_impel_down"); ?>
    <?php $validator = new CampanhaImpelDown($campanha, $connection, $userDetails, $protector); ?>
    <?php $etapa = $validator->get_current_stage(); ?>
    <?php render_campanha_etapa($etapa, $validator, "impel_down"); ?>
</div>